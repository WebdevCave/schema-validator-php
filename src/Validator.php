<?php

namespace Webdevcave\SchemaValidator;

use Webdevcave\SchemaValidator\Schemas\ArraySchema;
use Webdevcave\SchemaValidator\Schemas\BaseSchema;
use Webdevcave\SchemaValidator\Schemas\NumericSchema;
use Webdevcave\SchemaValidator\Schemas\ObjectSchema;
use Webdevcave\SchemaValidator\Schemas\StringSchema;

class Validator
{
    /**
     * @return StringSchema
     */
    public static function string(): StringSchema
    {
        return new StringSchema();
    }

    /**
     * @return NumericSchema
     */
    public static function numeric(): NumericSchema
    {
        return new NumericSchema();
    }

    /**
     * @param array $subSchemas
     *
     * @return ArraySchema
     */
    public static function array(array $subSchemas = []): ArraySchema
    {
        return new ArraySchema($subSchemas);
    }

    /**
     * @param array $subSchemas
     *
     * @return ArraySchema
     */
    public static function object(array $subSchemas = []): ArraySchema
    {
        return new ObjectSchema($subSchemas);
    }
}
