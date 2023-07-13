<?php

declare(strict_types=1);

namespace Creatuity\AIContent\Test\Unit\Model\Attribute;

use Creatuity\AIContent\Model\Attribute\GetMultiselectAttributeValuesByOption;
use Magento\Eav\Model\Entity\Attribute\AbstractAttribute;
use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;
use Magento\Framework\Exception\LocalizedException;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class GetMultiselectAttributeValuesByOptionTest extends TestCase
{
    private readonly LoggerInterface $logger;

    protected function setUp(): void
    {
        $this->logger = $this->createMock(LoggerInterface::class);
    }

    public function testExecute(): void
    {
        $attribute = $this->createMock(AbstractAttribute::class);
        $option = '1,2,3';
        $attrSource = $this->createMock(AbstractSource::class);
        $attrSource
            ->expects($this->exactly(3))
            ->method('getOptionText')
            ->willReturnCallback(fn($optionId) => match ($optionId) {
                '1' => 'red',
                '2' => 'blue',
                '3' => 'orange'
            });
        $attribute->expects($this->exactly(3))->method('getSource')->willReturn($attrSource);
        $expected = 'red, blue, orange';
        $object = new GetMultiselectAttributeValuesByOption($this->logger);
        $result = $object->execute($attribute, $option);
        $this->assertSame($expected, $result);
    }

    public function testExecuteWithException(): void
    {
        $attribute = $this->createMock(AbstractAttribute::class);
        $option = '1,2,3';
        $exception = new LocalizedException(__('Error'));
        $attrSource = $this->createMock(AbstractSource::class);
        $attrSource
            ->expects($this->exactly(3))
            ->method('getOptionText')
            ->willReturnCallback(fn($optionId) => match ($optionId) {
                '1' => 'red',
                '2' => throw $exception,
                '3' => 'orange'
            });
        $attribute->expects($this->exactly(3))->method('getSource')->willReturn($attrSource);
        $expected = 'red, orange';
        $this->logger
            ->expects($this->once())
            ->method('warning')
            ->with('Attribute multiselect option text cannot be found', ['exception' => $exception]);
        $object = new GetMultiselectAttributeValuesByOption($this->logger);
        $result = $object->execute($attribute, $option);
        $this->assertSame($expected, $result);
    }
}