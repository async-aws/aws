<?php

namespace AsyncAws\LocationService\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * Contains a search result from a position search query that is run on a place index resource.
 */
final class SearchForPositionResult
{
    /**
     * Details about the search result, such as its address and position.
     *
     * @var Place
     */
    private $place;

    /**
     * The distance in meters of a great-circle arc between the query position and the result.
     *
     * > A great-circle arc is the shortest path on a sphere, in this case the Earth. This returns the shortest distance
     * > between two locations.
     *
     * @var float
     */
    private $distance;

    /**
     * The unique identifier of the place. You can use this with the `GetPlace` operation to find the place again later.
     *
     * > For `SearchPlaceIndexForPosition` operations, the `PlaceId` is returned only by place indexes that use HERE or Grab
     * > as a data provider.
     *
     * @var string|null
     */
    private $placeId;

    /**
     * @param array{
     *   Place: Place|array,
     *   Distance: float,
     *   PlaceId?: string|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->place = isset($input['Place']) ? Place::create($input['Place']) : $this->throwException(new InvalidArgument('Missing required field "Place".'));
        $this->distance = $input['Distance'] ?? $this->throwException(new InvalidArgument('Missing required field "Distance".'));
        $this->placeId = $input['PlaceId'] ?? null;
    }

    /**
     * @param array{
     *   Place: Place|array,
     *   Distance: float,
     *   PlaceId?: string|null,
     * }|SearchForPositionResult $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getDistance(): float
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

    /**
     * @return never
     */
    private function throwException(\Throwable $exception)
    {
        throw $exception;
    }
}
