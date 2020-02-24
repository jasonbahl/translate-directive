<?php
namespace PoP\TranslateDirective\Conditional\UserState\Conditional\UserRoles\Hooks;

use PoP\TranslateDirective\Conditional\UserState\Conditional\UserRoles\ComponentConfiguration;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\UserRoles\Hooks\AbstractMaybeDisableDirectivesIfLoggedInUserDoesNotHaveRoleHookSet;

class MaybeDisableDirectivesIfLoggedInUserDoesNotHaveRoleHookSet extends AbstractMaybeDisableDirectivesIfLoggedInUserDoesNotHaveRoleHookSet
{
    protected function getRoleName(): ?string
    {
        return ComponentConfiguration::roleLoggedInUserMustHaveToAccessTranslateDirective();
    }

    /**
     * Remove directiveName "roles" if the user is not logged in
     *
     * @param boolean $include
     * @param TypeResolverInterface $typeResolver
     * @param string $directiveName
     * @return boolean
     */
    protected function removeDirectiveNames(TypeResolverInterface $typeResolver, string $directiveName): bool
    {
        return $directiveName == 'translate';
    }
}
