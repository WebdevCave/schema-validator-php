<?php

namespace Webdevcave\SchemaValidator\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Webdevcave\SchemaValidator\Schemas\ArraySchema;
use Webdevcave\SchemaValidator\Schemas\NumericSchema;
use Webdevcave\SchemaValidator\Schemas\ObjectSchema;
use Webdevcave\SchemaValidator\Schemas\StringSchema;
use Webdevcave\SchemaValidator\Validator;

#[CoversClass(Validator::class)]
#[CoversClass(StringSchema::class)]
#[CoversClass(NumericSchema::class)]
#[CoversClass(ObjectSchema::class)]
#[CoversClass(ArraySchema::class)]
class ValidatorTest extends TestCase
{
    public function testBuilders()
    {
        $this->assertInstanceOf(StringSchema::class, Validator::string());
        $this->assertInstanceOf(NumericSchema::class, Validator::numeric());
        $this->assertInstanceOf(ObjectSchema::class, Validator::object());
        $this->assertInstanceOf(ArraySchema::class, Validator::array());
    }
}
