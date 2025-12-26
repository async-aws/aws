<?php

namespace AsyncAws\LocationService\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * A summary of the request sent by using `SearchPlaceIndexForText`.
 */
final class SearchPlaceIndexForTextSummary
{
    /**
     * The search text specified in the request.
     *
     * @var string
     */
    private $text;

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
     * Contains the coordinates for the optional bounding box specified in the request.
     *
     * @var float[]|null
     */
    private $filterBBox;

    /**
     * Contains the optional country filter specified in the request.
     *
     * @var string[]|null
     */
    private $filterCountries;

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
    private $resultBBox;

    /**
     * The geospatial data provider attached to the place index resource specified in the request. Values can be one of the
     * following:
     *
     * - Esri
     * - Grab
     * - Here
     *
     * For more information about data providers, see Amazon Location Service data providers [^1].
     *
     * [^1]: https://docs.aws.amazon.com/location/previous/developerguide/what-is-data-provider.html
     *
     * @var string
     */
    private $dataSource;

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
     * The optional category filter specified in the request.
     *
     * @var string[]|null
     */
    private $filterCategories;

    /**
     * @param array{
     *   Text: string,
     *   BiasPosition?: float[]|null,
     *   FilterBBox?: float[]|null,
     *   FilterCountries?: string[]|null,
     *   MaxResults?: int|null,
     *   ResultBBox?: float[]|null,
     *   DataSource: string,
     *   Language?: string|null,
     *   FilterCategories?: string[]|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->text = $input['Text'] ?? $this->throwException(new InvalidArgument('Missing required field "Text".'));
        $this->biasPosition = $input['BiasPosition'] ?? null;
        $this->filterBBox = $input['FilterBBox'] ?? null;
        $this->filterCountries = $input['FilterCountries'] ?? null;
        $this->maxResults = $input['MaxResults'] ?? null;
        $this->resultBBox = $input['ResultBBox'] ?? null;
        $this->dataSource = $input['DataSource'] ?? $this->throwException(new InvalidArgument('Missing required field "DataSource".'));
        $this->language = $input['Language'] ?? null;
        $this->filterCategories = $input['FilterCategories'] ?? null;
    }

    /**
     * @param array{
     *   Text: string,
     *   BiasPosition?: float[]|null,
     *   FilterBBox?: float[]|null,
     *   FilterCountries?: string[]|null,
     *   MaxResults?: int|null,
     *   ResultBBox?: float[]|null,
     *   DataSource: string,
     *   Language?: string|null,
     *   FilterCategories?: string[]|null,
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
    public function getResultBBox(): array
    {
        return $this->resultBBox ?? [];
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
