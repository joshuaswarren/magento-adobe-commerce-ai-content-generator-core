<?php

declare(strict_types=1);

namespace Creatuity\AIContent\Ui\Component\Form;

use Creatuity\AIContent\Model\DataProvider\ContentType\AIFormContentTypeProviderInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;

class Fieldset extends \Magento\Ui\Component\Form\Fieldset
{
    public function __construct(
        ContextInterface $context,
        private readonly AIFormContentTypeProviderInterface $contentTypeProvider,
        array $components = [],
        array $data = []
    ) {
        parent::__construct($context, $components, $data);
    }

    public function getConfiguration(): array
    {
        $config = parent::getConfiguration();
        $config['visible'] = $this->contentTypeProvider->get() === $this->getName();
        $config['opened'] = $config['visible'];

        return $config;
    }
}