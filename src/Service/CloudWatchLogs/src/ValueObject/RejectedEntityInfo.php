<?php

namespace AsyncAws\CloudWatchLogs\ValueObject;

use AsyncAws\CloudWatchLogs\Enum\EntityRejectionErrorType;
use AsyncAws\Core\Exception\InvalidArgument;

/**
 * Reserved for future use.
 */
final class RejectedEntityInfo
{
    /**
     * Reserved for future use.
     *
     * @var EntityRejectionErrorType::*
     */
    private $errorType;

    /**
     * @param array{
     *   errorType: EntityRejectionErrorType::*,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->errorType = $input['errorType'] ?? $this->throwException(new InvalidArgument('Missing required field "errorType".'));
    }

    /**
     * @param array{
     *   errorType: EntityRejectionErrorType::*,
     * }|RejectedEntityInfo $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return EntityRejectionErrorType::*
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
