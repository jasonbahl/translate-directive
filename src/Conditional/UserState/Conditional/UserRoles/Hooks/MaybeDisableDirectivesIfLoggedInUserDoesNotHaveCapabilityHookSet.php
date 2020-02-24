<?php
namespace PoP\TranslateDirective\Conditional\UserState\Conditional\UserRoles\Hooks;

use PoP\TranslateDirective\Conditional\UserState\Conditional\UserRoles\ComponentConfiguration;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\UserRoles\Hooks\AbstractMaybeDisableDirectivesIfLoggedInUserDoesNotHaveCapabilityHookSet;

class MaybeDisableDirectivesIfLoggedInUserDoesNotHaveCapabilityHookSet extends AbstractMaybeDisableDirectivesIfLoggedInUserDoesNotHaveCapabilityHookSet
{
    protected function getCapability(): ?string
    {
        return ComponentConfiguration::capabilityLoggedInUserMustHaveToAccessTranslateDirective();
    }

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
