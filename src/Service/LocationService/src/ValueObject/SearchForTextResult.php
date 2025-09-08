<?php

namespace AsyncAws\LocationService\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * Contains a search result from a text search query that is run on a place index resource.
 */
final class SearchForTextResult
{
    /**
     * Details about the search result, such as its address and position.
     *
     * @var Place
     */
    private $place;

    /**
     * The distance in meters of a great-circle arc between the bias position specified and the result. `Distance` will be
     * returned only if a bias position was specified in the query.
     *
     * > A great-circle arc is the shortest path on a sphere, in this case the Earth. This returns the shortest distance
     * > between two locations.
     *
     * @var float|null
     */
    private $distance;

    /**
     * The relative confidence in the match for a result among the results returned. For example, if more fields for an
     * address match (including house number, street, city, country/region, and postal code), the relevance score is closer
     * to 1.
     *
     * Returned only when the partner selected is Esri or Grab.
     *
     * @var float|null
     */
    private $relevance;

    /**
     * The unique identifier of the place. You can use this with the `GetPlace` operation to find the place again later.
     *
     * > For `SearchPlaceIndexForText` operations, the `PlaceId` is returned only by place indexes that use HERE or Grab as
     * > a data provider.
     *
     * @var string|null
     */
    private $placeId;

    /**
     * @param array{
     *   Place: Place|array,
     *   Distance?: float|null,
     *   Relevance?: float|null,
     *   PlaceId?: string|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->place = isset($input['Place']) ? Place::create($input['Place']) : $this->throwException(new InvalidArgument('Missing required field "Place".'));
        $this->distance = $input['Distance'] ?? null;
        $this->relevance = $input['Relevance'] ?? null;
        $this->placeId = $input['PlaceId'] ?? null;
    }

    /**
     * @param array{
     *   Place: Place|array,
     *   Distance?: float|null,
     *   Relevance?: float|null,
     *   PlaceId?: string|null,
     * }|SearchForTextResult $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getDistance(): ?float
    {
        return $this->distance;
    }

    public function getPlace(): Place
    {
        return $this->place;
    }

    public function getPlaceId(): ?string
    {
        return $this->placeId;
    }

    public function getRelevance(): ?float
    {
        return $this->relevance;
    }

    /**
     * @return never
     */
    private function throwException(\Throwable $exception)
    {
        throw $exception;
    }
}
