<?php
namespace PoP\TranslateDirective\Conditional\UserState\Conditional\UserRoles\TypeResolverDecorators;

use PoP\ComponentModel\TypeResolvers\AbstractTypeResolver;
use PoP\TranslateDirective\DirectiveResolvers\AbstractTranslateDirectiveResolver;
use PoP\TranslateDirective\Conditional\UserState\Conditional\UserRoles\ComponentConfiguration;
use PoP\UserRoles\Conditional\UserState\TypeResolverDecorators\AbstractValidateDoesLoggedInHaveRoleForDirectivesPublicSchemaTypeResolverDecorator;

class GlobalValidateDoesLoggedInHaveRoleForDirectivesPublicSchemaTypeResolverDecorator extends AbstractValidateDoesLoggedInHaveRoleForDirectivesPublicSchemaTypeResolverDecorator
{
    public static function getClassesToAttachTo(): array
    {
        return array(
            AbstractTypeResolver::class,
        );
    }

    protected function getRoleName(): ?string
    {
        return ComponentConfiguration::roleLoggedInUserMustHaveToAccessTranslateDirective();
    }

    protected function getDirectiveNames(): array
    {
        return [
            AbstractTranslateDirectiveResolver::getDirectiveName(),
        ];
    }
}
