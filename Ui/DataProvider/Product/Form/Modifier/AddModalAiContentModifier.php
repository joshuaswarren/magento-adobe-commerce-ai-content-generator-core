<?php

declare(strict_types=1);

namespace Creatuity\AIContent\Ui\DataProvider\Product\Form\Modifier;

use Creatuity\AIContent\Model\Config\AiContentGeneralConfig;
use Magento\Backend\Model\UrlInterface;
use Magento\Catalog\Ui\DataProvider\Product\Form\Modifier\AbstractModifier;

class AddModalAiContentModifier extends AbstractModifier
{

    public function __construct(
        private readonly UrlInterface $url,
        private readonly AiContentGeneralConfig $aiContentGeneralConfig
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

        $formTarget = 'creatuityaicontent_generate_form';
        $meta['creatuity-ai-modal'] = [
            'arguments' => [
                'data' => [
                    'config' => [
                        'componentType' => 'fieldset',
                        'label' => '',
                        'visible' => false,
                        'dataScope' => '',
                        'sortOrder' => 10,
                    ],
                ],
            ],
            'children' => [
                'modal' =>  [
                    'arguments' => [
                        'data' => [
                            'config' => [
                                'componentType' => 'modal',
                                'options' => [
                                    'title' => __('Generate Content With AI'),
                                    'buttons' => [
                                        [
                                            'text' => __('Close'),
                                            'actions' => [
                                                'closeModal'
                                            ]
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ],
                    'children' => [
                        $formTarget => [
                            'arguments' => [
                                'data' => [
                                    'config' => [
                                        'autoRender' => false,
                                        'loading' => false,
                                        'componentType' => 'insertForm',
                                        'dataScope' => '',
                                        'externalProvider' => $formTarget . '.' . $formTarget . '_data_source',
                                        'ns' => $formTarget,
                                        'component' => 'Magento_Ui/js/form/components/insert-form',
                                        'update_url' => $this->url->getUrl('mui/index/render'),
                                        'render_url' => $this->url->getUrl(
                                            'mui/index/render_handle',
                                            [
                                                'handle' => $formTarget,
                                                'buttons' => 1
                                            ]
                                        )
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ];

        return $meta;
    }
}