<?php
namespace PoP\TranslateDirective\Conditional\UserState\Conditional\UserRoles;

use PoP\ComponentModel\AbstractComponentConfiguration;

class ComponentConfiguration extends AbstractComponentConfiguration
{
    private static $roleLoggedInUserMustHaveToAccessTranslateDirective;
    public static function roleLoggedInUserMustHaveToAccessTranslateDirective(): ?string
    {
        $envVariable = Environment::ROLE_LOGGED_IN_USER_MUST_HAVE_TO_ACCESS_TRANSLATE_DIRECTIVE;
        $selfProperty = &self::$roleLoggedInUserMustHaveToAccessTranslateDirective;
        self::maybeInitEnvironmentVariable(
            $envVariable,
            $selfProperty,
            [Environment::class, 'roleLoggedInUserMustHaveToAccessTranslateDirective']
        );
        return $selfProperty;
    }
}

