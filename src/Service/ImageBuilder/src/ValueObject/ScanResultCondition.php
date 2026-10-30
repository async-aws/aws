<?php

namespace AsyncAws\ImageBuilder\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\ImageBuilder\Enum\MaximumSeverityLabel;

/**
 * A condition that sets which findings from an on-demand AMI scan fail the image. Image Builder checks the output AMI's
 * findings against this condition during the test phase.
 */
final class ScanResultCondition
{
    /**
     * The maximum severity allowed for findings on the output AMI. A finding above this severity fails the image, unless
     * `fixAvailable` excludes it. Valid values are `INFORMATIONAL`, `LOW`, `MEDIUM`, and `HIGH`. A `CRITICAL` finding that
     * counts always fails the image.
     *
     * @var MaximumSeverityLabel::*
     */
    private $maximumSeverityLabel;

    /**
     * Specifies whether only findings that have a fix available count toward `maximumSeverityLabel`. When `false`, Image
     * Builder counts every finding. The default is `true`.
     *
     * @var bool|null
     */
    private $fixAvailable;

    /**
     * @param array{
     *   maximumSeverityLabel: MaximumSeverityLabel::*,
     *   fixAvailable?: bool|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->maximumSeverityLabel = $input['maximumSeverityLabel'] ?? $this->throwException(new InvalidArgument('Missing required field "maximumSeverityLabel".'));
        $this->fixAvailable = $input['fixAvailable'] ?? null;
    }

    /**
     * @param array{
     *   maximumSeverityLabel: MaximumSeverityLabel::*,
     *   fixAvailable?: bool|null,
     * }|ScanResultCondition $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getFixAvailable(): ?bool
    {
        return $this->fixAvailable;
    }

    /**
     * @return MaximumSeverityLabel::*
     */
    public function getMaximumSeverityLabel(): string
    {
        return $this->maximumSeverityLabel;
    }

    /**
     * @return never
     */
    private function throwException(\Throwable $exception)
    {
        throw $exception;
    }
}
