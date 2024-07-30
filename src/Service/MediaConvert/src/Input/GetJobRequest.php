<?php

namespace AsyncAws\MediaConvert\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

/**
 * Query a job by sending a request with the job ID.
 */
final class GetJobRequest extends Input
{
    /**
     * the job ID of the job.
     *
     * @required
     *
     * @var string|null
     */
    private $id;

    /**
     * @param array{
     *   Id?: string,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->id = $input['Id'] ?? null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   Id?: string,
     *   '@region'?: string|null,
     * }|GetJobRequest $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getId(): ?string
    {
        return $this->id;
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
        if (null === $v = $this->id) {
            throw new InvalidArgument(\sprintf('Missing parameter "Id" for "%s". The value cannot be null.', __CLASS__));
        }
        $uri['id'] = $v;
        $uriString = '/2017-08-29/jobs/' . rawurlencode($uri['id']);

        // Prepare Body
        $body = '';

        // Return the Request
        return new Request('GET', $uriString, $query, $headers, StreamFactory::create($body));
    }

    public function setId(?string $value): self
    {
        $this->id = $value;

        return $this;
    }
}
