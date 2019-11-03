<?php
namespace PoP\TranslateDirective;

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
