<?php
namespace PoP\TranslateDirective\Conditional\UserState\Conditional\UserRoles\TypeResolverDecorators;

use PoP\TranslateDirective\DirectiveResolvers\AbstractTranslateDirectiveResolver;
use PoP\TranslateDirective\Conditional\UserState\Conditional\UserRoles\ComponentConfiguration;
use PoP\UserRolesAccessControl\TypeResolverDecorators\ValidateDoesLoggedInUserHaveCapabilityForDirectivesPublicSchemaTypeResolverDecorator;

class GlobalValidateDoesLoggedInUserHaveCapabilityForDirectivesPublicSchemaTypeResolverDecorator extends ValidateDoesLoggedInUserHaveCapabilityForDirectivesPublicSchemaTypeResolverDecorator
{
    protected static function getConfiguredEntryList(): array
    {
        if ($capability = ComponentConfiguration::capabilityLoggedInUserMustHaveToAccessTranslateDirective()) {
            return [[AbstractTranslateDirectiveResolver::class, $capability]];
        }
        return [];
    }
}
