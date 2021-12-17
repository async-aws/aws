<?php

namespace AsyncAws\CloudFormation\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

final class DescribeStackDriftDetectionStatusInput extends Input
{
    /**
     * The ID of the drift detection results of this operation.
     *
     * @required
     *
     * @var string|null
     */
    private $stackDriftDetectionId;

    /**
     * @param array{
     *   StackDriftDetectionId?: string,
     *   @region?: string,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->stackDriftDetectionId = $input['StackDriftDetectionId'] ?? null;
        parent::__construct($input);
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getStackDriftDetectionId(): ?string
    {
        return $this->stackDriftDetectionId;
    }

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = ['content-type' => 'application/x-www-form-urlencoded'];

        // Prepare query
        $query = [];

        // Prepare URI
        $uriString = '/';

        // Prepare Body
        $body = http_build_query(['Action' => 'DescribeStackDriftDetectionStatus', 'Version' => '2010-05-15'] + $this->requestBody(), '', '&', \PHP_QUERY_RFC1738);

        // Return the Request
        return new Request('POST', $uriString, $query, $headers, StreamFactory::create($body));
    }

    public function setStackDriftDetectionId(?string $value): self
    {
        $this->stackDriftDetectionId = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->stackDriftDetectionId) {
            throw new InvalidArgument(sprintf('Missing parameter "StackDriftDetectionId" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['StackDriftDetectionId'] = $v;

        return $payload;
    }
}
