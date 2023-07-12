<?php

declare(strict_types=1);

namespace Creatuity\AIContent\Test\Unit\Model\Attribute;

use Creatuity\AIContent\Model\Attribute\GetAttributeOptionValue;
use Creatuity\AIContent\Model\Attribute\GetMultiselectAttributeValuesByOption;
use Magento\Catalog\Model\Product;
use Magento\Eav\Model\Config;
use Magento\Eav\Model\Entity\Attribute\AbstractAttribute;
use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;
use Magento\Framework\Exception\LocalizedException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class GetAttributeOptionValueTest extends TestCase
{
    private readonly Config|MockObject $eavConfig;
    private readonly LoggerInterface|MockObject $logger;
    private readonly GetMultiselectAttributeValuesByOption|MockObject $getMultiselectAttributeValuesByOption;

    protected function setUp(): void
    {
        $this->eavConfig = $this->createMock(Config::class);
        $this->logger = $this->createMock(LoggerInterface::class);
        $this->getMultiselectAttributeValuesByOption = $this->createMock(GetMultiselectAttributeValuesByOption::class);
    }

    public function testExecuteSelectTypeAttribute(): void
    {
        $code = 'some_attr_code';
        $option = '123';
        $value = 'some val';
        $attr = $this->createMock(AbstractAttribute::class);
        $attrSource = $this->createMock(AbstractSource::class);
        $attrSource->expects($this->once())->method('getOptionText')->with($option)->willReturn($value);
        $attr->expects($this->once())->method('getFrontendInput')->willReturn('select');
        $attr->expects($this->once())->method('getSource')->willReturn($attrSource);
        $this->eavConfig
            ->expects($this->once())
            ->method('getAttribute')
            ->with(Product::ENTITY, $code)
            ->willReturn($attr);
        $this->logger->expects($this->never())->method('warning');
        $this->getMultiselectAttributeValuesByOption->expects($this->never())->method('execute');
        $this->assertSame($value, $this->getObject()->execute($code, $option));
    }

    public function testExecuteMultiselectTypeAttribute(): void
    {
        $code = 'some_attr_code';
        $option = '123';
        $value = 'some val';
        $attr = $this->createMock(AbstractAttribute::class);
        $attr->expects($this->once())->method('getFrontendInput')->willReturn('multiselect');
        $this->eavConfig
            ->expects($this->once())
            ->method('getAttribute')
            ->with(Product::ENTITY, $code)
            ->willReturn($attr);
        $this->logger->expects($this->never())->method('warning');
        $this->getMultiselectAttributeValuesByOption
            ->expects($this->once())
            ->method('execute')
            ->with($attr, $option)
            ->willReturn($value);
        $this->assertSame($value, $this->getObject()->execute($code, $option));
    }

    public function testExecuteDefaultTypeAttribute(): void
    {
        $code = 'some_attr_code';
        $option = '123';
        $attr = $this->createMock(AbstractAttribute::class);
        $attr->expects($this->once())->method('getFrontendInput')->willReturn('some wierd type');
        $this->eavConfig
            ->expects($this->once())
            ->method('getAttribute')
            ->with(Product::ENTITY, $code)
            ->willReturn($attr);
        $this->logger->expects($this->never())->method('warning');
        $this->getMultiselectAttributeValuesByOption->expects($this->never())->method('execute');
        $this->assertSame($option, $this->getObject()->execute($code, $option));
    }

    public function testExecuteWithException(): void
    {
        $code = 'some_attr_code';
        $exception = new LocalizedException(__('Error'));
        $this->eavConfig
            ->expects($this->once())
            ->method('getAttribute')
            ->with(Product::ENTITY, $code)
            ->willThrowException($exception);
        $this->logger
            ->expects($this->once())
            ->method('warning')
            ->with('Attribute option text cannot be found', ['exception' => $exception]);
        $this->assertSame('', $this->getObject()->execute($code, '123'));
    }

    private function getObject(): GetAttributeOptionValue
    {
        return new GetAttributeOptionValue(
            $this->eavConfig,
            $this->logger,
            $this->getMultiselectAttributeValuesByOption
        );
    }
}