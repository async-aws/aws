<?php

namespace AsyncAws\CodeDeploy\ValueObject;

/**
 * Information about alarms associated with a deployment or deployment group.
 */
final class AlarmConfiguration
{
    /**
     * Indicates whether the alarm configuration is enabled.
     *
     * @var bool|null
     */
    private $enabled;

    /**
     * Indicates whether a deployment should continue if information about the current state of alarms cannot be retrieved
     * from Amazon CloudWatch. The default value is false.
     *
     * - `true`: The deployment proceeds even if alarm status information can't be retrieved from Amazon CloudWatch.
     * - `false`: The deployment stops if alarm status information can't be retrieved from Amazon CloudWatch.
     *
     * @var bool|null
     */
    private $ignorePollAlarmFailure;

    /**
     * A list of alarms configured for the deployment or deployment group. A maximum of 10 alarms can be added.
     *
     * @var Alarm[]|null
     */
    private $alarms;

    /**
     * @param array{
     *   enabled?: bool|null,
     *   ignorePollAlarmFailure?: bool|null,
     *   alarms?: array<Alarm|array>|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->enabled = $input['enabled'] ?? null;
        $this->ignorePollAlarmFailure = $input['ignorePollAlarmFailure'] ?? null;
        $this->alarms = isset($input['alarms']) ? array_map([Alarm::class, 'create'], $input['alarms']) : null;
    }

    /**
     * @param array{
     *   enabled?: bool|null,
     *   ignorePollAlarmFailure?: bool|null,
     *   alarms?: array<Alarm|array>|null,
     * }|AlarmConfiguration $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return Alarm[]
     */
    public function getAlarms(): array
    {
        return $this->alarms ?? [];
    }

    public function getEnabled(): ?bool
    {
        return $this->enabled;
    }

    public function getIgnorePollAlarmFailure(): ?bool
    {
        return $this->ignorePollAlarmFailure;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->enabled) {
            $payload['enabled'] = (bool) $v;
        }
        if (null !== $v = $this->ignorePollAlarmFailure) {
            $payload['ignorePollAlarmFailure'] = (bool) $v;
        }
        if (null !== $v = $this->alarms) {
            $index = -1;
            $payload['alarms'] = [];
            foreach ($v as $listValue) {
                ++$index;
                $payload['alarms'][$index] = $listValue->requestBody();
            }
        }

        return $payload;
    }
}
