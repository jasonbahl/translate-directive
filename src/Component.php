<?php

declare(strict_types=1);

namespace PoP\TranslateDirective;

use PoP\TranslateDirective\Config\ServiceConfiguration;
use PoP\Root\Component\AbstractComponent;
use PoP\Root\Component\YAMLServicesTrait;

/**
 * Initialize component
 */
class Component extends AbstractComponent
{
    public static $COMPONENT_DIR;
    use YAMLServicesTrait;
    // const VERSION = '0.1.0';

    /**
     * Initialize services
     */
    public static function init()
    {
        parent::init();
        self::$COMPONENT_DIR = dirname(__DIR__);
        self::initYAMLServices(self::$COMPONENT_DIR);
        ServiceConfiguration::init();
    }
}
