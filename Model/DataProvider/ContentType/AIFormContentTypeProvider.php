<?php

declare(strict_types=1);

namespace Creatuity\AIContent\Model\DataProvider\ContentType;

class AIFormContentTypeProvider implements AIFormContentTypeProviderInterface
{
    /**
     * @param AIFormContentTypeProviderInterface[] $providers
     */
    public function __construct(
        private readonly array $providers = []
    ) {
    }

    public function get(): ?string
    {
        foreach ($this->providers as $provider) {
            if (!$provider instanceof AIFormContentTypeProviderInterface) {
                throw new \InvalidArgumentException(get_class($provider) . ' doesn\'t implement ' . AIFormContentTypeProviderInterface::class);
            }

            if ($contentType = $provider->get()) {
                return $contentType;
            }
        }

        return null;
    }
}