<?php

namespace Webdevcave\SchemaValidator\Schemas;

/**
 * @method NumericSchema min(int $minValue, string $customMessage = null)
 * @method NumericSchema max(int $maxValue, string $customMessage = null)
 */
class NumericSchema extends BaseSchema
{
    protected bool $integerOnly = false;
    protected bool $floatOnly = false;
    protected bool $positiveOnly = false;
    protected ?int $min = null;
    protected ?int $max = null;

    /**
     * @return $this
     */
    public function integer(string $customMessage = 'Value must be an integer'): static
    {
        $this->integerOnly = true;
        $this->customMessages['integerOnly'] = $customMessage;

        return $this;
    }

    /**
     * @return $this
     */
    public function float(string $customMessage = 'Value must be a float'): static
    {
        $this->floatOnly = true;
        $this->customMessages['floatOnly'] = $customMessage;

        return $this;
    }

    /**
     * @return $this
     */
    public function positive(string $customMessage = 'Value must be positive'): static
    {
        $this->positiveOnly = true;
        $this->customMessages['positiveOnly'] = $customMessage;

        return $this;
    }

    /**
     * @inheritDoc
     */
    protected function validateSchema(mixed $value): void
    {
        if (!is_numeric($value)) {
            $this->error($this->customMessages['type'] ?? 'Value must be a number');
        }

        if (!is_null($this->min) && $value < $this->min) {
            $this->error($this->customMessages['min'] ?? "Value must be at least {$this->min}");
        }

        if (!is_null($this->max) && $value > $this->max) {
            $this->error($this->customMessages['max'] ?? "Value must be at most {$this->max}");
        }

        if ($this->integerOnly && !is_int($value)) {
            $this->error($this->customMessages['integerOnly']);
        }

        if ($this->floatOnly && !is_float($value)) {
            $this->error($this->customMessages['floatOnly']);
        }

        if ($this->positiveOnly && $value < 0) {
            $this->error($this->customMessages['positiveOnly']);
        }
    }
}
