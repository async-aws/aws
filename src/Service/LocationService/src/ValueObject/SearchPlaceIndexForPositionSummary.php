<?php

namespace AsyncAws\LocationService\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * A summary of the request sent by using `SearchPlaceIndexForPosition`.
 */
final class SearchPlaceIndexForPositionSummary
{
    /**
     * The position specified in the request.
     *
     * @var float[]
     */
    private $position;

    /**
     * Contains the optional result count limit that is specified in the request.
     *
     * Default value: `50`
     *
     * @var int|null
     */
    private $maxResults;

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
     * @param array{
     *   Position: float[],
     *   MaxResults?: int|null,
     *   DataSource: string,
     *   Language?: string|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->position = $input['Position'] ?? $this->throwException(new InvalidArgument('Missing required field "Position".'));
        $this->maxResults = $input['MaxResults'] ?? null;
        $this->dataSource = $input['DataSource'] ?? $this->throwException(new InvalidArgument('Missing required field "DataSource".'));
        $this->language = $input['Language'] ?? null;
    }

    /**
     * @param array{
     *   Position: float[],
     *   MaxResults?: int|null,
     *   DataSource: string,
     *   Language?: string|null,
     * }|SearchPlaceIndexForPositionSummary $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getDataSource(): string
    {
        return $this->dataSource;
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
        return $this->position;
    }

    /**
     * @return never
     */
    private function throwException(\Throwable $exception)
    {
        throw $exception;
    }
}
