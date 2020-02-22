<?php
namespace PoP\TranslateDirective\Conditional\UserState;

use PoP\TranslateDirective\Environment;
use PoP\API\Environment as APIEnvironment;
use PoP\ComponentModel\Container\ContainerBuilderUtils;
use PoP\ComponentModel\AttachableExtensions\AttachableExtensionGroups;
use PoP\TranslateDirective\Conditional\UserState\TypeResolverDecorators\GlobalTypeResolverDecorator;
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
    }

    /**
     * Attach directive resolvers based on environment variables
     *
     * @return void
     */
    protected static function attachDynamicHooks()
    {
        if (Environment::userMustBeLoggedInToTranslate()) {
            if (APIEnvironment::usePrivateSchemaMode()) {
                ContainerBuilderUtils::instantiateService(MaybeDisableDirectivesIfUserNotLoggedInHookSet::class);
            } else {
                GlobalTypeResolverDecorator::attach(AttachableExtensionGroups::TYPERESOLVERDECORATORS);
            }
        }
    }
}
