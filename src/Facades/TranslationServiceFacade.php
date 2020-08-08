<?php

declare(strict_types=1);

namespace PoPSchema\TranslateDirective\Facades;

use PoPSchema\TranslateDirective\Translation\TranslationServiceInterface;
use PoP\Root\Container\ContainerBuilderFactory;

class TranslationServiceFacade
{
    public static function getInstance(): TranslationServiceInterface
    {
        return ContainerBuilderFactory::getInstance()->get('translation_service');
    }
}
