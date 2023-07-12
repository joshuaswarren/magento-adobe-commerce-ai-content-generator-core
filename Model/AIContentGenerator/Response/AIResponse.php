<?php

declare(strict_types=1);

namespace Creatuity\AIContent\Model\AIContentGenerator\Response;

use Creatuity\AIContent\Api\Data\AIResponseInterface;
use Magento\Framework\DataObject;

class AIResponse extends DataObject implements AIResponseInterface
{
    public function getContent(): string
    {
        return (string) $this->getData(self::CONTENT_FIELD);
    }

    public function setContent(string $content): void
    {
        $this->setData(self::CONTENT_FIELD, $content);
    }
}