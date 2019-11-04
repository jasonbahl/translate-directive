<?php
namespace PoP\TranslateDirective;

class Environment
{
    public static function getDefaultTranslationProvider()
    {
        return $_ENV['DEFAULT_TRANSLATION_PROVIDER'];
    }

    public static function useAsyncForMultiLanguageTranslation()
    {
        return isset($_ENV['USE_ASYNC_FOR_MULTILANGUAGE_TRANSLATION']) ? strtolower($_ENV['USE_ASYNC_FOR_MULTILANGUAGE_TRANSLATION']) == "true" : true;
    }
}
