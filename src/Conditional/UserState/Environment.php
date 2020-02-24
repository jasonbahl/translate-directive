<?php
namespace PoP\TranslateDirective\Conditional\UserState;

class Environment
{
    public const USER_MUST_BE_LOGGED_IN_TO_ACCESS_TRANSLATE_DIRECTIVE = 'USER_MUST_BE_LOGGED_IN_TO_ACCESS_TRANSLATE_DIRECTIVE';
    public static function userMustBeLoggedInToAccessTranslateDirective(): bool
    {
        return isset($_ENV[self::USER_MUST_BE_LOGGED_IN_TO_ACCESS_TRANSLATE_DIRECTIVE]) ? strtolower($_ENV[self::USER_MUST_BE_LOGGED_IN_TO_ACCESS_TRANSLATE_DIRECTIVE]) == "true" : false;
    }
}

