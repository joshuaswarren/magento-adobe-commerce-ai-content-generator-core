<?php

declare(strict_types=1);

namespace Creatuity\AIContent\Model\Config;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

class AiContentGeneralConfig
{
    private const XML_PATH_AICONTENT_ENABLED = 'creatuityaicontent/general/enabled';
    private const XML_PATH_DESC_ATTRS = 'creatuityaicontent/general/product_description_attributes';
    private const XML_PATH_METATAGS_ATTRS = 'creatuityaicontent/general/product_meta_tags_attributes';
    private const XML_PATH_AI_PROVIDER = 'creatuityaicontent/general/aiprovider';

    public function __construct(
        private readonly ScopeConfigInterface $scopeConfig
    ) {
    }

    public function isEnabled(?int $storeId = null): bool
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_AICONTENT_ENABLED,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }


    public function getAIProvider(?int $storeId = null): string
    {
        return (string) $this->scopeConfig->getValue(
            self::XML_PATH_AI_PROVIDER,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * @return string[]
     */
    public function getDescriptionAttributes(?int $storeId = null): array
    {
        $val = (string) $this->scopeConfig->getValue(
            self::XML_PATH_DESC_ATTRS,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );

        $attrs = explode(',', $val);

        return array_map('trim', $attrs);
    }

    public function getMetaAttributes(?int $storeId = null): array
    {
        $val = (string) $this->scopeConfig->getValue(
            self::XML_PATH_METATAGS_ATTRS,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );

        $attrs = explode(',', $val);

        return array_map('trim', $attrs);
    }
}