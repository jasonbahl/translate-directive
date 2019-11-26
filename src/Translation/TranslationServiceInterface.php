<?php
namespace PoP\TranslateDirective\Translation;

interface TranslationServiceInterface
{
    public function getDefaultProvider(): ?string;
    public function setDefaultProvider(string $provider): void;
}
