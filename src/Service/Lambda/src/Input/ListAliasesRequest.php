<?php

namespace AsyncAws\Lambda\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

final class ListAliasesRequest extends Input
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
     * Specify a function version to only list aliases that invoke that version.
     *
     * @var string|null
     */
    private $functionVersion;

    /**
     * Specify the pagination token that's returned by a previous request to retrieve the next page of results.
     *
     * @var string|null
     */
    private $marker;

    /**
     * Limit the number of aliases returned.
     *
     * @var int|null
     */
    private $maxItems;

    /**
     * @param array{
     *   FunctionName?: string,
     *   FunctionVersion?: string|null,
     *   Marker?: string|null,
     *   MaxItems?: int|null,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->functionName = $input['FunctionName'] ?? null;
        $this->functionVersion = $input['FunctionVersion'] ?? null;
        $this->marker = $input['Marker'] ?? null;
        $this->maxItems = $input['MaxItems'] ?? null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   FunctionName?: string,
     *   FunctionVersion?: string|null,
     *   Marker?: string|null,
     *   MaxItems?: int|null,
     *   '@region'?: string|null,
     * }|ListAliasesRequest $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getFunctionName(): ?string
    {
        return $this->functionName;
    }

    public function getFunctionVersion(): ?string
    {
        return $this->functionVersion;
    }

    public function getMarker(): ?string
    {
        return $this->marker;
    }

    public function getMaxItems(): ?int
    {
        return $this->maxItems;
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
        if (null !== $this->functionVersion) {
            $query['FunctionVersion'] = $this->functionVersion;
        }
        if (null !== $this->marker) {
            $query['Marker'] = $this->marker;
        }
        if (null !== $this->maxItems) {
            $query['MaxItems'] = (string) $this->maxItems;
        }

        // Prepare URI
        $uri = [];
        if (null === $v = $this->functionName) {
            throw new InvalidArgument(\sprintf('Missing parameter "FunctionName" for "%s". The value cannot be null.', __CLASS__));
        }
        $uri['FunctionName'] = $v;
        $uriString = '/2015-03-31/functions/' . rawurlencode($uri['FunctionName']) . '/aliases';

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

    public function setFunctionVersion(?string $value): self
    {
        $this->functionVersion = $value;

        return $this;
    }

    public function setMarker(?string $value): self
    {
        $this->marker = $value;

        return $this;
    }

    public function setMaxItems(?int $value): self
    {
        $this->maxItems = $value;

        return $this;
    }
}
