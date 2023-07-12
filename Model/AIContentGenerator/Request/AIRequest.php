<?php

declare(strict_types=1);

namespace Creatuity\AIContent\Model\AIContentGenerator\Request;

use Creatuity\AIContent\Api\Data\AIRequestInterface;
use Magento\Framework\DataObject;

class AIRequest extends DataObject implements AIRequestInterface
{

    public function getInput(): string
    {
        return (string) $this->getData(self::INPUT_FIELD);
    }

    public function setInput(string $input): void
    {
        $this->setData(self::INPUT_FIELD, $input);
    }
}