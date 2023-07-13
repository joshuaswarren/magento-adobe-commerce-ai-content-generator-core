<?php

declare(strict_types=1);

namespace Creatuity\AIContent\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;

class AIProviderOptionSource implements OptionSourceInterface
{
    public function __construct(
        private readonly array $providers = []
    ) {
    }

    public function toOptionArray(): array
    {
        return $this->providers;
    }
}