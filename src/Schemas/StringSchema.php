<?php

namespace Webdevcave\SchemaValidator\Schemas;

use Closure;

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
     * @param mixed $value
     *
     * @return bool
     */
    protected function validateSchema(mixed $value): bool
    {
        if (!is_string($value)) {
            $this->errorMessages[] = $this->customMessages['type']
                ?? "Value must be a string";
        }

        if (!is_null($this->min)) {
            if (mb_strlen($value) < $this->min) {
                $this->errorMessages[] = $this->customMessages['minLength']
                    ?? "Value must be at least {$this->min} characters";
            }
        }

        if (!is_null($this->max)) {
            if (mb_strlen($value) > $this->max) {
                $this->errorMessages[] = $this->customMessages['maxLength']
                    ?? "Value must be at most {$this->max} characters";
            }
        }

        if (!is_null($this->pattern)) {
            if (!preg_match($this->pattern, $value)) {
                $this->errorMessages[] = $this->customMessages['pattern']
                    ?? "Value pattern must match required format";
            }
        }

        return empty($this->errorMessages);
    }
}
