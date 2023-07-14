<?php

declare(strict_types=1);

namespace Creatuity\AIContent\Ui\DataProvider\Product\Form\Modifier;

use Creatuity\AIContent\Enum\AiContentTypeEnum;
use Creatuity\AIContent\Model\Config\AiContentGeneralConfig;
use Creatuity\AIContent\Model\IsProductCreatePage;
use Magento\Catalog\Ui\DataProvider\Product\Form\Modifier\AbstractModifier;

class AddGenerateMetaTagsWithAIButtonModifier extends AbstractModifier
{

    public function __construct(
        private readonly AiContentGeneralConfig $aiContentGeneralConfig,
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
        $msg = __('You can use AI to generate META DATA automatically based on product name and description.');
        $section = $this->getSectionConfiguration->execute((string) $msg, AiContentTypeEnum::META_GROUP->value);
        $meta['search-engine-optimization']['children'] += $section;
        $meta['search-engine-optimization']['children']['container_url_key']['arguments']['data']['config']['sortOrder'] = 10;
        $meta['search-engine-optimization']['children']['button_set']['arguments']['data']['config']['sortOrder'] = 20;
        $meta['search-engine-optimization']['children']['container_meta_title']['arguments']['data']['config']['sortOrder'] = 30;
        $meta['search-engine-optimization']['children']['container_meta_keyword']['arguments']['data']['config']['sortOrder'] = 40;
        $meta['search-engine-optimization']['children']['container_meta_description']['arguments']['data']['config']['sortOrder'] = 50;

        return $meta;
    }
}