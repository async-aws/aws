<?php

namespace AsyncAws\CodeDeploy\ValueObject;

/**
 * Information about an alarm.
 */
final class Alarm
{
    /**
     * The name of the alarm. Maximum length is 255 characters. Each alarm name can be used only once in a list of alarms.
     *
     * @var string|null
     */
    private $name;

    /**
     * @param array{
     *   name?: string|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->name = $input['name'] ?? null;
    }

    /**
     * @param array{
     *   name?: string|null,
     * }|Alarm $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->name) {
            $payload['name'] = $v;
        }

        return $payload;
    }
}
