<?php

namespace AsyncAws\LocationService\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\LocationService\Enum\RouteMatrixErrorCode;

/**
 * An error corresponding to the calculation of a route between the `DeparturePosition` and `DestinationPosition`.
 *
 * The error code can be one of the following:
 *
 * - `RouteNotFound` - Unable to find a valid route with the given parameters.
 *
 * - `RouteTooLong` - Route calculation went beyond the maximum size of a route and was terminated before completion.
 *
 * - `PositionsNotFound` - One or more of the input positions were not found on the route network.
 *
 * - `DestinationPositionNotFound` - The destination position was not found on the route network.
 *
 * - `DeparturePositionNotFound` - The departure position was not found on the route network.
 *
 * - `OtherValidationError` - The given inputs were not valid or a route was not found. More information is given in the
 *   error `Message`
 */
final class RouteMatrixEntryError
{
    /**
     * The type of error which occurred for the route calculation.
     *
     * @var RouteMatrixErrorCode::*|string
     */
    private $code;

    /**
     * A message about the error that occurred for the route calculation.
     *
     * @var string|null
     */
    private $message;

    /**
     * @param array{
     *   Code: RouteMatrixErrorCode::*|string,
     *   Message?: null|string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->code = $input['Code'] ?? $this->throwException(new InvalidArgument('Missing required field "Code".'));
        $this->message = $input['Message'] ?? null;
    }

    /**
     * @param array{
     *   Code: RouteMatrixErrorCode::*|string,
     *   Message?: null|string,
     * }|RouteMatrixEntryError $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return RouteMatrixErrorCode::*|string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    /**
     * @return never
     */
    private function throwException(\Throwable $exception)
    {
        throw $exception;
    }
}
