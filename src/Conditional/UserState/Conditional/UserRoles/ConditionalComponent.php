<?php
namespace PoP\TranslateDirective\Conditional\UserState\Conditional\UserRoles;

use PoP\TranslateDirective\Conditional\UserState\Conditional\UserRoles\ComponentConfiguration;
use PoP\ComponentModel\Container\ContainerBuilderUtils;
use PoP\ComponentModel\AttachableExtensions\AttachableExtensionGroups;
use PoP\TranslateDirective\Conditional\UserState\Conditional\UserRoles\TypeResolverDecorators\GlobalValidateDoesLoggedInUserHaveRoleForDirectivesPublicSchemaTypeResolverDecorator;
use PoP\TranslateDirective\Conditional\UserState\Conditional\UserRoles\Hooks\MaybeDisableDirectivesIfLoggedInUserDoesNotHaveRolePrivateSchemaHookSet;
use PoP\TranslateDirective\Conditional\UserState\Conditional\UserRoles\TypeResolverDecorators\GlobalValidateDoesLoggedInUserHaveCapabilityForDirectivesPublicSchemaTypeResolverDecorator;
use PoP\TranslateDirective\Conditional\UserState\Conditional\UserRoles\Hooks\MaybeDisableDirectivesIfLoggedInUserDoesNotHaveCapabilityPrivateSchemaHookSet;
use PoP\Root\Component\YAMLServicesTrait;
use PoP\TranslateDirective\Component;

/**
 * Initialize component
 */
class ConditionalComponent
{
    use YAMLServicesTrait;

    public static function init()
    {
        self::initYAMLServices(Component::$COMPONENT_DIR, '/Conditional/UserState/Conditional/UserRoles');
    }

    /**
     * Boot component
     *
     * @return void
     */
    public static function boot()
    {
        // Initialize classes
        self::validateFieldsAndDirectives();
    }

    /**
     * Attach directive resolvers based on environment variables
     *
     * @return void
     */
    protected static function validateFieldsAndDirectives()
    {
        if (!is_null(ComponentConfiguration::roleLoggedInUserMustHaveToAccessTranslateDirective())) {
            ContainerBuilderUtils::instantiateService(MaybeDisableDirectivesIfLoggedInUserDoesNotHaveRolePrivateSchemaHookSet::class);
            GlobalValidateDoesLoggedInUserHaveRoleForDirectivesPublicSchemaTypeResolverDecorator::attach(AttachableExtensionGroups::TYPERESOLVERDECORATORS);
        }
        if (!is_null(ComponentConfiguration::capabilityLoggedInUserMustHaveToAccessTranslateDirective())) {
            ContainerBuilderUtils::instantiateService(MaybeDisableDirectivesIfLoggedInUserDoesNotHaveCapabilityPrivateSchemaHookSet::class);
            GlobalValidateDoesLoggedInUserHaveCapabilityForDirectivesPublicSchemaTypeResolverDecorator::attach(AttachableExtensionGroups::TYPERESOLVERDECORATORS);
        }
    }
}
