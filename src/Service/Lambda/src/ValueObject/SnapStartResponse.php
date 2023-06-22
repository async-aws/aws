<?php

namespace AsyncAws\Lambda\ValueObject;

use AsyncAws\Lambda\Enum\SnapStartApplyOn;
use AsyncAws\Lambda\Enum\SnapStartOptimizationStatus;

/**
 * The function's SnapStart [^1] setting.
 *
 * [^1]: https://docs.aws.amazon.com/lambda/latest/dg/snapstart.html
 */
final class SnapStartResponse
{
    /**
     * When set to `PublishedVersions`, Lambda creates a snapshot of the execution environment when you publish a function
     * version.
     */
    private $applyOn;

    /**
     * When you provide a qualified Amazon Resource Name (ARN) [^1], this response element indicates whether SnapStart is
     * activated for the specified function version.
     *
     * [^1]: https://docs.aws.amazon.com/lambda/latest/dg/configuration-versions.html#versioning-versions-using
     */
    private $optimizationStatus;

    /**
     * @param array{
     *   ApplyOn?: null|SnapStartApplyOn::*,
     *   OptimizationStatus?: null|SnapStartOptimizationStatus::*,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->applyOn = $input['ApplyOn'] ?? null;
        $this->optimizationStatus = $input['OptimizationStatus'] ?? null;
    }

    /**
     * @param array{
     *   ApplyOn?: null|SnapStartApplyOn::*,
     *   OptimizationStatus?: null|SnapStartOptimizationStatus::*,
     * }|SnapStartResponse $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return SnapStartApplyOn::*|null
     */
    public function getApplyOn(): ?string
    {
        return $this->applyOn;
    }

    /**
     * @return SnapStartOptimizationStatus::*|null
     */
    public function getOptimizationStatus(): ?string
    {
        return $this->optimizationStatus;
    }
}
