<?php

namespace AsyncAws\CloudFormation\Input;

use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

/**
 * The input for DescribeStackEvents action.
 */
final class DescribeStackEventsInput extends Input
{
    /**
     * The name or the unique stack ID that's associated with the stack, which aren't always interchangeable:
     *
     * - Running stacks: You can specify either the stack's name or its unique stack ID.
     * - Deleted stacks: You must specify the unique stack ID.
     *
     * @var string|null
     */
    private $stackName;

    /**
     * A string that identifies the next page of events that you want to retrieve.
     *
     * @var string|null
     */
    private $nextToken;

    /**
     * @param array{
     *   StackName?: string|null,
     *   NextToken?: string|null,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->stackName = $input['StackName'] ?? null;
        $this->nextToken = $input['NextToken'] ?? null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   StackName?: string|null,
     *   NextToken?: string|null,
     *   '@region'?: string|null,
     * }|DescribeStackEventsInput $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getNextToken(): ?string
    {
        return $this->nextToken;
    }

    public function getStackName(): ?string
    {
        return $this->stackName;
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
        $body = http_build_query(['Action' => 'DescribeStackEvents', 'Version' => '2010-05-15'] + $this->requestBody(), '', '&', \PHP_QUERY_RFC1738);

        // Return the Request
        return new Request('POST', $uriString, $query, $headers, StreamFactory::create($body));
    }

    public function setNextToken(?string $value): self
    {
        $this->nextToken = $value;

        return $this;
    }

    public function setStackName(?string $value): self
    {
        $this->stackName = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->stackName) {
            $payload['StackName'] = $v;
        }
        if (null !== $v = $this->nextToken) {
            $payload['NextToken'] = $v;
        }

        return $payload;
    }
}
