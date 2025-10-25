<?php

namespace AsyncAws\LocationService;

use AsyncAws\Core\AbstractApi;
use AsyncAws\Core\AwsError\AwsErrorFactoryInterface;
use AsyncAws\Core\AwsError\JsonRestAwsErrorFactory;
use AsyncAws\Core\Configuration;
use AsyncAws\Core\RequestContext;
use AsyncAws\LocationService\Enum\DistanceUnit;
use AsyncAws\LocationService\Enum\OptimizationMode;
use AsyncAws\LocationService\Enum\TravelMode;
use AsyncAws\LocationService\Exception\AccessDeniedException;
use AsyncAws\LocationService\Exception\InternalServerException;
use AsyncAws\LocationService\Exception\ResourceNotFoundException;
use AsyncAws\LocationService\Exception\ThrottlingException;
use AsyncAws\LocationService\Exception\ValidationException;
use AsyncAws\LocationService\Input\CalculateRouteMatrixRequest;
use AsyncAws\LocationService\Input\CalculateRouteRequest;
use AsyncAws\LocationService\Input\SearchPlaceIndexForPositionRequest;
use AsyncAws\LocationService\Input\SearchPlaceIndexForTextRequest;
use AsyncAws\LocationService\Result\CalculateRouteMatrixResponse;
use AsyncAws\LocationService\Result\CalculateRouteResponse;
use AsyncAws\LocationService\Result\SearchPlaceIndexForPositionResponse;
use AsyncAws\LocationService\Result\SearchPlaceIndexForTextResponse;
use AsyncAws\LocationService\ValueObject\CalculateRouteCarModeOptions;
use AsyncAws\LocationService\ValueObject\CalculateRouteTruckModeOptions;

class LocationServiceClient extends AbstractApi
{
    /**
     * ! This operation is no longer current and may be deprecated in the future. We recommend you upgrade to
     * ! `CalculateRoutes` [^1] or `CalculateIsolines` [^2] unless you require Grab data.
     * !
     * ! - `CalculateRoute` is part of a previous Amazon Location Service Routes API (version 1) which has been superseded
     * !   by a more intuitive, powerful, and complete API (version 2).
     * ! - The version 2 `CalculateRoutes` operation gives better results for point-to-point routing, while the version 2
     * !   `CalculateIsolines` operation adds support for calculating service areas and travel time envelopes.
     * ! - If you are using an Amazon Web Services SDK or the Amazon Web Services CLI, note that the Routes API version 2 is
     * !   found under `geo-routes` or `geo_routes`, not under `location`.
     * ! - Since Grab is not yet fully supported in Routes API version 2, we recommend you continue using API version 1 when
     * !   using Grab.
     * !
     *
     * Calculates a route [^3] given the following required parameters: `DeparturePosition` and `DestinationPosition`.
     * Requires that you first create a route calculator resource [^4].
     *
     * By default, a request that doesn't specify a departure time uses the best time of day to travel with the best traffic
     * conditions when calculating the route.
     *
     * Additional options include:
     *
     * - Specifying a departure time [^5] using either `DepartureTime` or `DepartNow`. This calculates a route based on
     *   predictive traffic data at the given time.
     *
     *   > You can't specify both `DepartureTime` and `DepartNow` in a single request. Specifying both parameters returns a
     *   > validation error.
     *
     * - Specifying a travel mode [^6] using TravelMode sets the transportation mode used to calculate the routes. This also
     *   lets you specify additional route preferences in `CarModeOptions` if traveling by `Car`, or `TruckModeOptions` if
     *   traveling by `Truck`.
     *
     *   > If you specify `walking` for the travel mode and your data provider is Esri, the start and destination must be
     *   > within 40km.
     *
     * [^1]: /location/latest/APIReference/API_CalculateRoutes.html
     * [^2]: /location/latest/APIReference/API_CalculateIsolines.html
     * [^3]: https://docs.aws.amazon.com/location/previous/developerguide/calculate-route.html
     * [^4]: https://docs.aws.amazon.com/location-routes/latest/APIReference/API_CreateRouteCalculator.html
     * [^5]: https://docs.aws.amazon.com/location/previous/developerguide/departure-time.html
     * [^6]: https://docs.aws.amazon.com/location/previous/developerguide/travel-mode.html
     *
     * @see https://docs.aws.amazon.com/location/latest/APIReference/API_CalculateRoute.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-geo-2020-11-19.html#calculateroute
     *
     * @param array{
     *   CalculatorName: string,
     *   DeparturePosition: float[],
     *   DestinationPosition: float[],
     *   WaypointPositions?: array[]|null,
     *   TravelMode?: TravelMode::*|null,
     *   DepartureTime?: \DateTimeImmutable|string|null,
     *   DepartNow?: bool|null,
     *   DistanceUnit?: DistanceUnit::*|null,
     *   IncludeLegGeometry?: bool|null,
     *   CarModeOptions?: CalculateRouteCarModeOptions|array|null,
     *   TruckModeOptions?: CalculateRouteTruckModeOptions|array|null,
     *   ArrivalTime?: \DateTimeImmutable|string|null,
     *   OptimizeFor?: OptimizationMode::*|null,
     *   Key?: string|null,
     *   '@region'?: string|null,
     * }|CalculateRouteRequest $input
     *
     * @throws AccessDeniedException
     * @throws InternalServerException
     * @throws ResourceNotFoundException
     * @throws ThrottlingException
     * @throws ValidationException
     */
    public function calculateRoute($input): CalculateRouteResponse
    {
        $input = CalculateRouteRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'CalculateRoute', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'AccessDeniedException' => AccessDeniedException::class,
            'InternalServerException' => InternalServerException::class,
            'ResourceNotFoundException' => ResourceNotFoundException::class,
            'ThrottlingException' => ThrottlingException::class,
            'ValidationException' => ValidationException::class,
        ]]));

        return new CalculateRouteResponse($response);
    }

    /**
     * ! This operation is no longer current and may be deprecated in the future. We recommend you upgrade to the V2
     * ! `CalculateRouteMatrix` [^1] unless you require Grab data.
     * !
     * ! - This version of `CalculateRouteMatrix` is part of a previous Amazon Location Service Routes API (version 1) which
     * !   has been superseded by a more intuitive, powerful, and complete API (version 2).
     * ! - The version 2 `CalculateRouteMatrix` operation gives better results for matrix routing calculations.
     * ! - If you are using an Amazon Web Services SDK or the Amazon Web Services CLI, note that the Routes API version 2 is
     * !   found under `geo-routes` or `geo_routes`, not under `location`.
     * ! - Since Grab is not yet fully supported in Routes API version 2, we recommend you continue using API version 1 when
     * !   using Grab.
     * ! - Start your version 2 API journey with the Routes V2 API Reference [^2] or the Developer Guide [^3].
     * !
     *
     * Calculates a route matrix [^4] given the following required parameters: `DeparturePositions` and
     * `DestinationPositions`. `CalculateRouteMatrix` calculates routes and returns the travel time and travel distance from
     * each departure position to each destination position in the request. For example, given departure positions A and B,
     * and destination positions X and Y, `CalculateRouteMatrix` will return time and distance for routes from A to X, A to
     * Y, B to X, and B to Y (in that order). The number of results returned (and routes calculated) will be the number of
     * `DeparturePositions` times the number of `DestinationPositions`.
     *
     * > Your account is charged for each route calculated, not the number of requests.
     *
     * Requires that you first create a route calculator resource [^5].
     *
     * By default, a request that doesn't specify a departure time uses the best time of day to travel with the best traffic
     * conditions when calculating routes.
     *
     * Additional options include:
     *
     * - Specifying a departure time [^6] using either `DepartureTime` or `DepartNow`. This calculates routes based on
     *   predictive traffic data at the given time.
     *
     *   > You can't specify both `DepartureTime` and `DepartNow` in a single request. Specifying both parameters returns a
     *   > validation error.
     *
     * - Specifying a travel mode [^7] using TravelMode sets the transportation mode used to calculate the routes. This also
     *   lets you specify additional route preferences in `CarModeOptions` if traveling by `Car`, or `TruckModeOptions` if
     *   traveling by `Truck`.
     *
     * [^1]: /location/latest/APIReference/API_CalculateRouteMatrix.html
     * [^2]: /location/latest/APIReference/API_Operations_Amazon_Location_Service_Routes_V2.html
     * [^3]: /location/latest/developerguide/routes.html
     * [^4]: https://docs.aws.amazon.com/location/previous/developerguide/calculate-route-matrix.html
     * [^5]: https://docs.aws.amazon.com/location-routes/latest/APIReference/API_CreateRouteCalculator.html
     * [^6]: https://docs.aws.amazon.com/location/previous/developerguide/departure-time.html
     * [^7]: https://docs.aws.amazon.com/location/previous/developerguide/travel-mode.html
     *
     * @see https://docs.aws.amazon.com/location/latest/APIReference/API_CalculateRouteMatrix.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-geo-2020-11-19.html#calculateroutematrix
     *
     * @param array{
     *   CalculatorName: string,
     *   DeparturePositions: array[],
     *   DestinationPositions: array[],
     *   TravelMode?: TravelMode::*|null,
     *   DepartureTime?: \DateTimeImmutable|string|null,
     *   DepartNow?: bool|null,
     *   DistanceUnit?: DistanceUnit::*|null,
     *   CarModeOptions?: CalculateRouteCarModeOptions|array|null,
     *   TruckModeOptions?: CalculateRouteTruckModeOptions|array|null,
     *   Key?: string|null,
     *   '@region'?: string|null,
     * }|CalculateRouteMatrixRequest $input
     *
     * @throws AccessDeniedException
     * @throws InternalServerException
     * @throws ResourceNotFoundException
     * @throws ThrottlingException
     * @throws ValidationException
     */
    public function calculateRouteMatrix($input): CalculateRouteMatrixResponse
    {
        $input = CalculateRouteMatrixRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'CalculateRouteMatrix', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'AccessDeniedException' => AccessDeniedException::class,
            'InternalServerException' => InternalServerException::class,
            'ResourceNotFoundException' => ResourceNotFoundException::class,
            'ThrottlingException' => ThrottlingException::class,
            'ValidationException' => ValidationException::class,
        ]]));

        return new CalculateRouteMatrixResponse($response);
    }

    /**
     * ! This operation is no longer current and may be deprecated in the future. We recommend you upgrade to
     * ! `ReverseGeocode` [^1] or `SearchNearby` [^2] unless you require Grab data.
     * !
     * ! - `SearchPlaceIndexForPosition` is part of a previous Amazon Location Service Places API (version 1) which has been
     * !   superseded by a more intuitive, powerful, and complete API (version 2).
     * ! - The version 2 `ReverseGeocode` operation gives better results in the address reverse-geocoding use case, while
     * !   the version 2 `SearchNearby` operation gives better results when searching for businesses and points of interest
     * !   near a specific location.
     * ! - If you are using an Amazon Web Services SDK or the Amazon Web Services CLI, note that the Places API version 2 is
     * !   found under `geo-places` or `geo_places`, not under `location`.
     * ! - Since Grab is not yet fully supported in Places API version 2, we recommend you continue using API version 1 when
     * !   using Grab.
     * !
     *
     * Reverse geocodes a given coordinate and returns a legible address. Allows you to search for Places or points of
     * interest near a given position.
     *
     * [^1]: /location/latest/APIReference/API_geoplaces_ReverseGeocode.html
     * [^2]: /location/latest/APIReference/API_geoplaces_SearchNearby.html
     *
     * @see https://docs.aws.amazon.com/location/latest/APIReference/API_SearchPlaceIndexForPosition.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-geo-2020-11-19.html#searchplaceindexforposition
     *
     * @param array{
     *   IndexName: string,
     *   Position: float[],
     *   MaxResults?: int|null,
     *   Language?: string|null,
     *   Key?: string|null,
     *   '@region'?: string|null,
     * }|SearchPlaceIndexForPositionRequest $input
     *
     * @throws AccessDeniedException
     * @throws InternalServerException
     * @throws ResourceNotFoundException
     * @throws ThrottlingException
     * @throws ValidationException
     */
    public function searchPlaceIndexForPosition($input): SearchPlaceIndexForPositionResponse
    {
        $input = SearchPlaceIndexForPositionRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'SearchPlaceIndexForPosition', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'AccessDeniedException' => AccessDeniedException::class,
            'InternalServerException' => InternalServerException::class,
            'ResourceNotFoundException' => ResourceNotFoundException::class,
            'ThrottlingException' => ThrottlingException::class,
            'ValidationException' => ValidationException::class,
        ]]));

        return new SearchPlaceIndexForPositionResponse($response);
    }

    /**
     * ! This operation is no longer current and may be deprecated in the future. We recommend you upgrade to `Geocode` [^1]
     * ! or `SearchText` [^2] unless you require Grab data.
     * !
     * ! - `SearchPlaceIndexForText` is part of a previous Amazon Location Service Places API (version 1) which has been
     * !   superseded by a more intuitive, powerful, and complete API (version 2).
     * ! - The version 2 `Geocode` operation gives better results in the address geocoding use case, while the version 2
     * !   `SearchText` operation gives better results when searching for businesses and points of interest.
     * ! - If you are using an Amazon Web Services SDK or the Amazon Web Services CLI, note that the Places API version 2 is
     * !   found under `geo-places` or `geo_places`, not under `location`.
     * ! - Since Grab is not yet fully supported in Places API version 2, we recommend you continue using API version 1 when
     * !   using Grab.
     * !
     *
     * Geocodes free-form text, such as an address, name, city, or region to allow you to search for Places or points of
     * interest.
     *
     * Optional parameters let you narrow your search results by bounding box or country, or bias your search toward a
     * specific position on the globe.
     *
     * > You can search for places near a given position using `BiasPosition`, or filter results within a bounding box using
     * > `FilterBBox`. Providing both parameters simultaneously returns an error.
     *
     * Search results are returned in order of highest to lowest relevance.
     *
     * [^1]: /location/latest/APIReference/API_geoplaces_Geocode.html
     * [^2]: /location/latest/APIReference/API_geoplaces_SearchText.html
     *
     * @see https://docs.aws.amazon.com/location/latest/APIReference/API_SearchPlaceIndexForText.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-geo-2020-11-19.html#searchplaceindexfortext
     *
     * @param array{
     *   IndexName: string,
     *   Text: string,
     *   BiasPosition?: float[]|null,
     *   FilterBBox?: float[]|null,
     *   FilterCountries?: string[]|null,
     *   MaxResults?: int|null,
     *   Language?: string|null,
     *   FilterCategories?: string[]|null,
     *   Key?: string|null,
     *   '@region'?: string|null,
     * }|SearchPlaceIndexForTextRequest $input
     *
     * @throws AccessDeniedException
     * @throws InternalServerException
     * @throws ResourceNotFoundException
     * @throws ThrottlingException
     * @throws ValidationException
     */
    public function searchPlaceIndexForText($input): SearchPlaceIndexForTextResponse
    {
        $input = SearchPlaceIndexForTextRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'SearchPlaceIndexForText', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'AccessDeniedException' => AccessDeniedException::class,
            'InternalServerException' => InternalServerException::class,
            'ResourceNotFoundException' => ResourceNotFoundException::class,
            'ThrottlingException' => ThrottlingException::class,
            'ValidationException' => ValidationException::class,
        ]]));

        return new SearchPlaceIndexForTextResponse($response);
    }

    protected function getAwsErrorFactory(): AwsErrorFactoryInterface
    {
        return new JsonRestAwsErrorFactory();
    }

    protected function getEndpointMetadata(?string $region): array
    {
        if (null === $region) {
            $region = Configuration::DEFAULT_REGION;
        }

        switch ($region) {
            case 'fips-us-gov-west-1':
                return [
                    'endpoint' => 'https://geo-fips.us-gov-west-1.amazonaws.com',
                    'signRegion' => 'us-gov-west-1',
                    'signService' => 'geo',
                    'signVersions' => ['v4'],
                ];
        }

        return [
            'endpoint' => "https://geo.$region.amazonaws.com",
            'signRegion' => $region,
            'signService' => 'geo',
            'signVersions' => ['v4'],
        ];
    }
}
