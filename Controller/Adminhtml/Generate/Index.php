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
use Magento\Framework\Exception\LocalizedException;
use Psr\Log\LoggerInterface;

class Index extends Action implements HttpPostActionInterface
{
    public function __construct(
        Context $context,
        private readonly JsonFactory $jsonResultFactory,
        private readonly RequestProcessor $requestProcessor,
        private readonly RequestInterface $request,
        private readonly LoggerInterface $logger
    ) {
        parent::__construct($context);
    }

    public function execute(): ResultInterface
    {
        $jsonResult = $this->jsonResultFactory->create();

        try {
            $jsonResult->setData([
                'success' => true,
                'choices' => $this->requestProcessor
                    ->execute($this->request->getParam('specification', []))
                    ->getChoices()
            ]);
        } catch (LocalizedException $e) {
            return $this->returnError((string) $e->getMessage());
        } catch (\Throwable $t) {
            $this->logger->error('AI content generating error', ['exception' => $t]);

            return $this->returnError((string) __('Unknown error occurred. Try again later.'));
        }

        return $jsonResult;
    }

    private function returnError(string $msg): ResultInterface
    {
        $jsonResult = $this->jsonResultFactory->create();
        $jsonResult->setData([
            'success' => false,
            'message' => $msg
        ]);
        $jsonResult->setHttpResponseCode(500);

        return $jsonResult;
    }
}