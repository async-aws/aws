<?php

namespace AsyncAws\Lambda\ValueObject;

/**
 * The ARN of the runtime and any errors that occured.
 */
final class RuntimeVersionConfig
{
    /**
     * The ARN of the runtime version you want the function to use.
     *
     * @var string|null
     */
    private $runtimeVersionArn;

    /**
     * Error response when Lambda is unable to retrieve the runtime version for a function.
     *
     * @var RuntimeVersionError|null
     */
    private $error;

    /**
     * @param array{
     *   RuntimeVersionArn?: string|null,
     *   Error?: RuntimeVersionError|array|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->runtimeVersionArn = $input['RuntimeVersionArn'] ?? null;
        $this->error = isset($input['Error']) ? RuntimeVersionError::create($input['Error']) : null;
    }

    /**
     * @param array{
     *   RuntimeVersionArn?: string|null,
     *   Error?: RuntimeVersionError|array|null,
     * }|RuntimeVersionConfig $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getError(): ?RuntimeVersionError
    {
        return $this->error;
    }

    public function getRuntimeVersionArn(): ?string
    {
        return $this->runtimeVersionArn;
    }
}
