<?php

declare(strict_types=1);

namespace Creatuity\AIContent\Ui\DataProvider\Product\Form\Modifier;

use Creatuity\AIContent\Enum\AiContentTypeEnum;
use Creatuity\AIContent\Model\Config\AiContentGeneralConfig;
use Creatuity\AIContent\Model\IsProductCreatePage;

class GetModalActions
{
    public function __construct(
        private readonly AiContentGeneralConfig $aiContentGeneralConfig,
        private readonly IsProductCreatePage $isProductCreatePage
    )
    {
    }

    public function execute(string $target, string $aiContentGroup): array
    {
        if ($this->isProductCreatePage->execute() || !$this->aiContentGeneralConfig->isEnabled()) {
            return [];
        }

        return [
            [
                'targetName' => 'product_form.product_form.creatuity-ai-modal.modal',
                'actionName' => 'toggleModal',
                'params' =>  [
                    [
                        'ai_content_group' => $aiContentGroup
                    ]
                ]
            ],
            [
                'targetName' => 'product_form.product_form.creatuity-ai-modal.modal.' . $target,
                'actionName' => 'destroyInserted'
            ],
            [
                'targetName' => 'product_form.product_form.creatuity-ai-modal.modal.' . $target,
                'actionName' => 'render',
                'params' =>  [
                    [
                        'ai_content_group' => $aiContentGroup
                    ]
                ]
            ],
            [
                'targetName' => 'product_form.product_form.creatuity-ai-modal.modal.' . $target,
                'actionName' => 'resetForm',
                'params' =>  [
                    [
                        'ai_content_group' => $aiContentGroup
                    ]
                ]
            ]
        ];
    }
}