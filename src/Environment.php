<?php
namespace PoP\TranslateDirective;

class Environment
{
    public static function getDefaultTranslationProvider()
    {
        return $_ENV['DEFAULT_TRANSLATION_PROVIDER'];
    }
}

