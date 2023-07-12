<?php

declare(strict_types=1);

namespace Creatuity\AIContent\Model\Attribute;

use Magento\Eav\Model\Entity\Attribute\AbstractAttribute;
use Magento\Framework\Exception\LocalizedException;
use Psr\Log\LoggerInterface;

class GetMultiselectAttributeValuesByOption
{
    public function __construct(
        private readonly LoggerInterface $logger
    ) {
    }

    public function execute(AbstractAttribute $attribute, string $option): string
    {
        $values = [];
        foreach (explode(',', $option) as $value) {
            if ($value === '') {
                continue;
            }
            try {
                $value = (string)$attribute->getSource()->getOptionText($value);

                if (!$value) {
                    continue;
                }

                $values[] = $value;
            } catch (LocalizedException $e) {
                $this->logger->warning('Attribute multiselect option text cannot be found', ['exception' => $e]);
            }
        }

        return implode(', ', $values);
    }
}