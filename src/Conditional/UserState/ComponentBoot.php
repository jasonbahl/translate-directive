<?php
namespace PoP\TranslateDirective\Conditional\UserState;

use PoP\TranslateDirective\Conditional\UserState\Environment;
use PoP\API\Environment as APIEnvironment;
use PoP\ComponentModel\Container\ContainerBuilderUtils;
use PoP\ComponentModel\AttachableExtensions\AttachableExtensionGroups;
use PoP\TranslateDirective\Conditional\UserRoles\TypeResolverDecorators\GlobalValidateDoesLoggedInHaveRoleForDirectivesTypeResolverDecorator;
use PoP\TranslateDirective\Conditional\UserState\Hooks\MaybeDisableDirectivesIfUserNotLoggedInHookSet;

/**
 * Initialize component
 */
class ComponentBoot
{
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
            \PoP\TranslateDirective\Conditional\UserState\Conditional\UserRoles\ComponentBoot::boot();
        }
    }

    /**
     * Attach directive resolvers based on environment variables
     *
     * @return void
     */
    protected static function attachDynamicHooks()
    {
        if (Environment::userMustBeLoggedInToAccessTranslateDirective()) {
            if (APIEnvironment::usePrivateSchemaMode()) {
                ContainerBuilderUtils::instantiateService(MaybeDisableDirectivesIfUserNotLoggedInHookSet::class);
            } else {
                GlobalValidateDoesLoggedInHaveRoleForDirectivesTypeResolverDecorator::attach(AttachableExtensionGroups::TYPERESOLVERDECORATORS);
            }
        }
    }
}
