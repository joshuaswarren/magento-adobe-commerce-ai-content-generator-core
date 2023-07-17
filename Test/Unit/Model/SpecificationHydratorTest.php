<?php

declare(strict_types=1);

namespace Creatuity\AIContent\Test\Unit\Model;

use Creatuity\AIContent\Api\Data\SpecificationInterface;
use Creatuity\AIContent\Api\Data\SpecificationInterfaceFactory;
use Creatuity\AIContent\Model\Config\GetDescriptionAttributes;
use Creatuity\AIContent\Model\SpecificationHydrator;
use Magento\Framework\Api\DataObjectHelper;
use PHPUnit\Framework\TestCase;
use Psr\Log\InvalidArgumentException;

class SpecificationHydratorTest extends TestCase
{
    private array $settings = [
        'description' => [
            'type' => 'description',
            'min_len' => 0,
            'max_len' => 9999
        ],
        'short_description' => [
            'type' => 'short_description',
            'min_len' => 0,
            'max_len' => 9999
        ],
        'meta-title' => [
            'type' => 'meta-title',
            'min_len' => 50,
            'max_len' => 60
        ],
        'meta-keywords' => [
            'type' => 'meta-keywords',
            'min_len' => 8,
            'max_len' => 10
        ],
        'meta-description' => [
            'type' => 'meta-description',
            'min_len' => 8,
            'max_len' => 150
        ]
    ];

    private readonly DataObjectHelper $dataObjectHelper;
    private readonly SpecificationInterfaceFactory $specificationFactory;
    private readonly GetDescriptionAttributes $getDescriptionAttributes;

    protected function setUp(): void
    {
        $this->dataObjectHelper = $this->createMock(DataObjectHelper::class);
        $this->specificationFactory = $this->getMockBuilder(SpecificationInterfaceFactory::class)
            ->disableOriginalConstructor()->setMethods(['create'])->getMock();
        $this->getDescriptionAttributes = $this->createMock(GetDescriptionAttributes::class);
    }

    /**
     * @dataProvider dataProvider
     */
    public function testHydrateDefaultData(string $contentType, array $attributes): void
    {
        $data = [
            SpecificationInterface::PRODUCT_ID => 1,
            SpecificationInterface::CONTENT_TYPE => $contentType,
            SpecificationInterface::PRODUCT_ATTRIBUTES => []
        ];
        $specification = $this->createMock(SpecificationInterface::class);
        $this->specificationFactory->expects($this->once())->method('create')->willReturn($specification);

        $this->getDescriptionAttributes
            ->expects($this->once())
            ->method('execute')
            ->with(null)
            ->willReturn($attributes);

        $expectedData = array_merge(
            $data,
            [
                SpecificationInterface::MIN_LENGTH => $this->settings[$contentType]['min_len'],
                SpecificationInterface::MAX_LENGTH => $this->settings[$contentType]['max_len'],
                SpecificationInterface::PRODUCT_ATTRIBUTES => $attributes
            ]
        );
        $this->dataObjectHelper
            ->expects($this->once())
            ->method('populateWithArray')
            ->with($specification, $expectedData, SpecificationInterface::class);

        $result = $this->getObject()->hydrate($data);
        $this->assertSame($specification, $result);
    }

    /**
     * @dataProvider dataProvider
     */
    public function testHydrateCustomData(string $contentType, array $attributes): void
    {
        $data = [
            SpecificationInterface::PRODUCT_ID => 1,
            SpecificationInterface::CONTENT_TYPE => $contentType,
            SpecificationInterface::PRODUCT_ATTRIBUTES => $attributes,
            SpecificationInterface::MIN_LENGTH => 10,
            SpecificationInterface::MAX_LENGTH => 100,
            SpecificationInterface::STORE_ID => 1
        ];
        $specification = $this->createMock(SpecificationInterface::class);
        $this->specificationFactory->expects($this->once())->method('create')->willReturn($specification);
        $this->getDescriptionAttributes->expects($this->never())->method('execute');

        $this->dataObjectHelper
            ->expects($this->once())
            ->method('populateWithArray')
            ->with($specification, $data, SpecificationInterface::class);

        $result = $this->getObject()->hydrate($data);
        $this->assertSame($specification, $result);
    }

    public function testHydrateExceptionMissingContentType(): void
    {
        $this->exceptionExpected([SpecificationInterface::PRODUCT_ID => 'description']);
    }

    public function testHydrateExceptionMissingProductId(): void
    {
        $this->exceptionExpected([SpecificationInterface::CONTENT_TYPE => 'description']);
    }

    private function exceptionExpected(array $data): void
    {
        $this->getDescriptionAttributes->expects($this->never())->method('execute');
        $this->specificationFactory->expects($this->never())->method('create');
        $this->dataObjectHelper->expects($this->never())->method('populateWithArray');
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(
            (string)__(
                'Missing specification properties. Required properties: %1, %1',
                SpecificationInterface::PRODUCT_ID,
                SpecificationInterface::CONTENT_TYPE
            )
        );
        $this->getObject()->hydrate($data);
    }

    private function getObject(): SpecificationHydrator
    {
        return new SpecificationHydrator(
            $this->dataObjectHelper,
            $this->specificationFactory,
            $this->getDescriptionAttributes,
            $this->settings
        );
    }

    private function dataProvider(): array
    {
        return [
            ['description', ['color', 'size', 'name']],
            ['short_description', ['color', 'size', 'name']],
            ['meta-title', ['name', 'description']],
            ['meta-keywords', ['name', 'description']],
            ['meta-description', ['name', 'description']]
        ];
    }
}