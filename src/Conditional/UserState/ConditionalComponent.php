<?php
namespace PoP\TranslateDirective\Conditional\UserState;

use PoP\TranslateDirective\Conditional\UserState\ComponentConfiguration;
use PoP\API\Environment as APIEnvironment;
use PoP\ComponentModel\Container\ContainerBuilderUtils;
use PoP\ComponentModel\AttachableExtensions\AttachableExtensionGroups;
use PoP\TranslateDirective\Conditional\UserState\Conditional\UserRoles\TypeResolverDecorators\GlobalValidateDoesLoggedInHaveRoleForDirectivesTypeResolverDecorator;
use PoP\TranslateDirective\Conditional\UserState\Hooks\MaybeDisableDirectivesIfUserNotLoggedInHookSet;
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
        self::initYAMLServices(Component::$COMPONENT_DIR, '/Conditional/UserState');

        if (class_exists('\PoP\UserRoles\Component')) {
            \PoP\TranslateDirective\Conditional\UserState\Conditional\UserRoles\ConditionalComponent::init();
        }
    }

    /**
     * Boot component
     *
     * @return void
     */
    public static function boot()
    {
        // Initialize classes
        self::attachDynamicHooks();

        if (class_exists('\PoP\UserRoles\Component')) {
            \PoP\TranslateDirective\Conditional\UserState\Conditional\UserRoles\ConditionalComponent::boot();
        }
    }

    /**
     * Attach directive resolvers based on environment variables
     *
     * @return void
     */
    protected static function attachDynamicHooks()
    {
        if (ComponentConfiguration::userMustBeLoggedInToAccessTranslateDirective()) {
            if (APIEnvironment::usePrivateSchemaMode()) {
                ContainerBuilderUtils::instantiateService(MaybeDisableDirectivesIfUserNotLoggedInHookSet::class);
            } else {
                GlobalValidateDoesLoggedInHaveRoleForDirectivesTypeResolverDecorator::attach(AttachableExtensionGroups::TYPERESOLVERDECORATORS);
            }
        }
    }
}
