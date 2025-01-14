<?php

namespace Webdevcave\SchemaValidator\Tests\Schemas;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use stdClass;
use Webdevcave\SchemaValidator\Schemas\BaseSchema;
use Webdevcave\SchemaValidator\Schemas\StringSchema;
use Webdevcave\SchemaValidator\Validator;

#[CoversClass(Validator::class)]
#[CoversClass(BaseSchema::class)]
#[CoversClass(StringSchema::class)]
class StringSchemaTest extends TestCase
{
    public function testMustValidateOnlyStrings(): void
    {
        $schema = Validator::string();

        $this->assertFalse($schema->validate(1), 'Should not validate integers');
        $this->assertFalse($schema->validate(1.2), 'Should not validate floats');
        $this->assertFalse($schema->validate([]), 'Should not validate arrays');
        $this->assertFalse($schema->validate(new stdClass()), 'Should not validate objects');
        $this->assertFalse($schema->validate(null), 'Should not validate null');
        $this->assertTrue($schema->validate('my string'), 'Should validate strings');
    }

    public function testMinLengthValidation(): void
    {
        $myStr = str_repeat('a', 5);

        $this->assertTrue(
            Validator::string()->min(5)->validate($myStr),
            'Should validate when length is equal to minimum'
        );
        $this->assertTrue(
            Validator::string()->min(4)->validate($myStr),
            'Should validate when length is greater than minimum'
        );
        $this->assertFalse(
            Validator::string()->min(6)->validate($myStr),
            'Should not validate when length is lesser than minimum'
        );
    }

    public function testMaxLengthValidation(): void
    {
        $myStr = str_repeat('a', 5);

        $this->assertTrue(
            Validator::string()->max(5)->validate($myStr),
            'Should validate when length is equal to maximum'
        );
        $this->assertFalse(
            Validator::string()->max(4)->validate($myStr),
            'Should not validate when length is greater than maximum'
        );
        $this->assertTrue(
            Validator::string()->max(6)->validate($myStr),
            'Should not validate when length is lesser than maximum'
        );
    }

    public function testPatternValidation(): void
    {
        $myString = 'Hello world';
        $validPattern = '/Hello/';
        $invalidPattern = '/Goodbye/';

        $this->assertTrue(
            Validator::string()->pattern($validPattern)->validate($myString),
            'Should validate a valid pattern'
        );
        $this->assertFalse(
            Validator::string()->pattern($invalidPattern)->validate($myString),
            'Should not validate an invalid pattern'
        );
    }
}
