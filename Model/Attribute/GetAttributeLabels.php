<?php

declare(strict_types=1);

namespace Creatuity\AIContent\Model\Attribute;

use Magento\Catalog\Model\ResourceModel\Product;
use Psr\Log\LoggerInterface;

class GetAttributeLabels
{
    public function __construct(
        private readonly Product $productResource,
        private readonly LoggerInterface $logger
    ) {
    }

    /**
     * @param string[] $attrCodes
     * @return string[]
     */
    public function execute(array $attrCodes): array
    {
        $data = [];
        foreach ($attrCodes as $attr) {
            try {
                //@phpstan-ignore-next-line
                $label = $this->productResource->getAttribute($attr)->getFrontend()->getLabel();
                $data[$attr] = $label;
            } catch (\Throwable $e) {
                $this->logger->error('Failed to find attribute ' . $attr, ['exception' => $e]);
            }
        }

        return $data;
    }
}