<?php

namespace AsyncAws\Rekognition\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

final class CreateCollectionRequest extends Input
{
    /**
     * ID for the collection that you are creating.
     *
     * @required
     *
     * @var string|null
     */
    private $collectionId;

    /**
     * A set of tags (key-value pairs) that you want to attach to the collection.
     *
     * @var array<string, string>|null
     */
    private $tags;

    /**
     * @param array{
     *   CollectionId?: string,
     *   Tags?: array<string, string>|null,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->collectionId = $input['CollectionId'] ?? null;
        $this->tags = $input['Tags'] ?? null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   CollectionId?: string,
     *   Tags?: array<string, string>|null,
     *   '@region'?: string|null,
     * }|CreateCollectionRequest $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getCollectionId(): ?string
    {
        return $this->collectionId;
    }

    /**
     * @return array<string, string>
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
            'Content-Type' => 'application/x-amz-json-1.1',
            'X-Amz-Target' => 'RekognitionService.CreateCollection',
            'Accept' => 'application/json',
        ];

        // Prepare query
        $query = [];

        // Prepare URI
        $uriString = '/';

        // Prepare Body
        $bodyPayload = $this->requestBody();
        $body = empty($bodyPayload) ? '{}' : json_encode($bodyPayload, 4194304);

        // Return the Request
        return new Request('POST', $uriString, $query, $headers, StreamFactory::create($body));
    }

    public function setCollectionId(?string $value): self
    {
        $this->collectionId = $value;

        return $this;
    }

    /**
     * @param array<string, string> $value
     */
    public function setTags(array $value): self
    {
        $this->tags = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->collectionId) {
            throw new InvalidArgument(\sprintf('Missing parameter "CollectionId" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['CollectionId'] = $v;
        if (null !== $v = $this->tags) {
            if (empty($v)) {
                $payload['Tags'] = new \stdClass();
            } else {
                $payload['Tags'] = [];
                foreach ($v as $name => $mv) {
                    $payload['Tags'][$name] = $mv;
                }
            }
        }

        return $payload;
    }
}
