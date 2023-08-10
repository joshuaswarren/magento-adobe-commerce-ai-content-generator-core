<?php

declare(strict_types=1);

namespace Creatuity\AIContent\Model\AIContentGenerator\Response;

use Creatuity\AIContent\Api\Data\AIResponseInterface;
use Magento\Framework\DataObject;

class AIResponse extends DataObject implements AIResponseInterface
{
    public function getChoices(): array
    {
        return (array) $this->getData(self::CHOICES_FIELD);
    }

    public function setChoices(array $choices): void
    {
        $this->setData(self::CHOICES_FIELD, $choices);
    }
}