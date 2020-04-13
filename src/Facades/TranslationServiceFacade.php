<?php

declare(strict_types=1);

namespace PoP\TranslateDirective\Facades;

use PoP\TranslateDirective\Translation\TranslationServiceInterface;
use PoP\Root\Container\ContainerBuilderFactory;

class TranslationServiceFacade
{
    public static function getInstance(): TranslationServiceInterface
    {
        return ContainerBuilderFactory::getInstance()->get('translation_service');
    }
}
