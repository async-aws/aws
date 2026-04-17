<?php

namespace AsyncAws\Lambda\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

final class GetAliasRequest extends Input
{
    /**
     * The name or ARN of the Lambda function.
     *
     * **Name formats**
     *
     * - **Function name** - `MyFunction`.
     * - **Function ARN** - `arn:aws:lambda:us-west-2:123456789012:function:MyFunction`.
     * - **Partial ARN** - `123456789012:function:MyFunction`.
     *
     * The length constraint applies only to the full ARN. If you specify only the function name, it is limited to 64
     * characters in length.
     *
     * @required
     *
     * @var string|null
     */
    private $functionName;

    /**
     * The name of the alias.
     *
     * @required
     *
     * @var string|null
     */
    private $name;

    /**
     * @param array{
     *   FunctionName?: string,
     *   Name?: string,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->functionName = $input['FunctionName'] ?? null;
        $this->name = $input['Name'] ?? null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   FunctionName?: string,
     *   Name?: string,
     *   '@region'?: string|null,
     * }|GetAliasRequest $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getFunctionName(): ?string
    {
        return $this->functionName;
    }

    public function getName(): ?string
    {
        return $this->name;
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
        if (null === $v = $this->functionName) {
            throw new InvalidArgument(\sprintf('Missing parameter "FunctionName" for "%s". The value cannot be null.', __CLASS__));
        }
        $uri['FunctionName'] = $v;
        if (null === $v = $this->name) {
            throw new InvalidArgument(\sprintf('Missing parameter "Name" for "%s". The value cannot be null.', __CLASS__));
        }
        $uri['Name'] = $v;
        $uriString = '/2015-03-31/functions/' . rawurlencode($uri['FunctionName']) . '/aliases/' . rawurlencode($uri['Name']);

        // Prepare Body
        $body = '';

        // Return the Request
        return new Request('GET', $uriString, $query, $headers, StreamFactory::create($body));
    }

    public function setFunctionName(?string $value): self
    {
        $this->functionName = $value;

        return $this;
    }

    public function setName(?string $value): self
    {
        $this->name = $value;

        return $this;
    }
}
