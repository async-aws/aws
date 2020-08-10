<?php

namespace AsyncAws\Rekognition\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

final class DeleteCollectionRequest extends Input
{
    /**
     * ID of the collection to delete.
     *
     * @required
     *
     * @var string|null
     */
    private $CollectionId;

    /**
     * @param array{
     *   CollectionId?: string,
     *   @region?: string,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->CollectionId = $input['CollectionId'] ?? null;
        parent::__construct($input);
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getCollectionId(): ?string
    {
        return $this->CollectionId;
    }

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = [
            'Content-Type' => 'application/x-amz-json-1.1',
            'X-Amz-Target' => 'RekognitionService.DeleteCollection',
        ];

        // Prepare query
        $query = [];

        // Prepare URI
        $uriString = '/';

        // Prepare Body
        $bodyPayload = $this->requestBody();
        $body = empty($bodyPayload) ? '{}' : json_encode($bodyPayload);

        // Return the Request
        return new Request('POST', $uriString, $query, $headers, StreamFactory::create($body));
    }

    public function setCollectionId(?string $value): self
    {
        $this->CollectionId = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->CollectionId) {
            throw new InvalidArgument(sprintf('Missing parameter "CollectionId" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['CollectionId'] = $v;

        return $payload;
    }
}
