<?php

namespace AsyncAws\Scheduler\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

final class GetScheduleInput extends Input
{
    /**
     * The name of the schedule group associated with this schedule. If you omit this, EventBridge Scheduler assumes that
     * the schedule is associated with the default group.
     *
     * @var string|null
     */
    private $groupName;

    /**
     * The name of the schedule to retrieve.
     *
     * @required
     *
     * @var string|null
     */
    private $name;

    /**
     * @param array{
     *   GroupName?: string|null,
     *   Name?: string,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->groupName = $input['GroupName'] ?? null;
        $this->name = $input['Name'] ?? null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   GroupName?: string|null,
     *   Name?: string,
     *   '@region'?: string|null,
     * }|GetScheduleInput $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getGroupName(): ?string
    {
        return $this->groupName;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ];

        // Prepare query
        $query = [];
        if (null !== $this->groupName) {
            $query['groupName'] = $this->groupName;
        }

        // Prepare URI
        $uri = [];
        if (null === $v = $this->name) {
            throw new InvalidArgument(\sprintf('Missing parameter "Name" for "%s". The value cannot be null.', __CLASS__));
        }
        $uri['Name'] = $v;
        $uriString = '/schedules/' . rawurlencode($uri['Name']);

        // Prepare Body
        $body = '';

        // Return the Request
        return new Request('GET', $uriString, $query, $headers, StreamFactory::create($body));
    }

    public function setGroupName(?string $value): self
    {
        $this->groupName = $value;

        return $this;
    }

    public function setName(?string $value): self
    {
        $this->name = $value;

        return $this;
    }
}
