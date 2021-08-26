<?php

namespace AsyncAws\CloudWatch\ValueObject;

/**
 * This structure includes the `Timezone` parameter, which you can use to specify your time zone so that the labels of
 * returned data display the correct time for your time zone.
 */
final class LabelOptions
{
    /**
     * The time zone to use for metric data return in this operation. The format is `+` or `-` followed by four digits. The
     * first two digits indicate the number of hours ahead or behind of UTC, and the final two digits are the number of
     * minutes. For example, +0130 indicates a time zone that is 1 hour and 30 minutes ahead of UTC. The default is +0000.
     */
    private $timezone;

    /**
     * @param array{
     *   Timezone?: null|string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->timezone = $input['Timezone'] ?? null;
    }

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
