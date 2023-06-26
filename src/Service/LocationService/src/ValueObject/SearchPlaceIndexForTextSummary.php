<?php

namespace AsyncAws\LocationService\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * A summary of the request sent by using `SearchPlaceIndexForText`.
 */
final class SearchPlaceIndexForTextSummary
{
    /**
     * Contains the coordinates for the optional bias position specified in the request.
     *
     * This parameter contains a pair of numbers. The first number represents the X coordinate, or longitude; the second
     * number represents the Y coordinate, or latitude.
     *
     * For example, `[-123.1174, 49.2847]` represents the position with longitude `-123.1174` and latitude `49.2847`.
     *
     * @var float[]|null
     */
    private $biasPosition;

    /**
     * The geospatial data provider attached to the place index resource specified in the request. Values can be one of the
     * following:.
     *
     * - Esri
     * - Grab
     * - Here
     *
     * For more information about data providers, see Amazon Location Service data providers [^1].
     *
     * [^1]: https://docs.aws.amazon.com/location/latest/developerguide/what-is-data-provider.html
     *
     * @var string
     */
    private $dataSource;

    /**
     * Contains the coordinates for the optional bounding box specified in the request.
     *
     * @var float[]|null
     */
    private $filterBbox;

    /**
     * The optional category filter specified in the request.
     *
     * @var string[]|null
     */
    private $filterCategories;

    /**
     * Contains the optional country filter specified in the request.
     *
     * @var string[]|null
     */
    private $filterCountries;

    /**
     * The preferred language used to return results. Matches the language in the request. The value is a valid BCP 47 [^1]
     * language tag, for example, `en` for English.
     *
     * [^1]: https://tools.ietf.org/search/bcp47
     *
     * @var string|null
     */
    private $language;

    /**
     * Contains the optional result count limit specified in the request.
     *
     * @var int|null
     */
    private $maxResults;

    /**
     * The bounding box that fully contains all search results.
     *
     * > If you specified the optional `FilterBBox` parameter in the request, `ResultBBox` is contained within `FilterBBox`.
     *
     * @var float[]|null
     */
    private $resultBbox;

    /**
     * The search text specified in the request.
     *
     * @var string
     */
    private $text;

    /**
     * @param array{
     *   BiasPosition?: null|float[],
     *   DataSource: string,
     *   FilterBBox?: null|float[],
     *   FilterCategories?: null|string[],
     *   FilterCountries?: null|string[],
     *   Language?: null|string,
     *   MaxResults?: null|int,
     *   ResultBBox?: null|float[],
     *   Text: string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->biasPosition = $input['BiasPosition'] ?? null;
        $this->dataSource = $input['DataSource'] ?? $this->throwException(new InvalidArgument('Missing required field "DataSource".'));
        $this->filterBbox = $input['FilterBBox'] ?? null;
        $this->filterCategories = $input['FilterCategories'] ?? null;
        $this->filterCountries = $input['FilterCountries'] ?? null;
        $this->language = $input['Language'] ?? null;
        $this->maxResults = $input['MaxResults'] ?? null;
        $this->resultBbox = $input['ResultBBox'] ?? null;
        $this->text = $input['Text'] ?? $this->throwException(new InvalidArgument('Missing required field "Text".'));
    }

    /**
     * @param array{
     *   BiasPosition?: null|float[],
     *   DataSource: string,
     *   FilterBBox?: null|float[],
     *   FilterCategories?: null|string[],
     *   FilterCountries?: null|string[],
     *   Language?: null|string,
     *   MaxResults?: null|int,
     *   ResultBBox?: null|float[],
     *   Text: string,
     * }|SearchPlaceIndexForTextSummary $input
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

    public function getDataSource(): string
    {
        return $this->dataSource;
    }

    /**
     * @return float[]
     */
    public function getFilterBbox(): array
    {
        return $this->filterBbox ?? [];
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
    public function getResultBbox(): array
    {
        return $this->resultBbox ?? [];
    }

    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @return never
     */
    private function throwException(\Throwable $exception)
    {
        throw $exception;
    }
}
