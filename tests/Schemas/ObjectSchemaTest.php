<?php

namespace Webdevcave\SchemaValidator\Tests\Schemas;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use stdClass;
use Webdevcave\SchemaValidator\Schemas\ArraySchema;
use Webdevcave\SchemaValidator\Schemas\BaseSchema;
use Webdevcave\SchemaValidator\Schemas\ObjectSchema;
use Webdevcave\SchemaValidator\Validator;

#[CoversClass(ObjectSchema::class)]
#[CoversClass(ArraySchema::class)]
#[CoversClass(BaseSchema::class)]
#[CoversClass(Validator::class)]
class ObjectSchemaTest extends TestCase
{
    public function testTypeCheck(): void
    {
        $schema = Validator::object();

        $this->assertFalse($schema->validate(1), 'Array schema should not validate integers');
        $this->assertFalse($schema->validate(1.1), 'Array schema should not validate floats');
        $this->assertFalse($schema->validate('str'), 'Array schema should not validate strings');
        $this->assertFalse($schema->validate([1, 2, 3]), 'Array schema should not validate arrays');

        $this->assertTrue($schema->validate(new stdClass()), 'Array schema should validate object');
    }
}
