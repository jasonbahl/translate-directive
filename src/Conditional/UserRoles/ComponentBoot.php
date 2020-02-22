<?php
namespace PoP\TranslateDirective\Conditional\UserRoles;

use PoP\TranslateDirective\Environment;
use PoP\API\Environment as APIEnvironment;
use PoP\ComponentModel\Container\ContainerBuilderUtils;
use PoP\ComponentModel\AttachableExtensions\AttachableExtensionGroups;
use PoP\TranslateDirective\Conditional\UserRoles\TypeResolverDecorators\GlobalTypeResolverDecorator;
use PoP\TranslateDirective\Conditional\UserRoles\Hooks\MaybeDisableDirectivesIfLoggedInUserDoesNotHaveRoleHookSet;

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
    }

    /**
     * Attach directive resolvers based on environment variables
     *
     * @return void
     */
    protected static function attachDynamicHooks()
    {
        if (!is_null(Environment::roleUserMustHaveToTranslate())) {
            if (APIEnvironment::usePrivateSchemaMode()) {
                ContainerBuilderUtils::instantiateService(MaybeDisableDirectivesIfLoggedInUserDoesNotHaveRoleHookSet::class);
            } else {
                GlobalTypeResolverDecorator::attach(AttachableExtensionGroups::TYPERESOLVERDECORATORS);
            }
        }
    }
}
