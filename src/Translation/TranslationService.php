<?php

declare(strict_types=1);

namespace PoP\TranslateDirective\Translation;

class TranslationService implements TranslationServiceInterface
{
    protected $defaultProvider;

    public function getDefaultProvider(): ?string
    {
        return $this->defaultProvider;
    }
    public function setDefaultProvider(string $provider): void
    {
        $this->defaultProvider = $provider;
    }
}
