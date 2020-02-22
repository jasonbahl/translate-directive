<?php
namespace PoP\TranslateDirective\Conditional\UserRoles\Hooks;

use PoP\TranslateDirective\Environment;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\UserRoles\Hooks\AbstractMaybeDisableDirectivesIfLoggedInUserDoesNotHaveRoleHookSet;

class MaybeDisableDirectivesIfLoggedInUserDoesNotHaveRoleHookSet extends AbstractMaybeDisableDirectivesIfLoggedInUserDoesNotHaveRoleHookSet
{
    protected function getRoleName(): ?string
    {
        return Environment::roleUserMustHaveToTranslate();
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
