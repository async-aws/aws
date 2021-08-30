<?php

namespace AsyncAws\CloudWatch\ValueObject;

/**
 * A message returned by the `GetMetricData`API, including a code and a description.
 * If a cross-Region `GetMetricData` operation fails with a code of `Forbidden` and a value of `Authentication too
 * complex to retrieve cross region data`, you can correct the problem by running the `GetMetricData` operation in the
 * same Region where the metric data is.
 */
final class MessageData
{
    /**
     * The error code or status code associated with the message.
     */
    private $code;

    /**
     * The message text.
     */
    private $value;

    /**
     * @param array{
     *   Code?: null|string,
     *   Value?: null|string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->code = $input['Code'] ?? null;
        $this->value = $input['Value'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }
}
