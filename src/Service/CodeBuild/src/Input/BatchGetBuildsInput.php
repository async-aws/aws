<?php

namespace AsyncAws\CodeBuild\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

final class BatchGetBuildsInput extends Input
{
    /**
     * The IDs of the builds.
     *
     * @required
     *
     * @var string[]|null
     */
    private $ids;

    /**
     * @param array{
     *   ids?: string[],
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->ids = $input['ids'] ?? null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   ids?: string[],
     *   '@region'?: string|null,
     * }|BatchGetBuildsInput $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return string[]
     */
    public function getIds(): array
    {
        return $this->ids ?? [];
    }

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = [
            'Content-Type' => 'application/x-amz-json-1.1',
            'X-Amz-Target' => 'CodeBuild_20161006.BatchGetBuilds',
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

    /**
     * @param string[] $value
     */
    public function setIds(array $value): self
    {
        $this->ids = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->ids) {
            throw new InvalidArgument(sprintf('Missing parameter "ids" for "%s". The value cannot be null.', __CLASS__));
        }

        $index = -1;
        $payload['ids'] = [];
        foreach ($v as $listValue) {
            ++$index;
            $payload['ids'][$index] = $listValue;
        }

        return $payload;
    }
}
