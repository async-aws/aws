<?php

namespace AsyncAws\CodeDeploy\ValueObject;

use AsyncAws\CodeDeploy\Enum\AutoRollbackEvent;
use AsyncAws\Core\Exception\InvalidArgument;

/**
 * Information about a configuration for automatically rolling back to a previous version of an application revision
 * when a deployment is not completed successfully.
 */
final class AutoRollbackConfiguration
{
    /**
     * Indicates whether a defined automatic rollback configuration is currently enabled.
     *
     * @var bool|null
     */
    private $enabled;

    /**
     * The event type or types that trigger a rollback.
     *
     * @var list<AutoRollbackEvent::*>|null
     */
    private $events;

    /**
     * @param array{
     *   enabled?: bool|null,
     *   events?: array<AutoRollbackEvent::*>|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->enabled = $input['enabled'] ?? null;
        $this->events = $input['events'] ?? null;
    }

    /**
     * @param array{
     *   enabled?: bool|null,
     *   events?: array<AutoRollbackEvent::*>|null,
     * }|AutoRollbackConfiguration $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getEnabled(): ?bool
    {
        return $this->enabled;
    }

    /**
     * @return list<AutoRollbackEvent::*>
     */
    public function getEvents(): array
    {
        return $this->events ?? [];
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
        if (null !== $v = $this->events) {
            $index = -1;
            $payload['events'] = [];
            foreach ($v as $listValue) {
                ++$index;
                if (!AutoRollbackEvent::exists($listValue)) {
                    /** @psalm-suppress NoValue */
                    throw new InvalidArgument(\sprintf('Invalid parameter "events" for "%s". The value "%s" is not a valid "AutoRollbackEvent".', __CLASS__, $listValue));
                }
                $payload['events'][$index] = $listValue;
            }
        }

        return $payload;
    }
}
