<?php
namespace PoP\TranslateDirective\Conditional\UserState;

use PoP\ComponentModel\AbstractComponentConfiguration;

class ComponentConfiguration extends AbstractComponentConfiguration
{
    private static $userMustBeLoggedInToAccessTranslateDirective;
    public static function userMustBeLoggedInToAccessTranslateDirective(): bool
    {
        // Define properties
        $envVariable = Environment::USER_MUST_BE_LOGGED_IN_TO_ACCESS_TRANSLATE_DIRECTIVE;
        $selfProperty = &self::$userMustBeLoggedInToAccessTranslateDirective;
        $callback = [Environment::class, 'userMustBeLoggedInToAccessTranslateDirective'];

        // Initialize property from the environment/hook
        self::maybeInitEnvironmentVariable(
            $envVariable,
            $selfProperty,
            $callback
        );
        return $selfProperty;
    }
}

