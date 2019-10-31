<?php
namespace PoP\TranslateDirective\DirectiveResolvers;
use PoP\ComponentModel\Environment;
use PoP\ComponentModel\GeneralUtils;
use PoP\GuzzleHelpers\GuzzleHelpers;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\ComponentModel\FieldResolvers\PipelinePositions;
use PoP\ComponentModel\FieldResolvers\FieldResolverInterface;
use PoP\ComponentModel\Facades\Schema\FieldQueryInterpreterFacade;
use PoP\ComponentModel\DirectiveResolvers\AbstractDirectiveResolver;
use PoP\ComponentModel\Error;

abstract class AbstractTranslateDirectiveResolver extends AbstractDirectiveResolver
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

    public function resolveDirective(FieldResolverInterface $fieldResolver, array &$resultIDItems, array &$idsDataFields, array &$dbItems, array &$dbErrors, array &$dbWarnings, array &$schemaErrors, array &$schemaWarnings, array &$schemaDeprecations)
    {
        // Replace all the strings with their translation
        $provider = 'google';
        if ($provider) {
            $fieldQueryInterpreter = FieldQueryInterpreterFacade::getInstance();
            $translationAPI = TranslationAPIFacade::getInstance();
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
            $counter = 0;
            foreach ($idsDataFields as $id => $dataFields) {
                // Extract the from/to language from the params
                $resultItem = $resultIDItems[$id];
                list(
                    $resultItemValidDirective,
                    $resultItemDirectiveName,
                    $resultItemDirectiveArgs
                ) = $this->dissectAndValidateDirectiveForResultItem($fieldResolver, $resultItem, $this->directive, $dbErrors, $dbWarnings);
                // Check that the directive is valid. If it is not, $dbErrors will have the error already added
                if (is_null($resultItemValidDirective)) {
                    continue;
                }
                $sourceLang = $resultItemDirectiveArgs['from'];
                $targetLang = $resultItemDirectiveArgs['to'];
                foreach ($dataFields['direct'] as $field) {
                    // Get the fieldOutputKey from the cache, or calculate it
                    if (is_null($fieldOutputKeyCache[$field])) {
                        $fieldOutputKeyCache[$field] = $fieldQueryInterpreter->getFieldOutputKey($field);
                    }
                    $fieldOutputKey = $fieldOutputKeyCache[$field];
                    // Add the text to be translated, and keep the position from where it will be retrieved
                    $contentsBySourceTargetLang[$sourceLang][$targetLang][] = $dbItems[$id][$fieldOutputKey];
                    $translationPositions[$sourceLang][$targetLang][$id][$fieldOutputKey] = $counter;
                    $counter++;
                }
            }
            // Translate all the contents for each pair of from/to languages
            foreach ($contentsBySourceTargetLang as $sourceLang => $targetLangContents) {
                foreach ($targetLangContents as $targetLang => $contents) {
                    // Get the query to send in the request, and execute against the endpoint
                    $query = $this->getQuery($provider, $sourceLang, $targetLang, $contents);
                    $response = GuzzleHelpers::requestJSON($endpointURL, $query);
                    // If the request failed, show an error and do nothing else
                    if (GeneralUtils::isError($response)) {
                        $error = $response;
                        $failureMessage = sprintf(
                            $translationAPI->__('There was an error requesting data from the Provider API: %s', 'component-model'),
                            $error->getErrorMessage()
                        );
                        $this->processFailure($failureMessage, [], $idsDataFields, $schemaErrors, $schemaWarnings);
                        return;
                    }
                    $response = (array)$response;
                    // Validate if the response is the translation, or some error from the service provider
                    if ($errorMessage = $this->getErrorMessageFromResponse($provider, $response)) {
                        $failureMessage = sprintf(
                            $translationAPI->__('There was an error processing the response from the Provider API: %s', 'component-model'),
                            $errorMessage
                        );
                        $this->processFailure($failureMessage, [], $idsDataFields, $schemaErrors, $schemaWarnings);
                        return;
                    }
                    $translations = $this->extractTranslationsFromResponse($provider, $response);
                    // Iterate through the translations, and replace the original content in the dbItems object
                    foreach ($idsDataFields as $id => $dataFields) {
                        foreach ($dataFields['direct'] as $field) {
                            $fieldOutputKey = $fieldOutputKeyCache[$field];
                            // Add the text to be translated, and keep the position from where it will be retrieved
                            $position = $translationPositions[$sourceLang][$targetLang][$id][$fieldOutputKey];
                            $dbItems[$id][$fieldOutputKey] = $translations[$position];
                        }
                    }
                }
            }
        }
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
}
