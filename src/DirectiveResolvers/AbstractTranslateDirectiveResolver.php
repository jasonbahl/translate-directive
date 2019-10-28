<?php
namespace PoP\TranslateDirective\DirectiveResolvers;
use PoP\ComponentModel\GeneralUtils;
use PoP\ComponentModel\FieldResolvers\PipelinePositions;
use PoP\ComponentModel\FieldResolvers\FieldResolverInterface;
use PoP\ComponentModel\Facades\Schema\FieldQueryInterpreterFacade;
use PoP\ComponentModel\DirectiveResolvers\AbstractDirectiveResolver;
use PoP\GuzzleHelpers\GuzzleHelpers;

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
        $sourceLang = 'en';
        $targetLang = 'es';
        $contents = [];
        // Keep track of which translation must be placed where
        $translationPositions = [];
        $counter = 0;
        if ($provider) {
            // Collect all the pieces of text to translate
            $fieldQueryInterpreter = FieldQueryInterpreterFacade::getInstance();
            $fieldOutputKeyCache = [];
            foreach ($idsDataFields as $id => $dataFields) {
                foreach ($dataFields['direct'] as $field) {
                    // Get the fieldOutputKey from the cache, or calculate it
                    if (is_null($fieldOutputKeyCache[$field])) {
                        $fieldOutputKeyCache[$field] = $fieldQueryInterpreter->getFieldOutputKey($field);
                    }
                    $fieldOutputKey = $fieldOutputKeyCache[$field];
                    // Add the text to be translated, and keep the position from where it will be retrieved
                    $contents[] = $dbItems[$id][$fieldOutputKey];
                    $translationPositions[$id][$fieldOutputKey] = $counter;
                    $counter++;
                }
            }
        }
        if ($contents) {
            // Execute the endpoint
            if ($endpointURL = $this->getEndpoint($provider, $sourceLang, $targetLang, $contents)) {
                $query = $this->getQuery($provider, $sourceLang, $targetLang, $contents);
                $response = GuzzleHelpers::requestJSON($endpointURL, $query);
                if (GeneralUtils::isError($response)) {
                    return $response;
                }
                $translations = $this->extractTranslationsFromResponse($provider, $response);
                // Iterate through the translations, and replace the original content in the dbItems object
                foreach ($idsDataFields as $id => $dataFields) {
                    foreach ($dataFields['direct'] as $field) {
                        $fieldOutputKey = $fieldOutputKeyCache[$field];
                        // Add the text to be translated, and keep the position from where it will be retrieved
                        $position = $translationPositions[$id][$fieldOutputKey];
                        $dbItems[$id][$fieldOutputKey] = $translations[$position];
                    }
                }
            }
        }
    }

    protected abstract function getEndpoint(string $provider, string $sourceLang, string $targetLang, array $contents);

    protected function getQuery(string $provider, string $sourceLang, string $targetLang, array $contents)
    {
        return null;
    }

    protected function extractTranslationsFromResponse(string $provider, array $response): array
    {
        return $response;
    }
}
