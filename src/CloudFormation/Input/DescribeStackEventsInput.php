<?php

namespace AsyncAws\CloudFormation\Input;

class DescribeStackEventsInput
{
    /**
     * The name or the unique stack ID that is associated with the stack, which are not always interchangeable:.
     *
     * @var string|null
     */
    private $StackName;

    /**
     * A string that identifies the next page of events that you want to retrieve.
     *
     * @var string|null
     */
    private $NextToken;

    /**
     * @param array{
     *   StackName?: string,
     *   NextToken?: string,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->StackName = $input['StackName'] ?? null;
        $this->NextToken = $input['NextToken'] ?? null;
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

    public function requestBody(): string
    {
        $payload = ['Action' => 'DescribeStackEvents', 'Version' => '2010-05-15'];

        if (null !== $v = $this->StackName) {
            $payload['StackName'] = $v;
        }

        if (null !== $v = $this->NextToken) {
            $payload['NextToken'] = $v;
        }

        return http_build_query($payload, '', '&', \PHP_QUERY_RFC1738);
    }

    public function requestHeaders(): array
    {
        $headers = ['content-type' => 'application/x-www-form-urlencoded'];

        return $headers;
    }

    public function requestQuery(): array
    {
        $query = [];

        return $query;
    }

    public function requestUri(): string
    {
        return '/';
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

    public function validate(): void
    {
        // There are no required properties
    }
}
