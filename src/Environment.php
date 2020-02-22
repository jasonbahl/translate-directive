<?php
namespace PoP\TranslateDirective;

class Environment
{
    public static function getDefaultTranslationProvider(): ?string
    {
        return $_ENV['DEFAULT_TRANSLATION_PROVIDER'];
    }

    public static function useAsyncForMultiLanguageTranslation(): bool
    {
        return isset($_ENV['USE_ASYNC_FOR_MULTILANGUAGE_TRANSLATION']) ? strtolower($_ENV['USE_ASYNC_FOR_MULTILANGUAGE_TRANSLATION']) == "true" : true;
    }

    public static function userMustBeLoggedInToTranslate(): bool
    {
        return isset($_ENV['USER_MUST_BE_LOGGED_IN_TO_TRANSLATE']) ? strtolower($_ENV['USER_MUST_BE_LOGGED_IN_TO_TRANSLATE']) == "true" : false;
    }

    public static function roleUserMustHaveToTranslate(): ?string
    {
        return isset($_ENV['ROLE_USER_MUST_HAVE_TO_TRANSLATE']) && $_ENV['ROLE_USER_MUST_HAVE_TO_TRANSLATE'] ? strtolower($_ENV['ROLE_USER_MUST_HAVE_TO_TRANSLATE']) : null;
    }
}

