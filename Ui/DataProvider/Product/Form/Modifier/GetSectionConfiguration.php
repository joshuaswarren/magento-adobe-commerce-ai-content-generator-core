<?php

declare(strict_types=1);

namespace Creatuity\AIContent\Ui\DataProvider\Product\Form\Modifier;

use Creatuity\AIContent\Model\IsProductCreatePage;

class GetSectionConfiguration
{
    private const DEFAULT_FORM_TARGET = 'creatuityaicontent_generate_form';

    public function __construct(
        private readonly IsProductCreatePage $isProductCreatePage,
        private readonly GetModalActions $getModalActions
    )
    {
    }

    public function execute(
        string $sectionMsg,
        string $aiContentGroup,
        string $formTarget = self::DEFAULT_FORM_TARGET
    ): array {
        return [
            'button_set' => [
                'arguments' => [
                    'data' => [
                        'config' => [
                            'additionalClasses' => 'admin__fieldset-section creatuity-ai-section',
                            'label' => false,
                            'collapsible' => false,
                            'componentType' => 'fieldset',
                            'sortOrder' => 10
                        ]
                    ]
                ],
                'children' => [
                    'ai_buttons' => [
                        'arguments' => [
                            'data' => [
                                'config' => [
                                    'formElement' => 'container',
                                    'componentType' => 'container',
                                    'label' => false,
                                    'isCreateProductPage' => $this->isProductCreatePage->execute(),
                                    'content' => $sectionMsg,
                                    'template' => 'Creatuity_AIContent/form/component/ai-section'
                                ]
                            ]
                        ],
                        'children' => [
                            'button_ai_description' => [
                                'arguments' => [
                                    'data' => [
                                        'config' => [
                                            'formElement' => 'container',
                                            'componentType' => 'container',
                                            'component' => 'Magento_Ui/js/form/components/button',
                                            'title' => __('Generate With AI'),
                                            'provider' => null,
                                            'disabled' => $this->isProductCreatePage->execute(),
                                            'additionalForGroup' => true,
                                            'additionalClasses' => 'admin__field-medium',
                                            'actions' => $this->getModalActions->execute(
                                                $formTarget,
                                                $aiContentGroup
                                            )
                                        ]
                                    ]
                                ],
                            ]
                        ]
                    ]
                ]
            ],
        ];
    }
}