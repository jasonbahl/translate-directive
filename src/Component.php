<?php
namespace PoP\TranslateDirective;

use PoP\TranslateDirective\Config\ServiceConfiguration;
use PoP\Root\Component\AbstractComponent;
use PoP\Root\Component\YAMLServicesTrait;

/**
 * Initialize component
 */
class Component extends AbstractComponent
{
    use YAMLServicesTrait;
    // const VERSION = '0.1.0';

    /**
     * Initialize services
     */
    public static function init()
    {
        parent::init();
        self::initYAMLServices(dirname(__DIR__));
        ServiceConfiguration::init();
    }

    /**
     * Boot component
     *
     * @return void
     */
    public static function boot()
    {
        parent::boot();

        if (class_exists('\PoP\UserState\Component')) {
            \PoP\TranslateDirective\Conditional\UserState\ComponentBoot::boot();
        }
        if (class_exists('\PoP\UserRoles\Component')) {
            \PoP\TranslateDirective\Conditional\UserRoles\ComponentBoot::boot();
        }
    }
}
