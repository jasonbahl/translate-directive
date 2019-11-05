<?php
namespace PoP\TranslateDirective\DirectiveResolvers;

use PoP\ComponentModel\Error;
use PoP\ComponentModel\GeneralUtils;
use PoP\GuzzleHelpers\GuzzleHelpers;
use PoP\TranslateDirective\Environment;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\TranslateDirective\Schema\SchemaDefinition;
use PoP\ComponentModel\FieldResolvers\PipelinePositions;
use PoP\TranslateDirective\Facades\TranslationServiceFacade;
use PoP\ComponentModel\FieldResolvers\FieldResolverInterface;
use PoP\ComponentModel\Environment as ComponentModelEnvironment;
use PoP\ComponentModel\Facades\Schema\FieldQueryInterpreterFacade;
use PoP\ComponentModel\DirectiveResolvers\AbstractSchemaDirectiveResolver;
use PoP\ComponentModel\DataloaderInterface;

abstract class AbstractTranslateDirectiveResolver extends AbstractSchemaDirectiveResolver
{
    const DIRECTIVE_NAME = 'translate';
    public static function getDirectiveName(): string {
        return self::DIRECTIVE_NAME;
    }

    /**
     * This directive must be executed after ResolveAndMerge, and modify values directly on the returned DB items
     *
     * @return void
     */
    public function getPipelinePosition(): string
    {
        return PipelinePositions::BACK;
    }

    /**
     * Allow to translate from the translation
     *
     * @return boolean
     */
    public function canExecuteMultipleTimesInField(): bool
    {
        return true;
    }

        /**
     * The name of the API's provider
     *
     * @return array
     */
    public abstract function getProvidersToResolve(): array;

    /**
     * If the provider is indicated through $directiveArgs, use it
     * Otherwise, use the default one, if set
     *
     * @param array $directiveArgs
     * @return void
     */
    protected function getProvider(array $directiveArgs): ?string
    {
        if (isset($directiveArgs['provider'])) {
            return $directiveArgs['provider'];
        }
        $translationService = TranslationServiceFacade::getInstance();
        return $translationService->getDefaultProvider();
    }

    /**
     * Only process the directive if this directiveResolver can handle the provider
     *
     * @param FieldResolverInterface $fieldResolver
     * @param string $directiveName
     * @param array $directiveArgs
     * @return boolean
     */
    public function resolveCanProcess(FieldResolverInterface $fieldResolver, string $directiveName, array $directiveArgs = []): bool
    {
        $provider = $this->getProvider($directiveArgs);
        return in_array($provider, $this->getProvidersToResolve());
    }

    public function resolveDirective(DataloaderInterface $dataloader, FieldResolverInterface $fieldResolver, array &$resultIDItems, array &$idsDataFields, array &$dbItems, array &$dbErrors, array &$dbWarnings, array &$schemaErrors, array &$schemaWarnings, array &$schemaDeprecations, array &$previousDBItems, array &$variables, array &$messages)
    {
        $fieldQueryInterpreter = FieldQueryInterpreterFacade::getInstance();
        $translationAPI = TranslationAPIFacade::getInstance();

        // Retrieve the provider from the directiveArgs. The provider is passed as a 'static' attribute (to decide the DirectiveResolver), so it can't be taken from the resultItem schema (as is the case with the from/to lang params)
        $directiveArgs = $fieldQueryInterpreter->extractStaticDirectiveArguments($this->directive);
        $provider = $this->getProvider($directiveArgs);
        // Make sure that there is an endpoint
        $endpointURL = $this->getEndpoint($provider);
        if (!$endpointURL) {
            // Give an error message for all failed fields
            $failureMessage = sprintf(
                $translationAPI->__('Provider \'%s\' doesn\'t have an endpoint URL configured, so it can\'t proceed to do the translation', 'component-model'),
                $provider
            );
            $this->processFailure($failureMessage, [], $idsDataFields, $schemaErrors, $schemaWarnings);
            // Nothing else to do
            return;
        }
        // Keep all the translations to be made by pairs of to/from language
        $contentsBySourceTargetLang = [];
        // Keep track of which translation must be placed where
        $translationPositions = [];
        // Collect all the pieces of text to translate
        $fieldOutputKeyCache = [];
        $counters = [];
        foreach ($idsDataFields as $id => $dataFields) {
            // Extract the from/to language from the params. Each pair of from/to languages can be set on a result-by-result basis,
            // that's why it's taken from resultItem and not from schema (as the provider is)
            $resultItemVariables = $this->getVariablesForResultItem($id, $variables, $messages);
            $resultItem = $resultIDItems[$id];
            list(
                $resultItemValidDirective,
                $resultItemDirectiveName,
                $resultItemDirectiveArgs
            ) = $this->dissectAndValidateDirectiveForResultItem($fieldResolver, $resultItem, $dbErrors, $dbWarnings, $resultItemVariables);
            // Check that the directive is valid. If it is not, $dbErrors will have the error already added
            if (is_null($resultItemValidDirective)) {
                continue;
            }
            $sourceLang = $resultItemDirectiveArgs['from'];
            $targetLang = $resultItemDirectiveArgs['to'];
            if (!isset($counters[$sourceLang][$targetLang])) {
                $counters[$sourceLang][$targetLang] = 0;
            }
            foreach ($dataFields['direct'] as $field) {
                // Get the fieldOutputKey from the cache, or calculate it
                if (is_null($fieldOutputKeyCache[$field])) {
                    $fieldOutputKeyCache[$field] = $fieldQueryInterpreter->getFieldOutputKey($field);
                }
                $fieldOutputKey = $fieldOutputKeyCache[$field];
                // Add the text to be translated, and keep the position from where it will be retrieved
                $contentsBySourceTargetLang[$sourceLang][$targetLang][] = $dbItems[$id][$fieldOutputKey];
                $translationPositions[$sourceLang][$targetLang][$id][$fieldOutputKey] = $counters[$sourceLang][$targetLang];
                $counters[$sourceLang][$targetLang]++;
            }
        }
        // Translate all the contents for each pair of from/to languages
        // For instance, this query will translate between 3 pairs: EN to ES, EN to FR, and EN to DE:
        // --> ?query=posts.id|title|title@random<translate(en,arrayRandom([es,fr,de]))>
        $queries = [];
        foreach ($contentsBySourceTargetLang as $sourceLang => $targetLangContents) {
            foreach ($targetLangContents as $targetLang => $contents) {
                $queries[] = $this->getQuery($provider, $sourceLang, $targetLang, $contents);
            }
        }
        // There are 2 ways to fetch the data when there are many languages:
        // Synchronously and Asynchronously
        // 1. Synchronously
        // --> Slower: it waits for one translation to be fetched before dispatching a new one
        // --> Resilient: if any translation fails (eg: the language code is wrong), it doesn't affect the translation for all other languages
        // 2. Asynchronously:
        // --> Faster: all translations are dispatched concurrently to the API
        // --> Fragile: if the translation for a single pair of languages fails, then the translation for all pairs fail!
        if (Environment::useAsyncForMultiLanguageTranslation()) {
            // Send all the queries for all languages all concurrently and asynchronously
            $responses = GuzzleHelpers::requestAsyncJSON($endpointURL, $queries);
            // If the request failed, show an error and do nothing else
            if (GeneralUtils::isError($responses)) {
                $failureMessage = $this->getClientFailureMessage($responses, $provider);
                $this->processFailure($failureMessage, [], $idsDataFields, $schemaErrors, $schemaWarnings);
                return;
            }
        } else {
            foreach ($queries as $query) {
                $response = GuzzleHelpers::requestJSON($endpointURL, $query);
                // If the request failed, show an error and do nothing else
                if (GeneralUtils::isError($response)) {
                    $failureMessage = $this->getClientFailureMessage($response, $provider);
                    $this->processFailure($failureMessage, [], $idsDataFields, $schemaErrors, $schemaWarnings);
                }
                $responses[] = $response;
            }
        }

        // Iterate through all the responses
        $removeFieldIfDirectiveFailed = ComponentModelEnvironment::removeFieldIfDirectiveFailed();
        $counter = 0;
        foreach ($contentsBySourceTargetLang as $sourceLang => $targetLangContents) {
            foreach ($targetLangContents as $targetLang => $contents) {
                $response = $responses[$counter];
                $counter++;
                // If any synchronous request failed, this response will be an error. Skip it (error message already added)
                if (GeneralUtils::isError($response)) {
                    // Add a DB error for all the invoved dbItems
                    foreach ($translationPositions[$sourceLang][$targetLang] as $id => $fieldOutputKeyPosition) {
                        foreach ($fieldOutputKeyPosition as $fieldOutputKey => $position) {
                            if ($removeFieldIfDirectiveFailed) {
                                $dbErrors[(string)$id][$this->directive][] = sprintf(
                                    $translationAPI->__('Due to some previous error, this directive has not been executed on property \'%s\' for object with ID \'%s\'', 'component-model'),
                                    $fieldOutputKey,
                                    $id
                                );
                            } else {
                                $dbWarnings[(string)$id][$this->directive][] = sprintf(
                                    $translationAPI->__('Due to some previous warning, property \'%s\' for object with ID \'%s\' has not been translated', 'component-model'),
                                    $fieldOutputKey,
                                    $id
                                );
                            }
                        }
                    }
                    continue;
                }
                $response = (array)$response;

                // Validate if the response is the translation, or some error from the service provider
                if ($errorMessage = $this->getErrorMessageFromResponse($provider, $response)) {
                    $failureMessage = sprintf(
                        $translationAPI->__('There was an error processing the response from the Provider API: %s', 'component-model'),
                        $errorMessage
                    );
                    $this->processFailure($failureMessage, [], $idsDataFields, $schemaErrors, $schemaWarnings);
                    continue;
                }
                $translations = $this->extractTranslationsFromResponse($provider, $response);
                // Iterate through the translations, and replace the original content in the dbItems object
                foreach ($translationPositions[$sourceLang][$targetLang] as $id => $fieldOutputKeyPosition) {
                    foreach ($fieldOutputKeyPosition as $fieldOutputKey => $position) {
                        $dbItems[$id][$fieldOutputKey] = $translations[$position];
                    }
                }
            }
        }
    }

    /**
     * Failure message to show the user, originated from Guzzle client's error
     *
     * @param Error $error
     * @param string $provider
     * @return void
     */
    protected function getClientFailureMessage(Error $error, string $provider): string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        return sprintf(
            $translationAPI->__('There was an error requesting data from the Provider API: %s', 'component-model'),
            $error->getErrorMessage()
        );
    }

    protected abstract function getEndpoint(string $provider): ?string;

    protected function getQuery(string $provider, string $sourceLang, string $targetLang, array $contents): array
    {
        return [];
    }

    protected function getErrorMessageFromResponse(string $provider, array $response): ?string
    {
        return null;
    }

    protected function extractTranslationsFromResponse(string $provider, array $response): array
    {
        return $response;
    }
    public function getSchemaDirectiveDescription(FieldResolverInterface $fieldResolver): ?string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        return $translationAPI->__('Translate a string using the API from some provider', 'translate-directive');
    }
    public function getSchemaDirectiveArgs(FieldResolverInterface $fieldResolver): array
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        return [
            [
                SchemaDefinition::ARGNAME_NAME => 'from',
                SchemaDefinition::ARGNAME_TYPE => SchemaDefinition::TYPE_STRING,
                SchemaDefinition::ARGNAME_DESCRIPTION => $translationAPI->__('Source language code, corresponding to the provider\'s specifications', 'translate-directive'),
                SchemaDefinition::ARGNAME_MANDATORY => true,
            ],
            [
                SchemaDefinition::ARGNAME_NAME => 'to',
                SchemaDefinition::ARGNAME_TYPE => SchemaDefinition::TYPE_STRING,
                SchemaDefinition::ARGNAME_DESCRIPTION => $translationAPI->__('Target language code, corresponding to the provider\'s specifications', 'translate-directive'),
                SchemaDefinition::ARGNAME_MANDATORY => true,
            ],
            [
                SchemaDefinition::ARGNAME_NAME => 'provider',
                SchemaDefinition::ARGNAME_TYPE => SchemaDefinition::TYPE_STRING,
                SchemaDefinition::ARGNAME_DESCRIPTION => $translationAPI->__('The name of the provider whose API to use for the translation. If this value is not provided, a default provider will be used', 'translate-directive'),
            ],
        ];
    }

    /**
     * Function to override
     */
    protected function addSchemaDefinitionForDirective(array &$schemaDefinition)
    {
        // Further add for which providers it works
        $schemaDefinition[SchemaDefinition::ARGNAME_FOR_PROVIDERS] = $this->getProvidersToResolve();
    }
}
