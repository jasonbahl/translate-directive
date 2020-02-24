<?php
namespace PoP\TranslateDirective\Conditional\UserState\TypeResolverDecorators;

use PoP\ComponentModel\TypeResolvers\AbstractTypeResolver;
use PoP\TranslateDirective\DirectiveResolvers\AbstractTranslateDirectiveResolver;
use PoP\UserState\TypeResolverDecorators\AbstractValidateIsUserLoggedInForDirectivesTypeResolverDecorator;

class GlobalValidateIsUserLoggedInForDirectivesTypeResolverDecorator extends AbstractValidateIsUserLoggedInForDirectivesTypeResolverDecorator
{
    public static function getClassesToAttachTo(): array
    {
        return array(
            AbstractTypeResolver::class,
        );
    }

    /**
     * Provide the classes for all the directiveResolverClasses that need the "validateIsUserLoggedIn" directive
     *
     * @return array
     */
    protected function getDirectiveResolverClasses(): array
    {
        return [
            AbstractTranslateDirectiveResolver::class,
        ];
    }
}
