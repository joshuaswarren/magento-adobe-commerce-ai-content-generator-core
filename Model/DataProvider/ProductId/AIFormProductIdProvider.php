<?php

declare(strict_types=1);

namespace Creatuity\AIContent\Model\DataProvider\ProductId;

class AIFormProductIdProvider implements AIFormProductIdProviderInterface
{
    /**
     * @param AIFormProductIdProviderInterface[] $providers
     */
    public function __construct(
        private readonly array $providers = []
    ) {
    }

    public function get(): ?int
    {
        foreach ($this->providers as $provider) {
            if (!$provider instanceof AIFormProductIdProviderInterface) {
                throw new \InvalidArgumentException(get_class($provider) . ' doesn\'t implement ' . AIFormProductIdProviderInterface::class);
            }

            if ($productId = $provider->get()) {
                return $productId;
            }
        }

        return null;
    }
}