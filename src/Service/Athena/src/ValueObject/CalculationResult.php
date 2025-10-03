<?php

namespace AsyncAws\Athena\ValueObject;

/**
 * Contains information about an application-specific calculation result.
 */
final class CalculationResult
{
    /**
     * The Amazon S3 location of the `stdout` file for the calculation.
     *
     * @var string|null
     */
    private $stdOutS3Uri;

    /**
     * The Amazon S3 location of the `stderr` error messages file for the calculation.
     *
     * @var string|null
     */
    private $stdErrorS3Uri;

    /**
     * The Amazon S3 location of the folder for the calculation results.
     *
     * @var string|null
     */
    private $resultS3Uri;

    /**
     * The data format of the calculation result.
     *
     * @var string|null
     */
    private $resultType;

    /**
     * @param array{
     *   StdOutS3Uri?: string|null,
     *   StdErrorS3Uri?: string|null,
     *   ResultS3Uri?: string|null,
     *   ResultType?: string|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->stdOutS3Uri = $input['StdOutS3Uri'] ?? null;
        $this->stdErrorS3Uri = $input['StdErrorS3Uri'] ?? null;
        $this->resultS3Uri = $input['ResultS3Uri'] ?? null;
        $this->resultType = $input['ResultType'] ?? null;
    }

    /**
     * @param array{
     *   StdOutS3Uri?: string|null,
     *   StdErrorS3Uri?: string|null,
     *   ResultS3Uri?: string|null,
     *   ResultType?: string|null,
     * }|CalculationResult $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getResultS3Uri(): ?string
    {
        return $this->resultS3Uri;
    }

    public function getResultType(): ?string
    {
        return $this->resultType;
    }

    public function getStdErrorS3Uri(): ?string
    {
        return $this->stdErrorS3Uri;
    }

    public function getStdOutS3Uri(): ?string
    {
        return $this->stdOutS3Uri;
    }
}
