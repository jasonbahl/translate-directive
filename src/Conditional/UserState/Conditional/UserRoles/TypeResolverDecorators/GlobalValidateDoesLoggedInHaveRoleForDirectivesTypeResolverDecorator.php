<?php
namespace PoP\TranslateDirective\Conditional\UserState\Conditional\UserRoles\TypeResolverDecorators;

use PoP\TranslateDirective\Conditional\UserState\Conditional\UserRoles\ComponentConfiguration;
use PoP\ComponentModel\TypeResolvers\AbstractTypeResolver;
use PoP\UserRoles\Conditional\UserState\TypeResolverDecorators\AbstractValidateDoesLoggedInHaveRoleForDirectivesTypeResolverDecorator;

class GlobalValidateDoesLoggedInHaveRoleForDirectivesTypeResolverDecorator extends AbstractValidateDoesLoggedInHaveRoleForDirectivesTypeResolverDecorator
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
            'translate',
        ];
    }
}
