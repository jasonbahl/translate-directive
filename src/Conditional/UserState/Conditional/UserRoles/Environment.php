<?php
namespace PoP\TranslateDirective\Conditional\UserState\Conditional\UserRoles;

class Environment
{
    public const ROLE_LOGGED_IN_USER_MUST_HAVE_TO_ACCESS_TRANSLATE_DIRECTIVE = 'ROLE_LOGGED_IN_USER_MUST_HAVE_TO_ACCESS_TRANSLATE_DIRECTIVE';
    public const CAPABILITY_LOGGED_IN_USER_MUST_HAVE_TO_ACCESS_TRANSLATE_DIRECTIVE = 'CAPABILITY_LOGGED_IN_USER_MUST_HAVE_TO_ACCESS_TRANSLATE_DIRECTIVE';

    public static function roleLoggedInUserMustHaveToAccessTranslateDirective(): ?string
    {
        return isset($_ENV[self::ROLE_LOGGED_IN_USER_MUST_HAVE_TO_ACCESS_TRANSLATE_DIRECTIVE]) && $_ENV[self::ROLE_LOGGED_IN_USER_MUST_HAVE_TO_ACCESS_TRANSLATE_DIRECTIVE] ? strtolower($_ENV[self::ROLE_LOGGED_IN_USER_MUST_HAVE_TO_ACCESS_TRANSLATE_DIRECTIVE]) : null;
    }

    public static function capabilityLoggedInUserMustHaveToAccessTranslateDirective(): ?string
    {
        return isset($_ENV[self::CAPABILITY_LOGGED_IN_USER_MUST_HAVE_TO_ACCESS_TRANSLATE_DIRECTIVE]) && $_ENV[self::CAPABILITY_LOGGED_IN_USER_MUST_HAVE_TO_ACCESS_TRANSLATE_DIRECTIVE] ? strtolower($_ENV[self::CAPABILITY_LOGGED_IN_USER_MUST_HAVE_TO_ACCESS_TRANSLATE_DIRECTIVE]) : null;
    }
}

