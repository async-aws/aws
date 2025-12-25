<?php

namespace AsyncAws\S3Vectors\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

final class ListTagsForResourceInput extends Input
{
    /**
     * The Amazon Resource Name (ARN) of the Amazon S3 Vectors resource that you want to list tags for. The tagged resource
     * can be a vector bucket or a vector index.
     *
     * @required
     *
     * @var string|null
     */
    private $resourceArn;

    /**
     * @param array{
     *   resourceArn?: string,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->resourceArn = $input['resourceArn'] ?? null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   resourceArn?: string,
     *   '@region'?: string|null,
     * }|ListTagsForResourceInput $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getResourceArn(): ?string
    {
        return $this->resourceArn;
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
        if (null === $v = $this->resourceArn) {
            throw new InvalidArgument(\sprintf('Missing parameter "resourceArn" for "%s". The value cannot be null.', __CLASS__));
        }
        $uri['resourceArn'] = $v;
        $uriString = '/tags/' . rawurlencode($uri['resourceArn']);

        // Prepare Body
        $body = '';

        // Return the Request
        return new Request('GET', $uriString, $query, $headers, StreamFactory::create($body));
    }

    public function setResourceArn(?string $value): self
    {
        $this->resourceArn = $value;

        return $this;
    }
}
