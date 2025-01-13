<?php

namespace Webdevcave\SchemaValidator\Tests\Schemas;

use PHPUnit\Framework\Attributes\CoversClass;
use stdClass;
use Webdevcave\SchemaValidator\Schemas\ArraySchema;
use PHPUnit\Framework\TestCase;
use Webdevcave\SchemaValidator\Schemas\BaseSchema;
use Webdevcave\SchemaValidator\Validator;

#[CoversClass(Validator::class)]
#[CoversClass(BaseSchema::class)]
#[CoversClass(ArraySchema::class)]
class ArraySchemaTest extends TestCase
{
    public function testTypeCheck(): void
    {
        $schema = new ArraySchema();

        $this->assertFalse($schema->validate(1), "Array schema should not validate integers");
        $this->assertFalse($schema->validate(1.1), "Array schema should not validate floats");
        $this->assertFalse($schema->validate('str'), "Array schema should not validate strings");
        $this->assertFalse($schema->validate(new stdClass()), "Array schema should not validate objects");

        $this->assertTrue($schema->validate([1,2,3]), "Array schema should validate arrays");
    }
}
