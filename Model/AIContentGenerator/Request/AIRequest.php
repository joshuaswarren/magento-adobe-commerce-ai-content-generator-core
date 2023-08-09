<?php

declare(strict_types=1);

namespace Creatuity\AIContent\Model\AIContentGenerator\Request;

use Creatuity\AIContent\Api\Data\AIRequestInterface;
use Magento\Framework\DataObject;

class AIRequest extends DataObject implements AIRequestInterface
{

    public function setParam(string $param, mixed $value): void
    {
        $params = $this->getParams();
        $params[$param] = $value;

        $this->setData('params', $params);
    }

    public function getParam(string $param): mixed
    {
        return $this->getParams()[$param] ?? null;
    }

    public function getParams(): array
    {
        return $this->_data['params'] ?? [];
    }

    public function getPrompt(): array
    {
        return (array) $this->getData(self::PROMPT);
    }

    public function setPrompt(array $prompt): void
    {
        $this->setData(self::PROMPT, $prompt);
    }
}