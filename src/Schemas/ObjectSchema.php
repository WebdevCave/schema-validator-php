<?php

namespace Webdevcave\SchemaValidator\Schemas;

use Webdevcave\Utility\ArrayUtil;

class ObjectSchema extends ArraySchema
{
    protected function validateSchema(mixed $value): void
    {
        if (!is_object($value)) {
            $this->error($this->customMessages['type'] ?? 'Provided value must be an object');

            return;
        }

        $array = ArrayUtil::fromObject($value);

        parent::validateSchema($array);
    }
}
