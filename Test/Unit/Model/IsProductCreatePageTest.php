<?php

declare(strict_types=1);

namespace Creatuity\AIContent\Test\Unit\Model;

use Creatuity\AIContent\Model\IsProductCreatePage;
use Magento\Framework\App\RequestInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class IsProductCreatePageTest extends TestCase
{
    private readonly RequestInterface|MockObject $request;

    protected function setUp(): void
    {
        $this->request = $this->getMockBuilder(RequestInterface::class)
            ->disableOriginalConstructor()
            ->addMethods(['getFullActionName'])
            ->getMockForAbstractClass();
    }

    /**
     * @dataProvider dataProvider
     */
    public function testExecute(string $actionName, bool $expected): void
    {
        $this->request->expects($this->once())->method('getFullActionName')->willReturn($actionName);
        $this->assertSame($expected, (new IsProductCreatePage($this->request))->execute());
    }

    private function dataProvider(): array
    {
        return [
            ['catalog_product_new', true],
            ['some_action', false]
        ];
    }
}