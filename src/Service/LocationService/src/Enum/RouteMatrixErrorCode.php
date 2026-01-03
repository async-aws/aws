<?php

namespace AsyncAws\LocationService\Enum;

final class RouteMatrixErrorCode
{
    public const DEPARTURE_POSITION_NOT_FOUND = 'DeparturePositionNotFound';
    public const DESTINATION_POSITION_NOT_FOUND = 'DestinationPositionNotFound';
    public const OTHER_VALIDATION_ERROR = 'OtherValidationError';
    public const POSITIONS_NOT_FOUND = 'PositionsNotFound';
    public const ROUTE_NOT_FOUND = 'RouteNotFound';
    public const ROUTE_TOO_LONG = 'RouteTooLong';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::DEPARTURE_POSITION_NOT_FOUND => true,
            self::DESTINATION_POSITION_NOT_FOUND => true,
            self::OTHER_VALIDATION_ERROR => true,
            self::POSITIONS_NOT_FOUND => true,
            self::ROUTE_NOT_FOUND => true,
            self::ROUTE_TOO_LONG => true,
        ][$value]);
    }
}
