<?php
namespace PoP\TranslateDirective\Conditional\UserRoles\TypeResolverDecorators;

use PoP\TranslateDirective\Environment;
use PoP\ComponentModel\TypeResolvers\AbstractTypeResolver;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\ComponentModel\Facades\Schema\FieldQueryInterpreterFacade;
use PoP\ComponentModel\TypeResolverDecorators\AbstractTypeResolverDecorator;
use PoP\UserRoles\Conditional\UserState\DirectiveResolvers\ValidateDoesLoggedInUserHaveRoleDirectiveResolver;

class GlobalTypeResolverDecorator extends AbstractTypeResolverDecorator
{
    public static function getClassesToAttachTo(): array
    {
        return array(
            AbstractTypeResolver::class,
        );
    }

    /**
     * Validate that the user has the configured role in order to translate
     *
     * @param TypeResolverInterface $typeResolver
     * @return array
     */
    public function getMandatoryDirectivesForDirectives(TypeResolverInterface $typeResolver): array
    {
        $mandatoryDirectivesForDirectives = [];
        if ($requiredRoleName = Environment::roleUserMustHaveToTranslate()) {
            $fieldQueryInterpreter = FieldQueryInterpreterFacade::getInstance();
            $mandatoryDirectivesForDirectives['translate'] = [
                $fieldQueryInterpreter->getDirective(
                    ValidateDoesLoggedInUserHaveRoleDirectiveResolver::getDirectiveName(),
                    [
                        'role' => $requiredRoleName,
                    ]
                )
            ];
        }
        return $mandatoryDirectivesForDirectives;
    }
}
