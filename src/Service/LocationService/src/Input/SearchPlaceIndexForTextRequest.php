<?php

namespace AsyncAws\LocationService\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

final class SearchPlaceIndexForTextRequest extends Input
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
     * The address, name, city, or region to be used in the search in free-form text format. For example, `123 Any Street`.
     *
     * @required
     *
     * @var string|null
     */
    private $text;

    /**
     * An optional parameter that indicates a preference for places that are closer to a specified position.
     *
     * If provided, this parameter must contain a pair of numbers. The first number represents the X coordinate, or
     * longitude; the second number represents the Y coordinate, or latitude.
     *
     * For example, `[-123.1174, 49.2847]` represents the position with longitude `-123.1174` and latitude `49.2847`.
     *
     * > `BiasPosition` and `FilterBBox` are mutually exclusive. Specifying both options results in an error.
     *
     * @var float[]|null
     */
    private $biasPosition;

    /**
     * An optional parameter that limits the search results by returning only places that are within the provided bounding
     * box.
     *
     * If provided, this parameter must contain a total of four consecutive numbers in two pairs. The first pair of numbers
     * represents the X and Y coordinates (longitude and latitude, respectively) of the southwest corner of the bounding
     * box; the second pair of numbers represents the X and Y coordinates (longitude and latitude, respectively) of the
     * northeast corner of the bounding box.
     *
     * For example, `[-12.7935, -37.4835, -12.0684, -36.9542]` represents a bounding box where the southwest corner has
     * longitude `-12.7935` and latitude `-37.4835`, and the northeast corner has longitude `-12.0684` and latitude
     * `-36.9542`.
     *
     * > `FilterBBox` and `BiasPosition` are mutually exclusive. Specifying both options results in an error.
     *
     * @var float[]|null
     */
    private $filterBBox;

    /**
     * An optional parameter that limits the search results by returning only places that are in a specified list of
     * countries.
     *
     * - Valid values include ISO 3166 [^1] 3-digit country codes. For example, Australia uses three upper-case characters:
     *   `AUS`.
     *
     * [^1]: https://www.iso.org/iso-3166-country-codes.html
     *
     * @var string[]|null
     */
    private $filterCountries;

    /**
     * An optional parameter. The maximum number of results returned per request.
     *
     * The default: `50`
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
     * For an example, we'll use the Greek language. You search for `Athens, Greece`, with the `language` parameter set to
     * `en`. The result found will most likely be returned as `Athens`.
     *
     * If you set the `language` parameter to `el`, for Greek, then the result found will more likely be returned as
     * `Αθήνα`.
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
     * A list of one or more Amazon Location categories to filter the returned places. If you include more than one
     * category, the results will include results that match *any* of the categories listed.
     *
     * For more information about using categories, including a list of Amazon Location categories, see Categories and
     * filtering [^1], in the *Amazon Location Service developer guide*.
     *
     * [^1]: https://docs.aws.amazon.com/location/previous/developerguide/category-filtering.html
     *
     * @var string[]|null
     */
    private $filterCategories;

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
     *   Text?: string,
     *   BiasPosition?: float[]|null,
     *   FilterBBox?: float[]|null,
     *   FilterCountries?: string[]|null,
     *   MaxResults?: int|null,
     *   Language?: string|null,
     *   FilterCategories?: string[]|null,
     *   Key?: string|null,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->indexName = $input['IndexName'] ?? null;
        $this->text = $input['Text'] ?? null;
        $this->biasPosition = $input['BiasPosition'] ?? null;
        $this->filterBBox = $input['FilterBBox'] ?? null;
        $this->filterCountries = $input['FilterCountries'] ?? null;
        $this->maxResults = $input['MaxResults'] ?? null;
        $this->language = $input['Language'] ?? null;
        $this->filterCategories = $input['FilterCategories'] ?? null;
        $this->key = $input['Key'] ?? null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   IndexName?: string,
     *   Text?: string,
     *   BiasPosition?: float[]|null,
     *   FilterBBox?: float[]|null,
     *   FilterCountries?: string[]|null,
     *   MaxResults?: int|null,
     *   Language?: string|null,
     *   FilterCategories?: string[]|null,
     *   Key?: string|null,
     *   '@region'?: string|null,
     * }|SearchPlaceIndexForTextRequest $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return float[]
     */
    public function getBiasPosition(): array
    {
        return $this->biasPosition ?? [];
    }

    /**
     * @return float[]
     */
    public function getFilterBBox(): array
    {
        return $this->filterBBox ?? [];
    }

    /**
     * @return string[]
     */
    public function getFilterCategories(): array
    {
        return $this->filterCategories ?? [];
    }

    /**
     * @return string[]
     */
    public function getFilterCountries(): array
    {
        return $this->filterCountries ?? [];
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

    public function getText(): ?string
    {
        return $this->text;
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
        $uriString = '/places/v0/indexes/' . rawurlencode($uri['IndexName']) . '/search/text';

        // Prepare Body
        $bodyPayload = $this->requestBody();
        $body = empty($bodyPayload) ? '{}' : json_encode($bodyPayload, 4194304);

        // Return the Request
        return new Request('POST', $uriString, $query, $headers, StreamFactory::create($body), 'places.');
    }

    /**
     * @param float[] $value
     */
    public function setBiasPosition(array $value): self
    {
        $this->biasPosition = $value;

        return $this;
    }

    /**
     * @param float[] $value
     */
    public function setFilterBBox(array $value): self
    {
        $this->filterBBox = $value;

        return $this;
    }

    /**
     * @param string[] $value
     */
    public function setFilterCategories(array $value): self
    {
        $this->filterCategories = $value;

        return $this;
    }

    /**
     * @param string[] $value
     */
    public function setFilterCountries(array $value): self
    {
        $this->filterCountries = $value;

        return $this;
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

    public function setText(?string $value): self
    {
        $this->text = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];

        if (null === $v = $this->text) {
            throw new InvalidArgument(\sprintf('Missing parameter "Text" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['Text'] = $v;
        if (null !== $v = $this->biasPosition) {
            $index = -1;
            $payload['BiasPosition'] = [];
            foreach ($v as $listValue) {
                ++$index;
                $payload['BiasPosition'][$index] = $listValue;
            }
        }
        if (null !== $v = $this->filterBBox) {
            $index = -1;
            $payload['FilterBBox'] = [];
            foreach ($v as $listValue) {
                ++$index;
                $payload['FilterBBox'][$index] = $listValue;
            }
        }
        if (null !== $v = $this->filterCountries) {
            $index = -1;
            $payload['FilterCountries'] = [];
            foreach ($v as $listValue) {
                ++$index;
                $payload['FilterCountries'][$index] = $listValue;
            }
        }
        if (null !== $v = $this->maxResults) {
            $payload['MaxResults'] = $v;
        }
        if (null !== $v = $this->language) {
            $payload['Language'] = $v;
        }
        if (null !== $v = $this->filterCategories) {
            $index = -1;
            $payload['FilterCategories'] = [];
            foreach ($v as $listValue) {
                ++$index;
                $payload['FilterCategories'][$index] = $listValue;
            }
        }

        return $payload;
    }
}
