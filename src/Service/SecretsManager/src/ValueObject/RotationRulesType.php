<?php

namespace AsyncAws\SecretsManager\ValueObject;

/**
 * A structure that defines the rotation configuration for the secret.
 */
final class RotationRulesType
{
    /**
     * The number of days between automatic scheduled rotations of the secret. You can use this value to check that your
     * secret meets your compliance guidelines for how often secrets must be rotated.
     */
    private $automaticallyAfterDays;

    /**
     * The length of the rotation window in hours, for example `3h` for a three hour window. Secrets Manager rotates your
     * secret at any time during this window. The window must not go into the next UTC day. If you don't specify this value,
     * the window automatically ends at the end of the UTC day. The window begins according to the `ScheduleExpression`. For
     * more information, including examples, see Schedule expressions in Secrets Manager rotation.
     *
     * @see https://docs.aws.amazon.com/secretsmanager/latest/userguide/rotate-secrets_schedule.html
     */
    private $duration;

    /**
     * A `cron()` or `rate()` expression that defines the schedule for rotating your secret. Secrets Manager rotation
     * schedules use UTC time zone.
     */
    private $scheduleExpression;

    /**
     * @param array{
     *   AutomaticallyAfterDays?: null|string,
     *   Duration?: null|string,
     *   ScheduleExpression?: null|string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->automaticallyAfterDays = $input['AutomaticallyAfterDays'] ?? null;
        $this->duration = $input['Duration'] ?? null;
        $this->scheduleExpression = $input['ScheduleExpression'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getAutomaticallyAfterDays(): ?string
    {
        return $this->automaticallyAfterDays;
    }

    public function getDuration(): ?string
    {
        return $this->duration;
    }

    public function getScheduleExpression(): ?string
    {
        return $this->scheduleExpression;
    }
}
