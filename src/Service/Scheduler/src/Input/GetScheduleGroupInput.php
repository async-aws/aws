<?php

namespace AsyncAws\Scheduler\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

final class GetScheduleGroupInput extends Input
{
    /**
     * The name of the schedule group to retrieve.
     *
     * @required
     *
     * @var string|null
     */
    private $name;

    /**
     * @param array{
     *   Name?: string,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->name = $input['Name'] ?? null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   Name?: string,
     *   '@region'?: string|null,
     * }|GetScheduleGroupInput $input
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
    public function request(): Request
    {
        // Prepare headers
        $headers = [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ];

        // Prepare query
        $query = [];

        // Prepare URI
        $uri = [];
        if (null === $v = $this->name) {
            throw new InvalidArgument(\sprintf('Missing parameter "Name" for "%s". The value cannot be null.', __CLASS__));
        }
        $uri['Name'] = $v;
        $uriString = '/schedule-groups/' . rawurlencode($uri['Name']);

        // Prepare Body
        $body = '';

        // Return the Request
        return new Request('GET', $uriString, $query, $headers, StreamFactory::create($body));
    }

    public function setName(?string $value): self
    {
        $this->name = $value;

        return $this;
    }
}
