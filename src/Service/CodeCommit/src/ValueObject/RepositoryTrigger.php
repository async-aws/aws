<?php

namespace AsyncAws\CodeCommit\ValueObject;

use AsyncAws\CodeCommit\Enum\RepositoryTriggerEventEnum;
use AsyncAws\Core\Exception\InvalidArgument;

/**
 * Information about a trigger for a repository.
 */
final class RepositoryTrigger
{
    /**
     * The name of the trigger.
     */
    private $name;

    /**
     * The ARN of the resource that is the target for a trigger (for example, the ARN of a topic in Amazon SNS).
     */
    private $destinationArn;

    /**
     * Any custom data associated with the trigger to be included in the information sent to the target of the trigger.
     */
    private $customData;

    /**
     * The branches to be included in the trigger configuration. If you specify an empty array, the trigger applies to all
     * branches.
     */
    private $branches;

    /**
     * The repository events that cause the trigger to run actions in another service, such as sending a notification
     * through Amazon SNS.
     */
    private $events;

    /**
     * @param array{
     *   name: string,
     *   destinationArn: string,
     *   customData?: null|string,
     *   branches?: null|string[],
     *   events: list<RepositoryTriggerEventEnum::*>,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->name = $input['name'];
        $this->destinationArn = $input['destinationArn'];
        $this->customData = $input['customData'] ?? null;
        $this->branches = $input['branches'] ?? null;
        $this->events = $input['events'];
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return string[]
     */
    public function getBranches(): array
    {
        return $this->branches ?? [];
    }

    public function getCustomData(): ?string
    {
        return $this->customData;
    }

    public function getDestinationArn(): string
    {
        return $this->destinationArn;
    }

    /**
     * @return list<RepositoryTriggerEventEnum::*>
     */
    public function getEvents(): array
    {
        return $this->events ?? [];
    }

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->name) {
            throw new InvalidArgument(sprintf('Missing parameter "name" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['name'] = $v;
        if (null === $v = $this->destinationArn) {
            throw new InvalidArgument(sprintf('Missing parameter "destinationArn" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['destinationArn'] = $v;
        if (null !== $v = $this->customData) {
            $payload['customData'] = $v;
        }
        if (null !== $v = $this->branches) {
            $index = -1;
            $payload['branches'] = [];
            foreach ($v as $listValue) {
                ++$index;
                $payload['branches'][$index] = $listValue;
            }
        }
        if (null === $v = $this->events) {
            throw new InvalidArgument(sprintf('Missing parameter "events" for "%s". The value cannot be null.', __CLASS__));
        }

        $index = -1;
        $payload['events'] = [];
        foreach ($v as $listValue) {
            ++$index;
            if (!RepositoryTriggerEventEnum::exists($listValue)) {
                throw new InvalidArgument(sprintf('Invalid parameter "events" for "%s". The value "%s" is not a valid "RepositoryTriggerEventEnum".', __CLASS__, $listValue));
            }
            $payload['events'][$index] = $listValue;
        }

        return $payload;
    }
}
