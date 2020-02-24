<?php
namespace PoP\TranslateDirective\Conditional\UserState\Hooks;

use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\UserState\Hooks\AbstractMaybeDisableDirectivesIfUserNotLoggedInHookSet;

class MaybeDisableDirectivesIfUserNotLoggedInHookSet extends AbstractMaybeDisableDirectivesIfUserNotLoggedInHookSet
{
    /**
     * Remove directiveName "translate" if the user is not logged in
     *
     * @param boolean $include
     * @param TypeResolverInterface $typeResolver
     * @param string $directiveName
     * @return boolean
     */
    protected function getDirectiveNames(): array
    {
        return [
            'translate',
        ];
    }
}
