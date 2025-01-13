<?php

namespace Webdevcave\SchemaValidator\Tests\Schemas;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Webdevcave\SchemaValidator\Schemas\BaseSchema;
use Webdevcave\SchemaValidator\Schemas\NumericSchema;
use Webdevcave\SchemaValidator\Validator;

#[CoversClass(Validator::class)]
#[CoversClass(BaseSchema::class)]
#[CoversClass(NumericSchema::class)]
class NumericSchemaTest extends TestCase
{
    public function testMinValue(): void
    {
        $schema = new NumericSchema();
        $schema->min(10);

        $this->assertTrue($schema->validate(10), "Failed asserting equals than minimum value");
        $this->assertTrue($schema->validate(11), "Failed asserting number greater than minimum value");
        $this->assertFalse($schema->validate(9), "Failed asserting number lower than minimum value");
    }

    public function testMaxValue(): void
    {
        $schema = new NumericSchema();
        $schema->max(10);

        $this->assertTrue($schema->validate(10), "Failed asserting equals than maximum value");
        $this->assertFalse($schema->validate(11), "Failed asserting number greater than maximum value");
        $this->assertTrue($schema->validate(9), "Failed asserting number lower than maximum value");
    }

    public function testRequirePositive(): void
    {
        $schema = new NumericSchema();
        $schema->positive();

        $this->assertTrue($schema->validate(1), "Failed asserting positive value");
        $this->assertFalse($schema->validate(-1), "Failed asserting negative value (positive required)");
    }
}
