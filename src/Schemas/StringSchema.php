<?php

namespace Webdevcave\SchemaValidator\Schemas;

/**
 * @method StringSchema min(int $minLength, string $customMessage = null)
 * @method StringSchema max(int $maxLength, string $customMessage = null)
 * @method StringSchema pattern(string $pattern, string $customMessage = null)
 */
class StringSchema extends BaseSchema
{
    protected ?int $min = null;
    protected ?int $max = null;
    protected ?string $pattern = null;

    /**
     * @inheritDoc
     */
    protected function validateSchema(mixed $value): void
    {
        if (!is_string($value)) {
            $this->error($this->customMessages['type'] ?? 'Value must be a string');
        }

        if (!is_null($this->min)) {
            if (mb_strlen($value) < $this->min) {
                $this->error($this->customMessages['min'] ?? "Value must be at least {$this->min} characters");
            }
        }

        if (!is_null($this->max)) {
            if (mb_strlen($value) > $this->max) {
                $this->error($this->customMessages['max'] ?? "Value must be at most {$this->max} characters");
            }
        }

        if (!is_null($this->pattern)) {
            if (!preg_match($this->pattern, $value)) {
                $this->error($this->customMessages['pattern'] ?? "Value pattern doesn't match required format");
            }
        }
    }
}
