<?php

declare(strict_types=1);

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
}
