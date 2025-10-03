<?php

namespace AsyncAws\CloudWatch\ValueObject;

use AsyncAws\CloudWatch\Enum\StatusCode;

/**
 * A `GetMetricData` call returns an array of `MetricDataResult` structures. Each of these structures includes the data
 * points for that metric, along with the timestamps of those data points and other identifying information.
 */
final class MetricDataResult
{
    /**
     * The short name you specified to represent this metric.
     *
     * @var string|null
     */
    private $id;

    /**
     * The human-readable label associated with the data.
     *
     * @var string|null
     */
    private $label;

    /**
     * The timestamps for the data points, formatted in Unix timestamp format. The number of timestamps always matches the
     * number of values and the value for Timestamps[x] is Values[x].
     *
     * @var \DateTimeImmutable[]|null
     */
    private $timestamps;

    /**
     * The data points for the metric corresponding to `Timestamps`. The number of values always matches the number of
     * timestamps and the timestamp for Values[x] is Timestamps[x].
     *
     * @var float[]|null
     */
    private $values;

    /**
     * The status of the returned data. `Complete` indicates that all data points in the requested time range were returned.
     * `PartialData` means that an incomplete set of data points were returned. You can use the `NextToken` value that was
     * returned and repeat your request to get more data points. `NextToken` is not returned if you are performing a math
     * expression. `InternalError` indicates that an error occurred. Retry your request using `NextToken`, if present.
     *
     * @var StatusCode::*|null
     */
    private $statusCode;

    /**
     * A list of messages with additional information about the data returned.
     *
     * @var MessageData[]|null
     */
    private $messages;

    /**
     * @param array{
     *   Id?: string|null,
     *   Label?: string|null,
     *   Timestamps?: \DateTimeImmutable[]|null,
     *   Values?: float[]|null,
     *   StatusCode?: StatusCode::*|null,
     *   Messages?: array<MessageData|array>|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->id = $input['Id'] ?? null;
        $this->label = $input['Label'] ?? null;
        $this->timestamps = $input['Timestamps'] ?? null;
        $this->values = $input['Values'] ?? null;
        $this->statusCode = $input['StatusCode'] ?? null;
        $this->messages = isset($input['Messages']) ? array_map([MessageData::class, 'create'], $input['Messages']) : null;
    }

    /**
     * @param array{
     *   Id?: string|null,
     *   Label?: string|null,
     *   Timestamps?: \DateTimeImmutable[]|null,
     *   Values?: float[]|null,
     *   StatusCode?: StatusCode::*|null,
     *   Messages?: array<MessageData|array>|null,
     * }|MetricDataResult $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    /**
     * @return MessageData[]
     */
    public function getMessages(): array
    {
        return $this->messages ?? [];
    }

    /**
     * @return StatusCode::*|null
     */
    public function getStatusCode(): ?string
    {
        return $this->statusCode;
    }

    /**
     * @return \DateTimeImmutable[]
     */
    public function getTimestamps(): array
    {
        return $this->timestamps ?? [];
    }

    /**
     * @return float[]
     */
    public function getValues(): array
    {
        return $this->values ?? [];
    }
}
