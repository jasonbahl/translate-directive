<?php
namespace PoP\TranslateDirective\Conditional\UserRoles;

use PoP\TranslateDirective\Environment;
use PoP\ComponentModel\Container\ContainerBuilderUtils;
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
            ContainerBuilderUtils::instantiateService(MaybeDisableDirectivesIfLoggedInUserDoesNotHaveRoleHookSet::class);
        }
    }
}
