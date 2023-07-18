<?php

declare(strict_types=1);

namespace Creatuity\AIContent\Api\Data;

interface SpecificationInterface
{
    public const DESCRIPTION_DEFAULT_MAX_LENGTH = 9999;
    public const SHORT_DESCRIPTION_DEFAULT_MAX_LENGTH = 1000;

    public const CONTENT_TYPE = 'content_type';
    public const PRODUCT_ID = 'product_id';
    public const PRODUCT_ATTRIBUTES = 'product_attributes';
    public const MIN_LENGTH = 'min_length';
    public const MAX_LENGTH = 'max_length';
    public const STORE_ID = 'store_id';
    public const NUMBER = 'number';

    /**
     * @return string
     */
    public function getContentType(): string;

    /**
     * @param string $type
     * @return void
     */
    public function setContentType(string $type): void;

    /**
     * @return int
     */
    public function getProductId(): int;

    /**
     * @param int $id
     * @return void
     */
    public function setProductId(int $id): void;

    /**
     * @return string[]
     */
    public function getProductAttributes(): array;

    /**
     * @param string[] $attrs
     * @return void
     */
    public function setProductAttributes(array $attrs): void;

    /**
     * @return int|null
     */
    public function getMinLength(): ?int;

    /**
     * @param int $length
     * @return void
     */
    public function setMinLength(int $length): void;

    /**
     * @return int|null
     */
    public function getMaxLength(): ?int;

    /**
     * @param int $length
     * @return void
     */
    public function setMaxLength(int $length): void;

    /**
     * @return int
     */
    public function getStoreId(): int;

    /**
     * @param int $id
     * @return void
     */
    public function setStoreId(int $id): void;

    /**
     * @return int
     */
    public function getNumber(): int;

    /**
     * @param int $number
     * @return void
     */
    public function setNumber(int $number): void;
}