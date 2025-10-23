<?php

namespace AsyncAws\LocationService\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

final class SearchPlaceIndexForPositionRequest extends Input
{
    /**
     * The name of the place index resource you want to use for the search.
     *
     * @required
     *
     * @var string|null
     */
    private $indexName;

    /**
     * Specifies the longitude and latitude of the position to query.
     *
     * This parameter must contain a pair of numbers. The first number represents the X coordinate, or longitude; the second
     * number represents the Y coordinate, or latitude.
     *
     * For example, `[-123.1174, 49.2847]` represents a position with longitude `-123.1174` and latitude `49.2847`.
     *
     * @required
     *
     * @var float[]|null
     */
    private $position;

    /**
     * An optional parameter. The maximum number of results returned per request.
     *
     * Default value: `50`
     *
     * @var int|null
     */
    private $maxResults;

    /**
     * The preferred language used to return results. The value must be a valid BCP 47 [^1] language tag, for example, `en`
     * for English.
     *
     * This setting affects the languages used in the results, but not the results themselves. If no language is specified,
     * or not supported for a particular result, the partner automatically chooses a language for the result.
     *
     * For an example, we'll use the Greek language. You search for a location around Athens, Greece, with the `language`
     * parameter set to `en`. The `city` in the results will most likely be returned as `Athens`.
     *
     * If you set the `language` parameter to `el`, for Greek, then the `city` in the results will more likely be returned
     * as `Αθήνα`.
     *
     * If the data provider does not have a value for Greek, the result will be in a language that the provider does
     * support.
     *
     * [^1]: https://tools.ietf.org/search/bcp47
     *
     * @var string|null
     */
    private $language;

    /**
     * The optional API key [^1] to authorize the request.
     *
     * [^1]: https://docs.aws.amazon.com/location/previous/developerguide/using-apikeys.html
     *
     * @var string|null
     */
    private $key;

    /**
     * @param array{
     *   IndexName?: string,
     *   Position?: float[],
     *   MaxResults?: int|null,
     *   Language?: string|null,
     *   Key?: string|null,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->indexName = $input['IndexName'] ?? null;
        $this->position = $input['Position'] ?? null;
        $this->maxResults = $input['MaxResults'] ?? null;
        $this->language = $input['Language'] ?? null;
        $this->key = $input['Key'] ?? null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   IndexName?: string,
     *   Position?: float[],
     *   MaxResults?: int|null,
     *   Language?: string|null,
     *   Key?: string|null,
     *   '@region'?: string|null,
     * }|SearchPlaceIndexForPositionRequest $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getIndexName(): ?string
    {
        return $this->indexName;
    }

    public function getKey(): ?string
    {
        return $this->key;
    }

    public function getLanguage(): ?string
    {
        return $this->language;
    }

    public function getMaxResults(): ?int
    {
        return $this->maxResults;
    }

    /**
     * @return float[]
     */
    public function getPosition(): array
    {
        return $this->position ?? [];
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
        if (null !== $this->key) {
            $query['key'] = $this->key;
        }

        // Prepare URI
        $uri = [];
        if (null === $v = $this->indexName) {
            throw new InvalidArgument(\sprintf('Missing parameter "IndexName" for "%s". The value cannot be null.', __CLASS__));
        }
        $uri['IndexName'] = $v;
        $uriString = '/places/v0/indexes/' . rawurlencode($uri['IndexName']) . '/search/position';

        // Prepare Body
        $bodyPayload = $this->requestBody();
        $body = empty($bodyPayload) ? '{}' : json_encode($bodyPayload, 4194304);

        // Return the Request
        return new Request('POST', $uriString, $query, $headers, StreamFactory::create($body), 'places.');
    }

    public function setIndexName(?string $value): self
    {
        $this->indexName = $value;

        return $this;
    }

    public function setKey(?string $value): self
    {
        $this->key = $value;

        return $this;
    }

    public function setLanguage(?string $value): self
    {
        $this->language = $value;

        return $this;
    }

    public function setMaxResults(?int $value): self
    {
        $this->maxResults = $value;

        return $this;
    }

    /**
     * @param float[] $value
     */
    public function setPosition(array $value): self
    {
        $this->position = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];

        if (null === $v = $this->position) {
            throw new InvalidArgument(\sprintf('Missing parameter "Position" for "%s". The value cannot be null.', __CLASS__));
        }

        $index = -1;
        $payload['Position'] = [];
        foreach ($v as $listValue) {
            ++$index;
            $payload['Position'][$index] = $listValue;
        }

        if (null !== $v = $this->maxResults) {
            $payload['MaxResults'] = $v;
        }
        if (null !== $v = $this->language) {
            $payload['Language'] = $v;
        }

        return $payload;
    }
}
