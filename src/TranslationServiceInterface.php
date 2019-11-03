<?php
namespace PoP\TranslateDirective;

interface TranslationServiceInterface
{
    public function getDefaultProvider(): ?string;
    public function setDefaultProvider(string $provider): void;
}
