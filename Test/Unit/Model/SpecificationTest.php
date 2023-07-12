<?php

declare(strict_types=1);

namespace Creatuity\AIContent\Test\Unit\Model;

use Creatuity\AIContent\Model\Specification;
use PHPUnit\Framework\TestCase;

class SpecificationTest extends TestCase
{
    public function testGetMinLength(): void
    {
        $specification = new Specification(['min_length' => null]);
        $this->assertNull($specification->getMinLength());

        $specification = new Specification(['min_length' => '0']);
        $this->assertSame(0, $specification->getMinLength());

        $specification = new Specification(['min_length' => 10]);
        $this->assertSame(10, $specification->getMinLength());
    }

    public function testGetMaxLength(): void
    {
        $specification = new Specification(['max_length' => null]);
        $this->assertNull($specification->getMaxLength());

        $specification = new Specification(['max_length' => '0']);
        $this->assertSame(0, $specification->getMaxLength());

        $specification = new Specification(['max_length' => 10]);
        $this->assertSame(10, $specification->getMaxLength());
    }
}