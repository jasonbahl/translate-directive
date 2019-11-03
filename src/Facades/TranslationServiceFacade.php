<?php
namespace PoP\TranslateDirective\Facades;

use PoP\TranslateDirective\TranslationServiceInterface;
use PoP\Root\Container\ContainerBuilderFactory;

class TranslationServiceFacade
{
    public static function getInstance(): TranslationServiceInterface
    {
        return ContainerBuilderFactory::getInstance()->get('translation_service');
    }
}
