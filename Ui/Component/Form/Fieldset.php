<?php

declare(strict_types=1);

namespace Creatuity\AIContent\Ui\Component\Form;

use Magento\Framework\App\RequestInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;

class Fieldset extends \Magento\Ui\Component\Form\Fieldset
{
    public function __construct(
        private readonly RequestInterface $request,
        ContextInterface $context,
        array $components = [],
        array $data = []
    ) {
        parent::__construct($context, $components, $data);
    }

    public function getConfiguration(): array
    {
        $config = parent::getConfiguration();
        $config['visible'] = $this->request->getQuery('ai_content_group') === $this->getName();
        $config['opened'] = $config['visible'];

        return $config;
    }
}