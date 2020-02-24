<?php
namespace PoP\TranslateDirective\Conditional\UserRoles\TypeResolverDecorators;

use PoP\TranslateDirective\Environment;
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
        return Environment::roleUserMustHaveToTranslate();
    }

    protected function getDirectiveNames(): array
    {
        return [
            'translate',
        ];
    }
}
