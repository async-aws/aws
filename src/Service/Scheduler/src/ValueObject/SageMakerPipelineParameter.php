<?php

namespace AsyncAws\Scheduler\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * The name and value pair of a parameter to use to start execution of a SageMaker Model Building Pipeline.
 */
final class SageMakerPipelineParameter
{
    /**
     * Name of parameter to start execution of a SageMaker Model Building Pipeline.
     *
     * @var string
     */
    private $name;

    /**
     * Value of parameter to start execution of a SageMaker Model Building Pipeline.
     *
     * @var string
     */
    private $value;

    /**
     * @param array{
     *   Name: string,
     *   Value: string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->name = $input['Name'] ?? $this->throwException(new InvalidArgument('Missing required field "Name".'));
        $this->value = $input['Value'] ?? $this->throwException(new InvalidArgument('Missing required field "Value".'));
    }

    /**
     * @param array{
     *   Name: string,
     *   Value: string,
     * }|SageMakerPipelineParameter $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        $v = $this->name;
        $payload['Name'] = $v;
        $v = $this->value;
        $payload['Value'] = $v;

        return $payload;
    }

    /**
     * @return never
     */
    private function throwException(\Throwable $exception)
    {
        throw $exception;
    }
}
