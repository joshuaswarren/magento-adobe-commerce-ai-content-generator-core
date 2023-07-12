<?php

declare(strict_types=1);

namespace Creatuity\AIContent\Model\Attribute;

class GetAttributeOptionValueFormatted
{
    public function __construct(
        private readonly GetAttributeOptionValue $getAttributeOptionValue
    ) {
    }

    public function execute(string $attrCode, int|string $option): ?string
    {
        $val = $this->getAttributeOptionValue->execute($attrCode, $option);
        $val = str_replace("\n", " ", strip_tags($val));
        if (!$val) {
            return null;
        }

        return $val;
    }
}