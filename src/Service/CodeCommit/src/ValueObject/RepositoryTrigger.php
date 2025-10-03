<?php

namespace AsyncAws\CodeCommit\ValueObject;

use AsyncAws\CodeCommit\Enum\RepositoryTriggerEventEnum;
use AsyncAws\Core\Exception\InvalidArgument;

/**
 * Information about a trigger for a repository.
 *
 * > If you want to receive notifications about repository events, consider using notifications instead of triggers. For
 * > more information, see Configuring notifications for repository events [^1].
 *
 * [^1]: https://docs.aws.amazon.com/codecommit/latest/userguide/how-to-repository-email.html
 */
final class RepositoryTrigger
{
    /**
     * The name of the trigger.
     *
     * @var string
     */
    private $name;

    /**
     * The ARN of the resource that is the target for a trigger (for example, the ARN of a topic in Amazon SNS).
     *
     * @var string
     */
    private $destinationArn;

    /**
     * Any custom data associated with the trigger to be included in the information sent to the target of the trigger.
     *
     * @var string|null
     */
    private $customData;

    /**
     * The branches to be included in the trigger configuration. If you specify an empty array, the trigger applies to all
     * branches.
     *
     * > Although no content is required in the array, you must include the array itself.
     *
     * @var string[]|null
     */
    private $branches;

    /**
     * The repository events that cause the trigger to run actions in another service, such as sending a notification
     * through Amazon SNS.
     *
     * > The valid value "all" cannot be used with any other values.
     *
     * @var list<RepositoryTriggerEventEnum::*>
     */
    private $events;

    /**
     * @param array{
     *   name: string,
     *   destinationArn: string,
     *   customData?: string|null,
     *   branches?: string[]|null,
     *   events: array<RepositoryTriggerEventEnum::*>,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->name = $input['name'] ?? $this->throwException(new InvalidArgument('Missing required field "name".'));
        $this->destinationArn = $input['destinationArn'] ?? $this->throwException(new InvalidArgument('Missing required field "destinationArn".'));
        $this->customData = $input['customData'] ?? null;
        $this->branches = $input['branches'] ?? null;
        $this->events = $input['events'] ?? $this->throwException(new InvalidArgument('Missing required field "events".'));
    }

    /**
     * @param array{
     *   name: string,
     *   destinationArn: string,
     *   customData?: string|null,
     *   branches?: string[]|null,
     *   events: array<RepositoryTriggerEventEnum::*>,
     * }|RepositoryTrigger $input
     */
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
        return $this->events;
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
        $v = $this->name;
        $payload['name'] = $v;
        $v = $this->destinationArn;
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
        $v = $this->events;

        $index = -1;
        $payload['events'] = [];
        foreach ($v as $listValue) {
            ++$index;
            if (!RepositoryTriggerEventEnum::exists($listValue)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "events" for "%s". The value "%s" is not a valid "RepositoryTriggerEventEnum".', __CLASS__, $listValue));
            }
            $payload['events'][$index] = $listValue;
        }

        return $payload;
    }

    /**
     * @return never
     */
    private function throwException(\Throwable $exception)
    {
        throw $exception;
    }
}
