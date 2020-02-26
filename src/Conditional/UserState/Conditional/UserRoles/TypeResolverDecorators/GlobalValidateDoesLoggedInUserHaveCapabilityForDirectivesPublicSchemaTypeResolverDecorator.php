<?php
namespace PoP\TranslateDirective\Conditional\UserState\Conditional\UserRoles\TypeResolverDecorators;

use PoP\ComponentModel\TypeResolvers\AbstractTypeResolver;
use PoP\TranslateDirective\DirectiveResolvers\AbstractTranslateDirectiveResolver;
use PoP\TranslateDirective\Conditional\UserState\Conditional\UserRoles\ComponentConfiguration;
use PoP\UserRolesAccessControl\TypeResolverDecorators\AbstractValidateDoesLoggedInUserHaveCapabilityForDirectivesPublicSchemaTypeResolverDecorator;

class GlobalValidateDoesLoggedInUserHaveCapabilityForDirectivesPublicSchemaTypeResolverDecorator extends AbstractValidateDoesLoggedInUserHaveCapabilityForDirectivesPublicSchemaTypeResolverDecorator
{
    public static function getClassesToAttachTo(): array
    {
        return array(
            AbstractTypeResolver::class,
        );
    }

    protected function getCapabilities(): array
    {
        if ($capability = ComponentConfiguration::capabilityLoggedInUserMustHaveToAccessTranslateDirective()) {
            return [$capability];
        }
        return [];
    }

    protected function getDirectiveResolverClasses(): array
    {
        return [
            AbstractTranslateDirectiveResolver::class,
        ];
    }
}
