<?php

namespace Webdevcave\SchemaValidator\Schemas;

use BadMethodCallException;
use Closure;

/**
 * @method string[] errorMessages()
 */
abstract class BaseSchema
{
    protected bool $optional = false;
    protected ?Closure $callback = null;

    /**
     * @var string[]
     */
    protected array $customMessages = [];

    /**
     * @var string[]
     */
    protected array $errorMessages = [];

    /**
     * @param string $name
     * @param array  $arguments
     *
     * @return mixed
     */
    public function __call(string $name, array $arguments): mixed
    {
        if (!property_exists($this, $name)) {
            throw new BadMethodCallException('Call to undefined method '.$name.'()');
        }

        if (!$arguments) {
            return $this->$name;
        }

        $this->$name = array_shift($arguments);

        if (!empty($arguments)) {
            $this->customMessages[$name] = array_shift($arguments);
        }

        return $this;
    }

    /**
     * @return static
     */
    public function optional(): static
    {
        $this->optional = true;
        return $this;
    }

    public function refine(callable $callback, $customMessage = "Custom validation error"): static
    {
        $this->customMessages['refine'] = $customMessage;
        $this->callback = $callback(...);

        return $this;
    }

    /**
     * @param mixed $value
     *
     * @return bool
     */
    public function validate(mixed $value): bool
    {
        $this->errorMessages = [];

        if (!$this->optional && empty($value)) {
            $this->errorMessages[] = $this->customMessages['required'] ?? 'Field is required';
        }

        $this->validateSchema($value);

        if (!is_null($this->callback)) {
            $callback = $this->callback;
            if (!$callback($value)) {
                $this->errorMessages[] = $this->customMessages['refine'];
            }
        }

        return empty($this->errorMessages);
    }

    /**
     * @param mixed $value
     *
     * @return bool
     */
    abstract protected function validateSchema(mixed $value): bool;
}
