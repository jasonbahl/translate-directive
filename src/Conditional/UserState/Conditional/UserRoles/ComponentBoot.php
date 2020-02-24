<?php
namespace PoP\TranslateDirective\Conditional\UserState\Conditional\UserRoles;

use PoP\TranslateDirective\Conditional\UserState\Conditional\UserRoles\Environment;
use PoP\API\Environment as APIEnvironment;
use PoP\ComponentModel\Container\ContainerBuilderUtils;
use PoP\ComponentModel\AttachableExtensions\AttachableExtensionGroups;
use PoP\TranslateDirective\Conditional\UserState\Conditional\UserRoles\TypeResolverDecorators\GlobalValidateDoesLoggedInHaveRoleForDirectivesTypeResolverDecorator;
use PoP\TranslateDirective\Conditional\UserState\Conditional\UserRoles\Hooks\MaybeDisableDirectivesIfLoggedInUserDoesNotHaveRoleHookSet;

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
        if (!is_null(Environment::roleLoggedInUserMustHaveToAccessTranslateDirective())) {
            if (APIEnvironment::usePrivateSchemaMode()) {
                ContainerBuilderUtils::instantiateService(MaybeDisableDirectivesIfLoggedInUserDoesNotHaveRoleHookSet::class);
            } else {
                GlobalValidateDoesLoggedInHaveRoleForDirectivesTypeResolverDecorator::attach(AttachableExtensionGroups::TYPERESOLVERDECORATORS);
            }
        }
    }
}
