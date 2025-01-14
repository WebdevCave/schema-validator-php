<?php

namespace Webdevcave\SchemaValidator\Tests\Schemas;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use stdClass;
use Webdevcave\SchemaValidator\Schemas\ArraySchema;
use Webdevcave\SchemaValidator\Schemas\BaseSchema;
use Webdevcave\SchemaValidator\Schemas\NumericSchema;
use Webdevcave\SchemaValidator\Schemas\StringSchema;
use Webdevcave\SchemaValidator\Validator;

#[CoversClass(Validator::class)]
#[CoversClass(BaseSchema::class)]
#[CoversClass(ArraySchema::class)]
#[CoversClass(NumericSchema::class)]
#[CoversClass(StringSchema::class)]
class ArraySchemaTest extends TestCase
{
    public function testTypeCheck(): void
    {
        $schema = Validator::array();

        $this->assertFalse($schema->validate(1), "Array schema should not validate integers");
        $this->assertFalse($schema->validate(1.1), "Array schema should not validate floats");
        $this->assertFalse($schema->validate('str'), "Array schema should not validate strings");
        $this->assertFalse($schema->validate(new stdClass()), "Array schema should not validate objects");

        $this->assertTrue($schema->validate([1, 2, 3]), "Array schema should validate arrays");
    }

    public function testDataValidation(): void
    {
        $positiveIntCheckMessage = 'must be positive';
        $schema = Validator::array(['name' => Validator::string()->min(3), 'age' => Validator::numeric()->integer()->positive($positiveIntCheckMessage),]);

        $validData = ['name' => 'Carlos', 'age' => 35,];
        $this->assertTrue($schema->validate($validData), "Should return true when dataset is valid");

        $invalidData = ['name' => 'John', 'age' => -10,];
        $this->assertFalse($schema->validate($invalidData), "Should return false when dataset is not valid");
        $this->assertEquals(['age' => [$positiveIntCheckMessage]], $schema->errorMessages(), 'Error messages doesn\'t match');
    }

    public function testWildcardIndexValidation()
    {
        $errorMessage = 'Only integers are allowed';
        $schema = Validator::array(['*' => Validator::numeric()->integer($errorMessage)]);

        $validData = [1, 2, 3];
        $this->assertTrue($schema->validate($validData), "Error evaluating wildcard index with valid data");

        $invalidData = [null];
        $this->assertFalse($schema->validate($invalidData), "Error evaluating wildcard index with invalid data");
    }

    public function testOptionalCheckShouldPassOnEmptyArray(): void
    {
        $schema = Validator::array()->optional();

        $this->assertTrue($schema->validate([]), "Array schema should accept empty array when it's optional");
    }

    public function testDoubleRuleValidation(): void
    {
        $schema = Validator::array([
            'name' => [
                Validator::string()->min(1),
                Validator::string()->max(10),
            ],
        ]);

        $data = [
            'name' => str_repeat('a', 10),
        ];
        $this->assertTrue($schema->validate($data), "Array schema should accept double rule");
    }
}
