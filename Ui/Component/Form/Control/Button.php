<?php

declare(strict_types=1);

namespace Creatuity\AIContent\Ui\Component\Form\Control;

use Magento\Backend\Model\UrlInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Ui\Component\Container;

class Button extends Container
{
    public function __construct(
        private readonly UrlInterface $urlBuilder,
        ContextInterface $context,
        array $components = [],
        array $data = []
    ) {
        parent::__construct($context, $components, $data);
    }

    public function getConfiguration(): array
    {
        $config = parent::getConfiguration();
        $config['actionUrl'] = $this->urlBuilder->getUrl(
            'creatuityAiContent/generate/index',
            ['ai_content_type' => $config['aiContentType'] ?? '']
        );

        return $config;
    }
}