<?php

namespace AsyncAws\AppSync\ValueObject;

use AsyncAws\AppSync\Enum\ConflictDetectionType;
use AsyncAws\AppSync\Enum\ConflictHandlerType;
use AsyncAws\Core\Exception\InvalidArgument;

/**
 * Describes a Sync configuration for a resolver.
 *
 * Specifies which Conflict Detection strategy and Resolution strategy to use when the resolver is invoked.
 */
final class SyncConfig
{
    /**
     * The Conflict Resolution strategy to perform in the event of a conflict.
     *
     * - **OPTIMISTIC_CONCURRENCY**: Resolve conflicts by rejecting mutations when versions don't match the latest version
     *   at the server.
     * - **AUTOMERGE**: Resolve conflicts with the Automerge conflict resolution strategy.
     * - **LAMBDA**: Resolve conflicts with an Lambda function supplied in the `LambdaConflictHandlerConfig`.
     *
     * @var ConflictHandlerType::*|null
     */
    private $conflictHandler;

    /**
     * The Conflict Detection strategy to use.
     *
     * - **VERSION**: Detect conflicts based on object versions for this resolver.
     * - **NONE**: Do not detect conflicts when invoking this resolver.
     *
     * @var ConflictDetectionType::*|null
     */
    private $conflictDetection;

    /**
     * The `LambdaConflictHandlerConfig` when configuring `LAMBDA` as the Conflict Handler.
     *
     * @var LambdaConflictHandlerConfig|null
     */
    private $lambdaConflictHandlerConfig;

    /**
     * @param array{
     *   conflictHandler?: ConflictHandlerType::*|null,
     *   conflictDetection?: ConflictDetectionType::*|null,
     *   lambdaConflictHandlerConfig?: LambdaConflictHandlerConfig|array|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->conflictHandler = $input['conflictHandler'] ?? null;
        $this->conflictDetection = $input['conflictDetection'] ?? null;
        $this->lambdaConflictHandlerConfig = isset($input['lambdaConflictHandlerConfig']) ? LambdaConflictHandlerConfig::create($input['lambdaConflictHandlerConfig']) : null;
    }

    /**
     * @param array{
     *   conflictHandler?: ConflictHandlerType::*|null,
     *   conflictDetection?: ConflictDetectionType::*|null,
     *   lambdaConflictHandlerConfig?: LambdaConflictHandlerConfig|array|null,
     * }|SyncConfig $input
     */
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
                throw new InvalidArgument(\sprintf('Invalid parameter "conflictHandler" for "%s". The value "%s" is not a valid "ConflictHandlerType".', __CLASS__, $v));
            }
            $payload['conflictHandler'] = $v;
        }
        if (null !== $v = $this->conflictDetection) {
            if (!ConflictDetectionType::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "conflictDetection" for "%s". The value "%s" is not a valid "ConflictDetectionType".', __CLASS__, $v));
            }
            $payload['conflictDetection'] = $v;
        }
        if (null !== $v = $this->lambdaConflictHandlerConfig) {
            $payload['lambdaConflictHandlerConfig'] = $v->requestBody();
        }

        return $payload;
    }
}
