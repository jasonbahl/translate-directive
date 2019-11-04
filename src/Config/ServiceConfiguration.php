<?php
namespace PoP\TranslateDirective\Config;

use PoP\TranslateDirective\Environment;
use PoP\Root\Component\PHPServiceConfigurationTrait;
use PoP\ComponentModel\Container\ContainerBuilderUtils;

class ServiceConfiguration
{
    use PHPServiceConfigurationTrait;

    protected static function configure()
    {
        // If there is a default translation provider, inject it into the service
        if ($defaultTranslationProvider = Environment::getDefaultTranslationProvider()) {
            ContainerBuilderUtils::injectValuesIntoService(
                'translation_service',
                'setDefaultProvider',
                $defaultTranslationProvider
            );
        }
    }
}