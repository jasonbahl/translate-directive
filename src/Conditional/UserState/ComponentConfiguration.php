<?php
namespace PoP\TranslateDirective\Conditional\UserState;

use PoP\Hooks\Facades\HooksAPIFacade;

class ComponentConfiguration
{
    public const HOOK_PLACEHOLDER = __CLASS__.':%s';
    private static $userMustBeLoggedInToAccessTranslateDirective;
    public static function userMustBeLoggedInToAccessTranslateDirective(): bool
    {
        if (is_null(self::$userMustBeLoggedInToAccessTranslateDirective)) {
            $hooksAPI = HooksAPIFacade::getInstance();
            $hook = sprintf(
                self::HOOK_PLACEHOLDER,
                'USER_MUST_BE_LOGGED_IN_TO_ACCESS_TRANSLATE_DIRECTIVE'
            );
            self::$userMustBeLoggedInToAccessTranslateDirective = $hooksAPI->applyFilters(
                $hook,
                Environment::userMustBeLoggedInToAccessTranslateDirective()
            );
        }
        return self::$userMustBeLoggedInToAccessTranslateDirective;
    }
}

