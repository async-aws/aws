<?php

namespace AsyncAws\AppSync\ValueObject;

use AsyncAws\AppSync\Enum\ConflictDetectionType;
use AsyncAws\AppSync\Enum\ConflictHandlerType;
use AsyncAws\Core\Exception\InvalidArgument;

/**
 * The `SyncConfig` for a resolver attached to a versioned data source.
 */
final class SyncConfig
{
    /**
     * The Conflict Resolution strategy to perform in the event of a conflict.
     */
    private $conflictHandler;

    /**
     * The Conflict Detection strategy to use.
     */
    private $conflictDetection;

    /**
     * The `LambdaConflictHandlerConfig` when configuring `LAMBDA` as the Conflict Handler.
     */
    private $lambdaConflictHandlerConfig;

    /**
     * @param array{
     *   conflictHandler?: null|ConflictHandlerType::*,
     *   conflictDetection?: null|ConflictDetectionType::*,
     *   lambdaConflictHandlerConfig?: null|LambdaConflictHandlerConfig|array,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->conflictHandler = $input['conflictHandler'] ?? null;
        $this->conflictDetection = $input['conflictDetection'] ?? null;
        $this->lambdaConflictHandlerConfig = isset($input['lambdaConflictHandlerConfig']) ? LambdaConflictHandlerConfig::create($input['lambdaConflictHandlerConfig']) : null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return ConflictDetectionType::*|null
     */
    public function getConflictDetection(): ?string
    {
        return $this->conflictDetection;
    }

    /**
     * @return ConflictHandlerType::*|null
     */
    public function getConflictHandler(): ?string
    {
        return $this->conflictHandler;
    }

    public function getLambdaConflictHandlerConfig(): ?LambdaConflictHandlerConfig
    {
        return $this->lambdaConflictHandlerConfig;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->conflictHandler) {
            if (!ConflictHandlerType::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "conflictHandler" for "%s". The value "%s" is not a valid "ConflictHandlerType".', __CLASS__, $v));
            }
            $payload['conflictHandler'] = $v;
        }
        if (null !== $v = $this->conflictDetection) {
            if (!ConflictDetectionType::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "conflictDetection" for "%s". The value "%s" is not a valid "ConflictDetectionType".', __CLASS__, $v));
            }
            $payload['conflictDetection'] = $v;
        }
        if (null !== $v = $this->lambdaConflictHandlerConfig) {
            $payload['lambdaConflictHandlerConfig'] = $v->requestBody();
        }

        return $payload;
    }
}
