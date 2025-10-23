<?php

namespace AsyncAws\SecretsManager\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;
use AsyncAws\SecretsManager\Enum\SortOrderType;
use AsyncAws\SecretsManager\ValueObject\Filter;

final class ListSecretsRequest extends Input
{
    /**
     * Specifies whether to include secrets scheduled for deletion. By default, secrets scheduled for deletion aren't
     * included.
     *
     * @var bool|null
     */
    private $includePlannedDeletion;

    /**
     * The number of results to include in the response.
     *
     * If there are more results available, in the response, Secrets Manager includes `NextToken`. To get the next results,
     * call `ListSecrets` again with the value from `NextToken`.
     *
     * @var int|null
     */
    private $maxResults;

    /**
     * A token that indicates where the output should continue from, if a previous call did not show all results. To get the
     * next results, call `ListSecrets` again with this value.
     *
     * @var string|null
     */
    private $nextToken;

    /**
     * The filters to apply to the list of secrets.
     *
     * @var Filter[]|null
     */
    private $filters;

    /**
     * Secrets are listed by `CreatedDate`.
     *
     * @var SortOrderType::*|null
     */
    private $sortOrder;

    /**
     * @param array{
     *   IncludePlannedDeletion?: bool|null,
     *   MaxResults?: int|null,
     *   NextToken?: string|null,
     *   Filters?: array<Filter|array>|null,
     *   SortOrder?: SortOrderType::*|null,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->includePlannedDeletion = $input['IncludePlannedDeletion'] ?? null;
        $this->maxResults = $input['MaxResults'] ?? null;
        $this->nextToken = $input['NextToken'] ?? null;
        $this->filters = isset($input['Filters']) ? array_map([Filter::class, 'create'], $input['Filters']) : null;
        $this->sortOrder = $input['SortOrder'] ?? null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   IncludePlannedDeletion?: bool|null,
     *   MaxResults?: int|null,
     *   NextToken?: string|null,
     *   Filters?: array<Filter|array>|null,
     *   SortOrder?: SortOrderType::*|null,
     *   '@region'?: string|null,
     * }|ListSecretsRequest $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return Filter[]
     */
    public function getFilters(): array
    {
        return $this->filters ?? [];
    }

    public function getIncludePlannedDeletion(): ?bool
    {
        return $this->includePlannedDeletion;
    }

    public function getMaxResults(): ?int
    {
        return $this->maxResults;
    }

    public function getNextToken(): ?string
    {
        return $this->nextToken;
    }

    /**
     * @return SortOrderType::*|null
     */
    public function getSortOrder(): ?string
    {
        return $this->sortOrder;
    }

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = [
            'Content-Type' => 'application/x-amz-json-1.1',
            'X-Amz-Target' => 'secretsmanager.ListSecrets',
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

    /**
     * @param Filter[] $value
     */
    public function setFilters(array $value): self
    {
        $this->filters = $value;

        return $this;
    }

    public function setIncludePlannedDeletion(?bool $value): self
    {
        $this->includePlannedDeletion = $value;

        return $this;
    }

    public function setMaxResults(?int $value): self
    {
        $this->maxResults = $value;

        return $this;
    }

    public function setNextToken(?string $value): self
    {
        $this->nextToken = $value;

        return $this;
    }

    /**
     * @param SortOrderType::*|null $value
     */
    public function setSortOrder(?string $value): self
    {
        $this->sortOrder = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->includePlannedDeletion) {
            $payload['IncludePlannedDeletion'] = (bool) $v;
        }
        if (null !== $v = $this->maxResults) {
            $payload['MaxResults'] = $v;
        }
        if (null !== $v = $this->nextToken) {
            $payload['NextToken'] = $v;
        }
        if (null !== $v = $this->filters) {
            $index = -1;
            $payload['Filters'] = [];
            foreach ($v as $listValue) {
                ++$index;
                $payload['Filters'][$index] = $listValue->requestBody();
            }
        }
        if (null !== $v = $this->sortOrder) {
            if (!SortOrderType::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "SortOrder" for "%s". The value "%s" is not a valid "SortOrderType".', __CLASS__, $v));
            }
            $payload['SortOrder'] = $v;
        }

        return $payload;
    }
}
