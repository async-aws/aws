<?php

namespace AsyncAws\LocationService\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\LocationService\ValueObject\Place;
use AsyncAws\LocationService\ValueObject\PlaceGeometry;
use AsyncAws\LocationService\ValueObject\SearchForPositionResult;
use AsyncAws\LocationService\ValueObject\SearchPlaceIndexForPositionSummary;
use AsyncAws\LocationService\ValueObject\TimeZone;

class SearchPlaceIndexForPositionResponse extends Result
{
    /**
     * Contains a summary of the request. Echoes the input values for `Position`, `Language`, `MaxResults`, and the
     * `DataSource` of the place index.
     *
     * @var SearchPlaceIndexForPositionSummary
     */
    private $summary;

    /**
     * Returns a list of Places closest to the specified position. Each result contains additional information about the
     * Places returned.
     *
     * @var SearchForPositionResult[]
     */
    private $results;

    /**
     * @return SearchForPositionResult[]
     */
    public function getResults(): array
    {
        $this->initialize();

        return $this->results;
    }

    public function getSummary(): SearchPlaceIndexForPositionSummary
    {
        $this->initialize();

        return $this->summary;
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->summary = $this->populateResultSearchPlaceIndexForPositionSummary($data['Summary']);
        $this->results = $this->populateResultSearchForPositionResultList($data['Results'] ?? []);
    }

    private function populateResultPlace(array $json): Place
    {
        return new Place([
            'Label' => isset($json['Label']) ? (string) $json['Label'] : null,
            'Geometry' => $this->populateResultPlaceGeometry($json['Geometry']),
            'AddressNumber' => isset($json['AddressNumber']) ? (string) $json['AddressNumber'] : null,
            'Street' => isset($json['Street']) ? (string) $json['Street'] : null,
            'Neighborhood' => isset($json['Neighborhood']) ? (string) $json['Neighborhood'] : null,
            'Municipality' => isset($json['Municipality']) ? (string) $json['Municipality'] : null,
            'SubRegion' => isset($json['SubRegion']) ? (string) $json['SubRegion'] : null,
            'Region' => isset($json['Region']) ? (string) $json['Region'] : null,
            'Country' => isset($json['Country']) ? (string) $json['Country'] : null,
            'PostalCode' => isset($json['PostalCode']) ? (string) $json['PostalCode'] : null,
            'Interpolated' => isset($json['Interpolated']) ? filter_var($json['Interpolated'], \FILTER_VALIDATE_BOOLEAN) : null,
            'TimeZone' => empty($json['TimeZone']) ? null : $this->populateResultTimeZone($json['TimeZone']),
            'UnitType' => isset($json['UnitType']) ? (string) $json['UnitType'] : null,
            'UnitNumber' => isset($json['UnitNumber']) ? (string) $json['UnitNumber'] : null,
            'Categories' => !isset($json['Categories']) ? null : $this->populateResultPlaceCategoryList($json['Categories']),
            'SupplementalCategories' => !isset($json['SupplementalCategories']) ? null : $this->populateResultPlaceSupplementalCategoryList($json['SupplementalCategories']),
            'SubMunicipality' => isset($json['SubMunicipality']) ? (string) $json['SubMunicipality'] : null,
        ]);
    }

    /**
     * @return string[]
     */
    private function populateResultPlaceCategoryList(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $a = isset($item) ? (string) $item : null;
            if (null !== $a) {
                $items[] = $a;
            }
        }

        return $items;
    }

    private function populateResultPlaceGeometry(array $json): PlaceGeometry
    {
        return new PlaceGeometry([
            'Point' => !isset($json['Point']) ? null : $this->populateResultPosition($json['Point']),
        ]);
    }

    /**
     * @return string[]
     */
    private function populateResultPlaceSupplementalCategoryList(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $a = isset($item) ? (string) $item : null;
            if (null !== $a) {
                $items[] = $a;
            }
        }

        return $items;
    }

    /**
     * @return float[]
     */
    private function populateResultPosition(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $a = isset($item) ? (float) $item : null;
            if (null !== $a) {
                $items[] = $a;
            }
        }

        return $items;
    }

    private function populateResultSearchForPositionResult(array $json): SearchForPositionResult
    {
        return new SearchForPositionResult([
            'Place' => $this->populateResultPlace($json['Place']),
            'Distance' => (float) $json['Distance'],
            'PlaceId' => isset($json['PlaceId']) ? (string) $json['PlaceId'] : null,
        ]);
    }

    /**
     * @return SearchForPositionResult[]
     */
    private function populateResultSearchForPositionResultList(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = $this->populateResultSearchForPositionResult($item);
        }

        return $items;
    }

    private function populateResultSearchPlaceIndexForPositionSummary(array $json): SearchPlaceIndexForPositionSummary
    {
        return new SearchPlaceIndexForPositionSummary([
            'Position' => $this->populateResultPosition($json['Position']),
            'MaxResults' => isset($json['MaxResults']) ? (int) $json['MaxResults'] : null,
            'DataSource' => (string) $json['DataSource'],
            'Language' => isset($json['Language']) ? (string) $json['Language'] : null,
        ]);
    }

    private function populateResultTimeZone(array $json): TimeZone
    {
        return new TimeZone([
            'Name' => (string) $json['Name'],
            'Offset' => isset($json['Offset']) ? (int) $json['Offset'] : null,
        ]);
    }
}
