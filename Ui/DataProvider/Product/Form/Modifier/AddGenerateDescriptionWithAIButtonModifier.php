<?php

declare(strict_types=1);

namespace Creatuity\AIContent\Ui\DataProvider\Product\Form\Modifier;

use Creatuity\AIContent\Enum\AiContentTypeEnum;
use Creatuity\AIContent\Model\Config\AiContentGeneralConfig;
use Magento\Backend\Model\UrlInterface;
use Magento\Catalog\Ui\DataProvider\Product\Form\Modifier\AbstractModifier;

class AddGenerateDescriptionWithAIButtonModifier extends AbstractModifier
{
    public function __construct(
        private readonly AiContentGeneralConfig $aiContentGeneralConfig,
        private readonly UrlInterface $url,
        private readonly GetSectionConfiguration $getSectionConfiguration
    )
    {
    }

    public function modifyData(array $data): array
    {
        return $data;
    }

    public function modifyMeta(array $meta): array
    {
        if (!$this->aiContentGeneralConfig->isEnabled()) {
            return $meta;
        }

        $configUrl = $this->url->getUrl('adminhtml/system_config/edit', ['section' => 'creatuityaicontent']);
        $configLink = '<a href="' . $configUrl . '" target="_blank">' . __('configuration') . '</a>.';
        $msg = __('You can use AI to generate descriptions automatically based on product attributes selected in %1', $configLink);

        $section = $this->getSectionConfiguration->execute((string) $msg, AiContentTypeEnum::DESCRIPTION_GROUP->value);
        $meta['content']['children'] = $section + $meta['content']['children'];
        $meta['content']['children']['container_short_description']['arguments']['data']['config']['sortOrder'] = 20;
        $meta['content']['children']['container_description']['arguments']['data']['config']['sortOrder'] = 30;

        return $meta;
    }
}