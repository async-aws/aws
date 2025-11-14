<?php

namespace AsyncAws\CloudFormation\Input;

use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

/**
 * The input for DescribeStacks action.
 */
final class DescribeStacksInput extends Input
{
    /**
     * > If you don't pass a parameter to `StackName`, the API returns a response that describes all resources in the
     * > account, which can impact performance. This requires `ListStacks` and `DescribeStacks` permissions.
     * >
     * > Consider using the ListStacks API if you're not passing a parameter to `StackName`.
     * >
     * > The IAM policy below can be added to IAM policies when you want to limit resource-level permissions and avoid
     * > returning a response when no parameter is sent in the request:
     * >
     * > { "Version": "2012-10-17", "Statement": [{ "Effect": "Deny", "Action": "cloudformation:DescribeStacks",
     * > "NotResource": "arn:aws:cloudformation:*:*:stack/* /*" }] }.
     *
     * The name or the unique stack ID that's associated with the stack, which aren't always interchangeable:
     *
     * - Running stacks: You can specify either the stack's name or its unique stack ID.
     * - Deleted stacks: You must specify the unique stack ID.
     *
     * @var string|null
     */
    private $stackName;

    /**
     * The token for the next set of items to return. (You received this token from a previous call.).
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
     * }|DescribeStacksInput $input
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
        $body = http_build_query(['Action' => 'DescribeStacks', 'Version' => '2010-05-15'] + $this->requestBody(), '', '&', \PHP_QUERY_RFC1738);

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
