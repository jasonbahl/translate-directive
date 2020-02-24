<?php
namespace PoP\TranslateDirective\Conditional\UserState\Conditional\UserRoles;

use PoP\TranslateDirective\Conditional\UserState\Conditional\UserRoles\ComponentConfiguration;
use PoP\API\Environment as APIEnvironment;
use PoP\ComponentModel\Container\ContainerBuilderUtils;
use PoP\ComponentModel\AttachableExtensions\AttachableExtensionGroups;
use PoP\TranslateDirective\Conditional\UserState\Conditional\UserRoles\TypeResolverDecorators\GlobalValidateDoesLoggedInHaveRoleForDirectivesPublicSchemaTypeResolverDecorator;
use PoP\TranslateDirective\Conditional\UserState\Conditional\UserRoles\Hooks\MaybeDisableDirectivesIfLoggedInUserDoesNotHaveRolePrivateSchemaHookSet;
use PoP\TranslateDirective\Conditional\UserState\Conditional\UserRoles\TypeResolverDecorators\GlobalValidateDoesLoggedInHaveCapabilityForDirectivesTypeResolverDecorator;
use PoP\TranslateDirective\Conditional\UserState\Conditional\UserRoles\Hooks\MaybeDisableDirectivesIfLoggedInUserDoesNotHaveCapabilityHookSet;
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
            GlobalValidateDoesLoggedInHaveRoleForDirectivesPublicSchemaTypeResolverDecorator::attach(AttachableExtensionGroups::TYPERESOLVERDECORATORS);
        }
        if (!is_null(ComponentConfiguration::capabilityLoggedInUserMustHaveToAccessTranslateDirective())) {
            if (APIEnvironment::usePrivateSchemaMode()) {
                ContainerBuilderUtils::instantiateService(MaybeDisableDirectivesIfLoggedInUserDoesNotHaveCapabilityHookSet::class);
            } else {
                GlobalValidateDoesLoggedInHaveCapabilityForDirectivesTypeResolverDecorator::attach(AttachableExtensionGroups::TYPERESOLVERDECORATORS);
            }
        }
    }
}
