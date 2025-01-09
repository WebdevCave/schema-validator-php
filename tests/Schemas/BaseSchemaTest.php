<?php

namespace Webdevcave\SchemaValidator\Tests\Schemas;

use BadMethodCallException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Webdevcave\SchemaValidator\Schemas\BaseSchema;
use Webdevcave\SchemaValidator\Validator;

class DummySchema extends BaseSchema
{
    protected ?string $equals = null;

    protected function validateSchema(mixed $value): void
    {
        if (!is_null($this->equals) && $this->equals !== $value) {
            $this->error($this->customMessages['equals'] ?? 'not equals');
        }
    }
}

#[CoversClass(Validator::class)]
#[CoversClass(BaseSchema::class)]
class BaseSchemaTest extends TestCase
{
    private ?BaseSchema $schema = null;

    protected function setUp(): void
    {
        $this->schema = new DummySchema();
    }

    public function testUndefinedChecksShouldThrowException(): void
    {
        $this->expectException(BadMethodCallException::class);
        $this->schema->inexistent('check');
    }

    public function testSetGetRule(): void
    {
        $value = 'value';

        $this->schema->equals($value);
        $this->assertEquals(
            $value, $this->schema->equals(),
            'Schema should be able to set and get a rule value'
        );
    }

    public function testFailingConditions(): void
    {
        $customMessage = 'must be different';
        $this->schema->equals('1', $customMessage);

        $successful = $this->schema->validate('2');
        $errors = $this->schema->errorMessages();
        $expected = [$customMessage];

        $this->assertFalse($successful, 'Value should not be valid');
        $this->assertEquals(
            $expected,
            $errors,
            'Error messages does not match'
        );
    }

    public function testOptionalValidation(): void
    {
        $this->schema->equals('value')->optional();

        $this->assertTrue(
            $this->schema->validate(null),
            'Optional validation should be able to assert null'
        );
    }

    public function testRequiredValidation(): void
    {
        $this->schema->equals('value');

        $this->assertFalse(
            $this->schema->validate(null),
            'Requied validation should not be able to assert null'
        );
    }

    public function testRefineValidation(): void
    {
        $this->schema->refine(function ($value) {
            return $value === 'Carlos';
        }, 'Incorrect');

        $this->assertFalse(
            $this->schema->validate('Carl'),
            'Refine verification should not validate the current value'
        );
        $this->assertTrue(
            $this->schema->validate('Carlos'),
            'Value does not match to the one specified in refine method'
        );
    }
}
