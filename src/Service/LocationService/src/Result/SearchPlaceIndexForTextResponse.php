<?php

namespace AsyncAws\LocationService\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\LocationService\ValueObject\Place;
use AsyncAws\LocationService\ValueObject\PlaceGeometry;
use AsyncAws\LocationService\ValueObject\SearchForTextResult;
use AsyncAws\LocationService\ValueObject\SearchPlaceIndexForTextSummary;
use AsyncAws\LocationService\ValueObject\TimeZone;

class SearchPlaceIndexForTextResponse extends Result
{
    /**
     * Contains a summary of the request. Echoes the input values for `BiasPosition`, `FilterBBox`, `FilterCountries`,
     * `Language`, `MaxResults`, and `Text`. Also includes the `DataSource` of the place index and the bounding box,
     * `ResultBBox`, which surrounds the search results.
     *
     * @var SearchPlaceIndexForTextSummary
     */
    private $summary;

    /**
     * A list of Places matching the input text. Each result contains additional information about the specific point of
     * interest.
     *
     * Not all response properties are included with all responses. Some properties may only be returned by specific data
     * partners.
     *
     * @var SearchForTextResult[]
     */
    private $results;

    /**
     * @return SearchForTextResult[]
     */
    public function getResults(): array
    {
        $this->initialize();

        return $this->results;
    }

    public function getSummary(): SearchPlaceIndexForTextSummary
    {
        $this->initialize();

        return $this->summary;
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->summary = $this->populateResultSearchPlaceIndexForTextSummary($data['Summary']);
        $this->results = $this->populateResultSearchForTextResultList($data['Results'] ?? []);
    }

    /**
     * @return float[]
     */
    private function populateResultBoundingBox(array $json): array
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

    /**
     * @return string[]
     */
    private function populateResultCountryCodeList(array $json): array
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
     * @return string[]
     */
    private function populateResultFilterPlaceCategoryList(array $json): array
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

    private function populateResultSearchForTextResult(array $json): SearchForTextResult
    {
        return new SearchForTextResult([
            'Place' => $this->populateResultPlace($json['Place']),
            'Distance' => isset($json['Distance']) ? (float) $json['Distance'] : null,
            'Relevance' => isset($json['Relevance']) ? (float) $json['Relevance'] : null,
            'PlaceId' => isset($json['PlaceId']) ? (string) $json['PlaceId'] : null,
        ]);
    }

    /**
     * @return SearchForTextResult[]
     */
    private function populateResultSearchForTextResultList(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = $this->populateResultSearchForTextResult($item);
        }

        return $items;
    }

    private function populateResultSearchPlaceIndexForTextSummary(array $json): SearchPlaceIndexForTextSummary
    {
        return new SearchPlaceIndexForTextSummary([
            'Text' => (string) $json['Text'],
            'BiasPosition' => !isset($json['BiasPosition']) ? null : $this->populateResultPosition($json['BiasPosition']),
            'FilterBBox' => !isset($json['FilterBBox']) ? null : $this->populateResultBoundingBox($json['FilterBBox']),
            'FilterCountries' => !isset($json['FilterCountries']) ? null : $this->populateResultCountryCodeList($json['FilterCountries']),
            'MaxResults' => isset($json['MaxResults']) ? (int) $json['MaxResults'] : null,
            'ResultBBox' => !isset($json['ResultBBox']) ? null : $this->populateResultBoundingBox($json['ResultBBox']),
            'DataSource' => (string) $json['DataSource'],
            'Language' => isset($json['Language']) ? (string) $json['Language'] : null,
            'FilterCategories' => !isset($json['FilterCategories']) ? null : $this->populateResultFilterPlaceCategoryList($json['FilterCategories']),
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
