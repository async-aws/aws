<?php

namespace AsyncAws\Lambda\ValueObject;

/**
 * The ARN of the runtime and any errors that occured.
 */
final class RuntimeVersionConfig
{
    /**
     * The ARN of the runtime version you want the function to use.
     */
    private $runtimeVersionArn;

    /**
     * Error response when Lambda is unable to retrieve the runtime version for a function.
     */
    private $error;

    /**
     * @param array{
     *   RuntimeVersionArn?: null|string,
     *   Error?: null|RuntimeVersionError|array,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->runtimeVersionArn = $input['RuntimeVersionArn'] ?? null;
        $this->error = isset($input['Error']) ? RuntimeVersionError::create($input['Error']) : null;
    }

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
