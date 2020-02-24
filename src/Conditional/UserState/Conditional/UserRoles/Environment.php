<?php
namespace PoP\TranslateDirective\Conditional\UserState\Conditional\UserRoles;

class Environment
{
    public static function roleLoggedInUserMustHaveToAccessTranslateDirective(): ?string
    {
        return isset($_ENV['ROLE_LOGGED_IN_USER_MUST_HAVE_TO_ACCESS_TRANSLATE_DIRECTIVE']) && $_ENV['ROLE_LOGGED_IN_USER_MUST_HAVE_TO_ACCESS_TRANSLATE_DIRECTIVE'] ? strtolower($_ENV['ROLE_LOGGED_IN_USER_MUST_HAVE_TO_ACCESS_TRANSLATE_DIRECTIVE']) : null;
    }
}

