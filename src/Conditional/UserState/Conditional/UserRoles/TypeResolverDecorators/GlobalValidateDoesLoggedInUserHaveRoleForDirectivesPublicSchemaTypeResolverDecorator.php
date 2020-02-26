<?php
namespace PoP\TranslateDirective\Conditional\UserState\Conditional\UserRoles\TypeResolverDecorators;

use PoP\ComponentModel\TypeResolvers\AbstractTypeResolver;
use PoP\TranslateDirective\DirectiveResolvers\AbstractTranslateDirectiveResolver;
use PoP\TranslateDirective\Conditional\UserState\Conditional\UserRoles\ComponentConfiguration;
use PoP\UserRolesAccessControl\TypeResolverDecorators\AbstractValidateDoesLoggedInUserHaveRoleForDirectivesPublicSchemaTypeResolverDecorator;

class GlobalValidateDoesLoggedInUserHaveRoleForDirectivesPublicSchemaTypeResolverDecorator extends AbstractValidateDoesLoggedInUserHaveRoleForDirectivesPublicSchemaTypeResolverDecorator
{
    public static function getClassesToAttachTo(): array
    {
        return array(
            AbstractTypeResolver::class,
        );
    }

    protected function getRoleNames(): array
    {
        if ($roleName = ComponentConfiguration::roleLoggedInUserMustHaveToAccessTranslateDirective()) {
            return [$roleName];
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
