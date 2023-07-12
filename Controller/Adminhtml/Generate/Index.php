<?php

declare(strict_types=1);

namespace Creatuity\AIContent\Controller\Adminhtml\Generate;

use Creatuity\AIContent\Model\RequestProcessor;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Controller\ResultInterface;

class Index extends Action implements HttpPostActionInterface
{
    public function __construct(
        Context $context,
        private readonly JsonFactory $jsonResultFactory,
        private readonly RequestProcessor $requestProcessor,
        private readonly RequestInterface $request
    ) {
        parent::__construct($context);
    }

    public function execute(): ResultInterface
    {
        $jsonResult = $this->jsonResultFactory->create();

        $jsonResult->setData([
            'text' => $this->requestProcessor->execute($this->request->getParam('specification', []))
        ]);

        return $jsonResult;
    }
}