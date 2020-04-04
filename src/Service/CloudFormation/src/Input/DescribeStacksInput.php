<?php

namespace AsyncAws\CloudFormation\Input;

use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

final class DescribeStacksInput extends Input
{
    /**
     * The name or the unique stack ID that is associated with the stack, which are not always interchangeable:.
     *
     * @var string|null
     */
    private $StackName;

    /**
     * A string that identifies the next page of stacks that you want to retrieve.
     *
     * @var string|null
     */
    private $NextToken;

    /**
     * @param array{
     *   StackName?: string,
     *   NextToken?: string,
     *   @region?: string,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->StackName = $input['StackName'] ?? null;
        $this->NextToken = $input['NextToken'] ?? null;
        parent::__construct($input);
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getNextToken(): ?string
    {
        return $this->NextToken;
    }

    public function getStackName(): ?string
    {
        return $this->StackName;
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
        $body = http_build_query(['Action' => 'DescribeStacks', 'Version' => '2010-05-15'] + $this->requestBody(), '', '&', \PHP_QUERY_RFC1738);

        // Return the Request
        return new Request('POST', $uriString, $query, $headers, StreamFactory::create($body));
    }

    public function setNextToken(?string $value): self
    {
        $this->NextToken = $value;

        return $this;
    }

    public function setStackName(?string $value): self
    {
        $this->StackName = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->StackName) {
            $payload['StackName'] = $v;
        }
        if (null !== $v = $this->NextToken) {
            $payload['NextToken'] = $v;
        }

        return $payload;
    }
}
