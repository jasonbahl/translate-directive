<?php
namespace PoP\TranslateDirective\Conditional\UserState\Conditional\UserRoles\Hooks;

use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\TranslateDirective\DirectiveResolvers\AbstractTranslateDirectiveResolver;
use PoP\TranslateDirective\Conditional\UserState\Conditional\UserRoles\ComponentConfiguration;
use PoP\UserRoles\Hooks\AbstractMaybeDisableDirectivesIfLoggedInUserDoesNotHaveRolePrivateSchemaHookSet;

class MaybeDisableDirectivesIfLoggedInUserDoesNotHaveRolePrivateSchemaHookSet extends AbstractMaybeDisableDirectivesIfLoggedInUserDoesNotHaveRolePrivateSchemaHookSet
{
    protected function getRoleNames(): array
    {
        if ($roleName = ComponentConfiguration::roleLoggedInUserMustHaveToAccessTranslateDirective()) {
            return [$roleName];
        }
        return [];
    }

    /**
     * Remove directiveName "translate" if the user is not logged in
     *
     * @param boolean $include
     * @param TypeResolverInterface $typeResolver
     * @param string $directiveName
     * @return boolean
     */
    protected function getDirectiveResolverClasses(): array
    {
        return [
            AbstractTranslateDirectiveResolver::class,
        ];
    }
}
