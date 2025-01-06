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
    private array $errorMessages = [];

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

        if (!empty($value)) {
            $this->validateSchema($value);
        }

        if (!is_null($this->callback)) {
            $callback = $this->callback;
            if (!$callback($value)) {
                $this->errorMessages[] = $this->customMessages['refine'];
            }
        }

        return empty($this->errorMessages);
    }

    /**
     * @param string $message
     *
     * @return void
     */
    protected final function error(string $message): void
    {
        $this->errorMessages[] = $message;
    }

    /**
     * @param array $errors
     *
     * @return void
     */
    protected final function setErrorMessages(array $errors): void
    {
        $this->errorMessages = $errors;
    }

    /**
     * @param mixed $value
     *
     * @return void
     */
    abstract protected function validateSchema(mixed $value): void;
}
