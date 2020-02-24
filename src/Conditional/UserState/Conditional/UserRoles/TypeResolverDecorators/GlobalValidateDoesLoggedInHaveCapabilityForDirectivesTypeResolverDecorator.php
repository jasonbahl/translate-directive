<?php
namespace PoP\TranslateDirective\Conditional\UserState\Conditional\UserRoles\TypeResolverDecorators;

use PoP\TranslateDirective\Conditional\UserState\Conditional\UserRoles\ComponentConfiguration;
use PoP\ComponentModel\TypeResolvers\AbstractTypeResolver;
use PoP\UserRoles\Conditional\UserState\TypeResolverDecorators\AbstractValidateDoesLoggedInHaveCapabilityForDirectivesTypeResolverDecorator;

class GlobalValidateDoesLoggedInHaveCapabilityForDirectivesTypeResolverDecorator extends AbstractValidateDoesLoggedInHaveCapabilityForDirectivesTypeResolverDecorator
{
    public static function getClassesToAttachTo(): array
    {
        return array(
            AbstractTypeResolver::class,
        );
    }

    protected function getCapability(): ?string
    {
        return ComponentConfiguration::capabilityLoggedInUserMustHaveToAccessTranslateDirective();
    }

    protected function getDirectiveNames(): array
    {
        return [
            'translate',
        ];
    }
}
