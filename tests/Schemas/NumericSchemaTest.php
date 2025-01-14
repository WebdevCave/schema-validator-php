<?php

namespace Webdevcave\SchemaValidator\Tests\Schemas;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use stdClass;
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

        $this->assertTrue($schema->validate(10), 'Failed asserting equals than minimum value');
        $this->assertTrue($schema->validate(11), 'Failed asserting number greater than minimum value');
        $this->assertFalse($schema->validate(9), 'Failed asserting number lower than minimum value');
    }

    public function testMaxValue(): void
    {
        $schema = new NumericSchema();
        $schema->max(10);

        $this->assertTrue($schema->validate(10), 'Failed asserting equals than maximum value');
        $this->assertFalse($schema->validate(11), 'Failed asserting number greater than maximum value');
        $this->assertTrue($schema->validate(9), 'Failed asserting number lower than maximum value');
    }

    public function testRequirePositive(): void
    {
        $schema = new NumericSchema();
        $schema->positive();

        $this->assertTrue($schema->validate(1), 'Failed asserting positive value');
        $this->assertFalse($schema->validate(-1), 'Failed asserting negative value (positive required)');
    }

    public function testTypeValidation(): void
    {
        $schema = new NumericSchema();

        $this->assertFalse($schema->validate('str'), 'Numeric schema should not validate strings');
        $this->assertFalse($schema->validate(new stdClass()), 'Numeric schema should not validate objects');
        $this->assertFalse($schema->validate([1, 2, 3]), 'Numeric schema should validate arrays');

        $this->assertTrue($schema->validate(1), 'Numeric schema should validate integers');
        $this->assertTrue($schema->validate(1.1), 'Numeric schema should validate floats');
    }

    public function testExclusiveIntegerValidation(): void
    {
        $schema = new NumericSchema();
        $schema->integer();

        $this->assertTrue($schema->validate(1), 'Numeric schema should validate integers (int only)');
        $this->assertFalse($schema->validate(1.1), 'Numeric schema should not validate floats (int only)');
    }

    public function testExclusiveFloatValidation(): void
    {
        $schema = new NumericSchema();
        $schema->float();

        $this->assertFalse($schema->validate(1), 'Numeric schema should not validate integers (floats only)');
        $this->assertTrue($schema->validate(1.1), 'Numeric schema should validate floats (floats only)');
    }
}
