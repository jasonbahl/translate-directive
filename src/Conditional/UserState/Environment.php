<?php
namespace PoP\TranslateDirective\Conditional\UserState;

class Environment
{
    public static function userMustBeLoggedInToAccessTranslateDirective(): bool
    {
        return isset($_ENV['USER_MUST_BE_LOGGED_IN_TO_ACCESS_TRANSLATE_DIRECTIVE']) ? strtolower($_ENV['USER_MUST_BE_LOGGED_IN_TO_ACCESS_TRANSLATE_DIRECTIVE']) == "true" : false;
    }
}

