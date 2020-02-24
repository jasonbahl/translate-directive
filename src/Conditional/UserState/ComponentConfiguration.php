<?php
namespace PoP\TranslateDirective\Conditional\UserState;

use PoP\ComponentModel\AbstractComponentConfiguration;

class ComponentConfiguration extends AbstractComponentConfiguration
{
    private static $userMustBeLoggedInToAccessTranslateDirective;
    public static function userMustBeLoggedInToAccessTranslateDirective(): bool
    {
        $envVariable = Environment::USER_MUST_BE_LOGGED_IN_TO_ACCESS_TRANSLATE_DIRECTIVE;
        $selfProperty = &self::$userMustBeLoggedInToAccessTranslateDirective;
        self::maybeInitEnvironmentVariable(
            $envVariable,
            $selfProperty,
            [Environment::class, 'userMustBeLoggedInToAccessTranslateDirective']
        );
        return $selfProperty;
    }
}

