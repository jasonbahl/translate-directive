<?php

declare(strict_types=1);

namespace PoPSchema\TranslateDirective;

class Environment
{
    public static function getDefaultTranslationProvider(): ?string
    {
        return isset($_ENV['DEFAULT_TRANSLATION_PROVIDER']) ? $_ENV['DEFAULT_TRANSLATION_PROVIDER'] : null;
    }

    public static function useAsyncForMultiLanguageTranslation(): bool
    {
        return isset($_ENV['USE_ASYNC_FOR_MULTILANGUAGE_TRANSLATION']) ? strtolower($_ENV['USE_ASYNC_FOR_MULTILANGUAGE_TRANSLATION']) == "true" : true;
    }
}
