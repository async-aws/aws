<?php

namespace AsyncAws\CodeCommit\Input;

use AsyncAws\CodeCommit\Enum\OrderEnum;
use AsyncAws\CodeCommit\Enum\SortByEnum;
use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

/**
 * Represents the input of a list repositories operation.
 */
final class ListRepositoriesInput extends Input
{
    /**
     * An enumeration token that allows the operation to batch the results of the operation. Batch sizes are 1,000 for list
     * repository operations. When the client sends the token back to CodeCommit, another page of 1,000 records is
     * retrieved.
     *
     * @var string|null
     */
    private $nextToken;

    /**
     * The criteria used to sort the results of a list repositories operation.
     *
     * @var SortByEnum::*|null
     */
    private $sortBy;

    /**
     * The order in which to sort the results of a list repositories operation.
     *
     * @var OrderEnum::*|null
     */
    private $order;

    /**
     * @param array{
     *   nextToken?: string|null,
     *   sortBy?: SortByEnum::*|null,
     *   order?: OrderEnum::*|null,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->nextToken = $input['nextToken'] ?? null;
        $this->sortBy = $input['sortBy'] ?? null;
        $this->order = $input['order'] ?? null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   nextToken?: string|null,
     *   sortBy?: SortByEnum::*|null,
     *   order?: OrderEnum::*|null,
     *   '@region'?: string|null,
     * }|ListRepositoriesInput $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getNextToken(): ?string
    {
        return $this->nextToken;
    }

    /**
     * @return OrderEnum::*|null
     */
    public function getOrder(): ?string
    {
        return $this->order;
    }

    /**
     * @return SortByEnum::*|null
     */
    public function getSortBy(): ?string
    {
        return $this->sortBy;
    }

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = [
            'Content-Type' => 'application/x-amz-json-1.1',
            'X-Amz-Target' => 'CodeCommit_20150413.ListRepositories',
            'Accept' => 'application/json',
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

    public function setNextToken(?string $value): self
    {
        $this->nextToken = $value;

        return $this;
    }

    /**
     * @param OrderEnum::*|null $value
     */
    public function setOrder(?string $value): self
    {
        $this->order = $value;

        return $this;
    }

    /**
     * @param SortByEnum::*|null $value
     */
    public function setSortBy(?string $value): self
    {
        $this->sortBy = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->nextToken) {
            $payload['nextToken'] = $v;
        }
        if (null !== $v = $this->sortBy) {
            if (!SortByEnum::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "sortBy" for "%s". The value "%s" is not a valid "SortByEnum".', __CLASS__, $v));
            }
            $payload['sortBy'] = $v;
        }
        if (null !== $v = $this->order) {
            if (!OrderEnum::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "order" for "%s". The value "%s" is not a valid "OrderEnum".', __CLASS__, $v));
            }
            $payload['order'] = $v;
        }

        return $payload;
    }
}
