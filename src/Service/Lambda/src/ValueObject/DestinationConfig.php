<?php

namespace AsyncAws\Lambda\ValueObject;

/**
 * A configuration object that specifies the destination of an event after Lambda processes it. For more information,
 * see Adding a destination [^1].
 *
 * [^1]: https://docs.aws.amazon.com/lambda/latest/dg/invocation-async-retain-records.html#invocation-async-destinations
 */
final class DestinationConfig
{
    /**
     * The destination configuration for successful invocations. Not supported in `CreateEventSourceMapping` or
     * `UpdateEventSourceMapping`.
     *
     * @var OnSuccess|null
     */
    private $onSuccess;

    /**
     * The destination configuration for failed invocations.
     *
     * @var OnFailure|null
     */
    private $onFailure;

    /**
     * @param array{
     *   OnSuccess?: OnSuccess|array|null,
     *   OnFailure?: OnFailure|array|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->onSuccess = isset($input['OnSuccess']) ? OnSuccess::create($input['OnSuccess']) : null;
        $this->onFailure = isset($input['OnFailure']) ? OnFailure::create($input['OnFailure']) : null;
    }

    /**
     * @param array{
     *   OnSuccess?: OnSuccess|array|null,
     *   OnFailure?: OnFailure|array|null,
     * }|DestinationConfig $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getOnFailure(): ?OnFailure
    {
        return $this->onFailure;
    }

    public function getOnSuccess(): ?OnSuccess
    {
        return $this->onSuccess;
    }
}
