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

    public static function getDependedComponentClasses(): array
    {
        return [
            \PoP\ComponentModel\Component::class,
            \PoP\GuzzleHelpers\Component::class,
        ];
    }

    /**
     * Initialize services
     */
    protected static function doInitialize(bool $skipSchema = false): void
    {
        parent::doInitialize($skipSchema);
        self::$COMPONENT_DIR = dirname(__DIR__);
        self::initYAMLServices(self::$COMPONENT_DIR);
        ServiceConfiguration::initialize();
    }
}
