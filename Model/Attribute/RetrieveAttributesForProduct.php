<?php

declare(strict_types=1);

namespace Creatuity\AIContent\Model\Attribute;

use Creatuity\AIContent\Model\Config\GetAttributesLabels;
use Magento\Catalog\Model\Product;

class RetrieveAttributesForProduct
{
    public function __construct(
        private readonly GetAttributesLabels $getAttributes,
        private readonly GetAttributeOptionValueFormatted $getAttributeOptionValueFormatted
    ) {
    }

    /**
     * @return string[]
     */
    public function execute(Product $product, array $attrCodes = []): array
    {
        $attrs = $this->getAttributes->get($attrCodes, (int)$product->getStoreId());
        $descAttrs = [];
        foreach ($attrs as $attrCode => $label) {
            $val = (string)$product->getData($attrCode);
            if ($val === '') {
                continue;
            }

            $val = $this->getAttributeOptionValueFormatted->execute($attrCode, $val);
            if (!$val) {
                continue;
            }

            $descAttrs[$label] = $val;
        }

        return $descAttrs;
    }
}