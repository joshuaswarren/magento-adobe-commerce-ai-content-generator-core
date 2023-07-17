<?php

declare(strict_types=1);

namespace Creatuity\AIContent\Model\Attribute;

use Magento\Catalog\Model\Product;
use Magento\Eav\Model\Config;
use Magento\Framework\Exception\LocalizedException;
use Psr\Log\LoggerInterface;

class GetAttributeOptionValue
{
    public function __construct(
        private readonly Config $eavConfig,
        private readonly LoggerInterface $logger,
        private readonly GetMultiselectAttributeValuesByOption $getMultiselectAttributeValuesByOption
    ) {
    }

    public function execute(string $code, int|string $option): string
    {
        try {
            $attribute = $this->eavConfig->getAttribute(Product::ENTITY, $code);

            return match($attribute->getFrontendInput()) {
                'select' => (string) $attribute->getSource()->getOptionText($option),
                'multiselect' => $this->getMultiselectAttributeValuesByOption->execute($attribute, (string) $option),
                'boolean' => $option ? (string)__('Yes') : (string)__('No'),
                default => (string) $option
            };
        } catch (LocalizedException $e) {
            $this->logger->warning('Attribute option text cannot be found', ['exception' => $e]);

            return '';
        }
    }
}