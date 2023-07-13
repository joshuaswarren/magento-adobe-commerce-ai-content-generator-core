<?php

declare(strict_types=1);

namespace Creatuity\AIContent\Model;

use Creatuity\AIContent\Api\Data\SpecificationInterface;
use Creatuity\AIContent\Api\Data\SpecificationInterfaceFactory;
use Creatuity\AIContent\Model\Config\GetDescriptionAttributes;
use Magento\Framework\Api\DataObjectHelper;
use Psr\Log\InvalidArgumentException;

class SpecificationHydrator
{
    private const DEFAULT_MAX_LEN = 9999;
    private const DEFAULT_MIN_LEN = 0;

    public function __construct(
        private readonly DataObjectHelper $dataObjectHelper,
        private readonly SpecificationInterfaceFactory $specificationFactory,
        private readonly GetDescriptionAttributes $getDescriptionAttributes,
        private readonly array $settings = []
    )
    {
    }

    /**
     * @param mixed[] $data
     * @return SpecificationInterface
     */
    public function hydrate(array $data): SpecificationInterface
    {
        if (empty($data[SpecificationInterface::PRODUCT_ID]) || empty($data[SpecificationInterface::CONTENT_TYPE])) {
            throw new InvalidArgumentException(
                (string)__(
                    'Missing specification properties. Required properties: %1, %1',
                    SpecificationInterface::PRODUCT_ID,
                    SpecificationInterface::CONTENT_TYPE
                )
            );
        }

        $storeId = $data[SpecificationInterface::STORE_ID] ?? null;
        $attributes = $data[SpecificationInterface::PRODUCT_ATTRIBUTES] ?? [];
        $data[SpecificationInterface::PRODUCT_ATTRIBUTES] = $attributes ?: $this->getDescriptionAttributes->execute($storeId);

        $maxLen = $data[SpecificationInterface::MAX_LENGTH] ?? null;
        $maxLen = $maxLen ?: $this->getSettings($data[SpecificationInterface::CONTENT_TYPE], 'max_len') ?: self::DEFAULT_MAX_LEN;
        $data[SpecificationInterface::MAX_LENGTH] = $maxLen;

        $minLen = $data[SpecificationInterface::MIN_LENGTH] ?? null;
        $minLen = $minLen ?: $this->getSettings($data[SpecificationInterface::CONTENT_TYPE], 'min_len') ?: self::DEFAULT_MIN_LEN;
        $data[SpecificationInterface::MIN_LENGTH] = $minLen;

        $specification = $this->specificationFactory->create();
        $this->dataObjectHelper->populateWithArray($specification, $data, SpecificationInterface::class);

        return $specification;
    }

    private function getSettings(string $contentType, string $settingType): mixed
    {
        foreach ($this->settings as $setting) {
            if ($contentType === $setting['type'] && isset($setting[$settingType])) {
                return $setting[$settingType];
            }
        }

        return null;
    }
}