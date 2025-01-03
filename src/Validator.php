<?php

namespace Webdevcave\SchemaValidator;

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
}
