<?php

namespace AsyncAws\Scheduler\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;
use AsyncAws\Scheduler\ValueObject\Tag;

final class CreateScheduleGroupInput extends Input
{
    /**
     * Unique, case-sensitive identifier you provide to ensure the idempotency of the request. If you do not specify a
     * client token, EventBridge Scheduler uses a randomly generated token for the request to ensure idempotency.
     *
     * @var string|null
     */
    private $clientToken;

    /**
     * The name of the schedule group that you are creating.
     *
     * @required
     *
     * @var string|null
     */
    private $name;

    /**
     * The list of tags to associate with the schedule group.
     *
     * @var Tag[]|null
     */
    private $tags;

    /**
     * @param array{
     *   ClientToken?: string|null,
     *   Name?: string,
     *   Tags?: array<Tag|array>|null,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->clientToken = $input['ClientToken'] ?? null;
        $this->name = $input['Name'] ?? null;
        $this->tags = isset($input['Tags']) ? array_map([Tag::class, 'create'], $input['Tags']) : null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   ClientToken?: string|null,
     *   Name?: string,
     *   Tags?: array<Tag|array>|null,
     *   '@region'?: string|null,
     * }|CreateScheduleGroupInput $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getClientToken(): ?string
    {
        return $this->clientToken;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @return Tag[]
     */
    public function getTags(): array
    {
        return $this->tags ?? [];
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
        $bodyPayload = $this->requestBody();
        $body = empty($bodyPayload) ? '{}' : json_encode($bodyPayload, 4194304);

        // Return the Request
        return new Request('POST', $uriString, $query, $headers, StreamFactory::create($body));
    }

    public function setClientToken(?string $value): self
    {
        $this->clientToken = $value;

        return $this;
    }

    public function setName(?string $value): self
    {
        $this->name = $value;

        return $this;
    }

    /**
     * @param Tag[] $value
     */
    public function setTags(array $value): self
    {
        $this->tags = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->clientToken) {
            $v = uuid_create(\UUID_TYPE_RANDOM);
        }
        $payload['ClientToken'] = $v;

        if (null !== $v = $this->tags) {
            $index = -1;
            $payload['Tags'] = [];
            foreach ($v as $listValue) {
                ++$index;
                $payload['Tags'][$index] = $listValue->requestBody();
            }
        }

        return $payload;
    }
}
