<?php

namespace AsyncAws\S3Vectors\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

final class TagResourceInput extends Input
{
    /**
     * The Amazon Resource Name (ARN) of the Amazon S3 Vectors resource that you're applying tags to. The tagged resource
     * can be a vector bucket or a vector index.
     *
     * @required
     *
     * @var string|null
     */
    private $resourceArn;

    /**
     * The user-defined tag that you want to add to the specified S3 Vectors resource. For more information, see Tagging for
     * cost allocation or attribute-based access control (ABAC) [^1].
     *
     * [^1]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/tagging.html
     *
     * @required
     *
     * @var array<string, string>|null
     */
    private $tags;

    /**
     * @param array{
     *   resourceArn?: string,
     *   tags?: array<string, string>,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->resourceArn = $input['resourceArn'] ?? null;
        $this->tags = $input['tags'] ?? null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   resourceArn?: string,
     *   tags?: array<string, string>,
     *   '@region'?: string|null,
     * }|TagResourceInput $input
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
        $bodyPayload = $this->requestBody();
        $body = empty($bodyPayload) ? '{}' : json_encode($bodyPayload, 4194304);

        // Return the Request
        return new Request('POST', $uriString, $query, $headers, StreamFactory::create($body));
    }

    public function setResourceArn(?string $value): self
    {
        $this->resourceArn = $value;

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

        if (null === $v = $this->tags) {
            throw new InvalidArgument(\sprintf('Missing parameter "tags" for "%s". The value cannot be null.', __CLASS__));
        }

        if (empty($v)) {
            $payload['tags'] = new \stdClass();
        } else {
            $payload['tags'] = [];
            foreach ($v as $name => $mv) {
                $payload['tags'][$name] = $mv;
            }
        }

        return $payload;
    }
}
