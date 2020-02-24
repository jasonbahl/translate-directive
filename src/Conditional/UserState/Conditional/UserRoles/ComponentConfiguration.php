<?php
namespace PoP\TranslateDirective\Conditional\UserState\Conditional\UserRoles;

use PoP\ComponentModel\AbstractComponentConfiguration;

class ComponentConfiguration extends AbstractComponentConfiguration
{
    private static $roleLoggedInUserMustHaveToAccessTranslateDirective;
    public static function roleLoggedInUserMustHaveToAccessTranslateDirective(): ?string
    {
        // Define properties
        $envVariable = Environment::ROLE_LOGGED_IN_USER_MUST_HAVE_TO_ACCESS_TRANSLATE_DIRECTIVE;
        $selfProperty = &self::$roleLoggedInUserMustHaveToAccessTranslateDirective;
        $callback = [Environment::class, 'roleLoggedInUserMustHaveToAccessTranslateDirective'];

        // Initialize property from the environment/hook
        self::maybeInitEnvironmentVariable(
            $envVariable,
            $selfProperty,
            $callback
        );
        return $selfProperty;
    }
}

