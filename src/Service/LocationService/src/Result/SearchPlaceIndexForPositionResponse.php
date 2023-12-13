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
     * Returns a list of Places closest to the specified position. Each result contains additional information about the
     * Places returned.
     *
     * @var SearchForPositionResult[]
     */
    private $results;

    /**
     * Contains a summary of the request. Echoes the input values for `Position`, `Language`, `MaxResults`, and the
     * `DataSource` of the place index.
     *
     * @var SearchPlaceIndexForPositionSummary
     */
    private $summary;

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

        $this->results = $this->populateResultSearchForPositionResultList($data['Results'] ?? []);
        $this->summary = $this->populateResultSearchPlaceIndexForPositionSummary($data['Summary']);
    }

    private function populateResultPlace(array $json): Place
    {
        return new Place([
            'AddressNumber' => isset($json['AddressNumber']) ? (string) $json['AddressNumber'] : null,
            'Categories' => !isset($json['Categories']) ? null : $this->populateResultPlaceCategoryList($json['Categories']),
            'Country' => isset($json['Country']) ? (string) $json['Country'] : null,
            'Geometry' => $this->populateResultPlaceGeometry($json['Geometry']),
            'Interpolated' => isset($json['Interpolated']) ? filter_var($json['Interpolated'], \FILTER_VALIDATE_BOOLEAN) : null,
            'Label' => isset($json['Label']) ? (string) $json['Label'] : null,
            'Municipality' => isset($json['Municipality']) ? (string) $json['Municipality'] : null,
            'Neighborhood' => isset($json['Neighborhood']) ? (string) $json['Neighborhood'] : null,
            'PostalCode' => isset($json['PostalCode']) ? (string) $json['PostalCode'] : null,
            'Region' => isset($json['Region']) ? (string) $json['Region'] : null,
            'Street' => isset($json['Street']) ? (string) $json['Street'] : null,
            'SubMunicipality' => isset($json['SubMunicipality']) ? (string) $json['SubMunicipality'] : null,
            'SubRegion' => isset($json['SubRegion']) ? (string) $json['SubRegion'] : null,
            'SupplementalCategories' => !isset($json['SupplementalCategories']) ? null : $this->populateResultPlaceSupplementalCategoryList($json['SupplementalCategories']),
            'TimeZone' => empty($json['TimeZone']) ? null : $this->populateResultTimeZone($json['TimeZone']),
            'UnitNumber' => isset($json['UnitNumber']) ? (string) $json['UnitNumber'] : null,
            'UnitType' => isset($json['UnitType']) ? (string) $json['UnitType'] : null,
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
            'Distance' => (float) $json['Distance'],
            'Place' => $this->populateResultPlace($json['Place']),
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
            'DataSource' => (string) $json['DataSource'],
            'Language' => isset($json['Language']) ? (string) $json['Language'] : null,
            'MaxResults' => isset($json['MaxResults']) ? (int) $json['MaxResults'] : null,
            'Position' => $this->populateResultPosition($json['Position']),
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
