<?php

namespace AsyncAws\CloudWatchLogs\ValueObject;

use AsyncAws\CloudWatchLogs\Enum\EntityRejectionErrorType;
use AsyncAws\Core\Exception\InvalidArgument;

/**
 * If an entity is rejected when a `PutLogEvents` request was made, this includes details about the reason for the
 * rejection.
 */
final class RejectedEntityInfo
{
    /**
     * The type of error that caused the rejection of the entity when calling `PutLogEvents`.
     *
     * @var EntityRejectionErrorType::*|string
     */
    private $errorType;

    /**
     * @param array{
     *   errorType: EntityRejectionErrorType::*|string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->errorType = $input['errorType'] ?? $this->throwException(new InvalidArgument('Missing required field "errorType".'));
    }

    /**
     * @param array{
     *   errorType: EntityRejectionErrorType::*|string,
     * }|RejectedEntityInfo $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return EntityRejectionErrorType::*|string
     */
    public function getErrorType(): string
    {
        return $this->errorType;
    }

    /**
     * @return never
     */
    private function throwException(\Throwable $exception)
    {
        throw $exception;
    }
}
