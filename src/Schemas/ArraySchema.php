<?php

namespace Webdevcave\SchemaValidator\Schemas;

use Exception;

class ArraySchema extends BaseSchema
{
    /**
     * @param array $subSchemas
     */
    public function __construct(
        private readonly array $subSchemas = []
    ) {
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    protected function validateSchema(mixed $value): void
    {
        if (!is_array($value)) {
            $this->error($this->customMessages['type'] ?? "Provided value must be an array");
            return;
        }

        $errors = [];

        foreach ($this->subSchemas as $index => $subSchema) {
            $schemas = !is_array($subSchema) ? [$subSchema] : $subSchema;

            foreach ($schemas as $schema) {
                if ($index === '*') {
                    foreach ($value as $itemIndex => $item) {
                        $this->subschemeValidate($schema, $item, $itemIndex, $errors);
                    }

                    continue;
                }

                $this->subschemeValidate($schema, $value[$index] ?? null, $index, $errors);
            }
        }

        $this->setErrorMessages($errors);
    }

    /**
     * @param BaseSchema $schema
     * @param mixed      $value
     * @param string     $index
     * @param array      $errors
     *
     * @return void
     */
    private function subschemeValidate(BaseSchema $schema, mixed $value, string $index, array &$errors): void
    {
        if (!$schema->validate($value)) {
            $errors[$index] = array_merge($errors[$index] ?? [], $schema->errorMessages());
        }
    }
}
