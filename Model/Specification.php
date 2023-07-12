<?php

declare(strict_types=1);

namespace Creatuity\AIContent\Model;

use Creatuity\AIContent\Api\Data\SpecificationInterface;
use Magento\Framework\DataObject;

class Specification extends DataObject implements SpecificationInterface
{

    public function getContentType(): string
    {
        return (string) $this->getData(self::CONTENT_TYPE);
    }

    public function setContentType(string $type): void
    {
        $this->setData(self::CONTENT_TYPE, $type);
    }

    public function getProductId(): int
    {
        return (int) $this->getData(self::PRODUCT_ID);
    }

    public function setProductId(int $id): void
    {
        $this->setData(self::PRODUCT_ID, $id);
    }

    public function getProductAttributes(): array
    {
        return $this->getData(self::PRODUCT_ATTRIBUTES) ?: [];
    }

    public function setProductAttributes(array $attrs): void
    {
        $this->setData(self::PRODUCT_ATTRIBUTES, $attrs);
    }

    public function getMinLength(): ?int
    {
        if (null === $this->getData(self::MIN_LENGTH)) {
            return null;
        }

        return (int) $this->getData(self::MIN_LENGTH);
    }

    public function setMinLength(int $length): void
    {
        $this->setData(self::MIN_LENGTH, $length);
    }

    public function getMaxLength(): ?int
    {
        if (null === $this->getData(self::MAX_LENGTH)) {
            return null;
        }

        return (int) $this->getData(self::MAX_LENGTH);
    }

    public function setMaxLength(int $length): void
    {
        $this->setData(self::MAX_LENGTH, $length);
    }
}