<?php

namespace AsyncAws\SecretsManager\ValueObject;

/**
 * A structure that defines the rotation configuration for the secret.
 */
final class RotationRulesType
{
    /**
     * The number of days between rotations of the secret. You can use this value to check that your secret meets your
     * compliance guidelines for how often secrets must be rotated. If you use this field to set the rotation schedule,
     * Secrets Manager calculates the next rotation date based on the previous rotation. Manually updating the secret value
     * by calling `PutSecretValue` or `UpdateSecret` is considered a valid rotation.
     */
    private $automaticallyAfterDays;

    /**
     * The length of the rotation window in hours, for example `3h` for a three hour window. Secrets Manager rotates your
     * secret at any time during this window. The window must not extend into the next rotation window or the next UTC day.
     * The window starts according to the `ScheduleExpression`. If you don't specify a `Duration`, for a
     * `ScheduleExpression` in hours, the window automatically closes after one hour. For a `ScheduleExpression` in days,
     * the window automatically closes at the end of the UTC day. For more information, including examples, see Schedule
     * expressions in Secrets Manager rotation in the *Secrets Manager Users Guide*.
     *
     * @see https://docs.aws.amazon.com/secretsmanager/latest/userguide/rotate-secrets_schedule.html
     */
    private $duration;

    /**
     * A `cron()` or `rate()` expression that defines the schedule for rotating your secret. Secrets Manager rotation
     * schedules use UTC time zone. Secrets Manager rotates your secret any time during a rotation window.
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
