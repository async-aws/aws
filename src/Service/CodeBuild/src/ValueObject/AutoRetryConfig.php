<?php

namespace AsyncAws\CodeBuild\ValueObject;

/**
 * Information about the auto-retry configuration for the build.
 */
final class AutoRetryConfig
{
    /**
     * The maximum number of additional automatic retries after a failed build. For example, if the auto-retry limit is set
     * to 2, CodeBuild will call the `RetryBuild` API to automatically retry your build for up to 2 additional times.
     *
     * @var int|null
     */
    private $autoRetryLimit;

    /**
     * The number of times that the build has been retried. The initial build will have an auto-retry number of 0.
     *
     * @var int|null
     */
    private $autoRetryNumber;

    /**
     * The build ARN of the auto-retried build triggered by the current build. The next auto-retry will be `null` for builds
     * that don't trigger an auto-retry.
     *
     * @var string|null
     */
    private $nextAutoRetry;

    /**
     * The build ARN of the build that triggered the current auto-retry build. The previous auto-retry will be `null` for
     * the initial build.
     *
     * @var string|null
     */
    private $previousAutoRetry;

    /**
     * @param array{
     *   autoRetryLimit?: int|null,
     *   autoRetryNumber?: int|null,
     *   nextAutoRetry?: string|null,
     *   previousAutoRetry?: string|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->autoRetryLimit = $input['autoRetryLimit'] ?? null;
        $this->autoRetryNumber = $input['autoRetryNumber'] ?? null;
        $this->nextAutoRetry = $input['nextAutoRetry'] ?? null;
        $this->previousAutoRetry = $input['previousAutoRetry'] ?? null;
    }

    /**
     * @param array{
     *   autoRetryLimit?: int|null,
     *   autoRetryNumber?: int|null,
     *   nextAutoRetry?: string|null,
     *   previousAutoRetry?: string|null,
     * }|AutoRetryConfig $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getAutoRetryLimit(): ?int
    {
        return $this->autoRetryLimit;
    }

    public function getAutoRetryNumber(): ?int
    {
        return $this->autoRetryNumber;
    }

    public function getNextAutoRetry(): ?string
    {
        return $this->nextAutoRetry;
    }

    public function getPreviousAutoRetry(): ?string
    {
        return $this->previousAutoRetry;
    }
}
