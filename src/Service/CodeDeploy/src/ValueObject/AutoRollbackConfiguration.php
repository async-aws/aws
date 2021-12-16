<?php

namespace AsyncAws\CodeDeploy\ValueObject;

use AsyncAws\CodeDeploy\Enum\AutoRollbackEvent;
use AsyncAws\Core\Exception\InvalidArgument;

/**
 * Configuration information for an automatic rollback that is added when a deployment is created.
 */
final class AutoRollbackConfiguration
{
    /**
     * Indicates whether a defined automatic rollback configuration is currently enabled.
     */
    private $enabled;

    /**
     * The event type or types that trigger a rollback.
     */
    private $events;

    /**
     * @param array{
     *   enabled?: null|bool,
     *   events?: null|list<AutoRollbackEvent::*>,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->enabled = $input['enabled'] ?? null;
        $this->events = $input['events'] ?? null;
    }

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
                    throw new InvalidArgument(sprintf('Invalid parameter "events" for "%s". The value "%s" is not a valid "AutoRollbackEvent".', __CLASS__, $listValue));
                }
                $payload['events'][$index] = $listValue;
            }
        }

        return $payload;
    }
}
