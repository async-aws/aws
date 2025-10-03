<?php

namespace AsyncAws\CloudWatch\ValueObject;

/**
 * This structure includes the `Timezone` parameter, which you can use to specify your time zone so that the labels that
 * are associated with returned metrics display the correct time for your time zone.
 *
 * The `Timezone` value affects a label only if you have a time-based dynamic expression in the label. For more
 * information about dynamic expressions in labels, see Using Dynamic Labels [^1].
 *
 * [^1]: https://docs.aws.amazon.com/AmazonCloudWatch/latest/monitoring/graph-dynamic-labels.html
 */
final class LabelOptions
{
    /**
     * The time zone to use for metric data return in this operation. The format is `+` or `-` followed by four digits. The
     * first two digits indicate the number of hours ahead or behind of UTC, and the final two digits are the number of
     * minutes. For example, +0130 indicates a time zone that is 1 hour and 30 minutes ahead of UTC. The default is +0000.
     *
     * @var string|null
     */
    private $timezone;

    /**
     * @param array{
     *   Timezone?: string|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->timezone = $input['Timezone'] ?? null;
    }

    /**
     * @param array{
     *   Timezone?: string|null,
     * }|LabelOptions $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getTimezone(): ?string
    {
        return $this->timezone;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->timezone) {
            $payload['Timezone'] = $v;
        }

        return $payload;
    }
}
