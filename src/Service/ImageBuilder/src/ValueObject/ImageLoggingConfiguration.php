<?php

namespace AsyncAws\ImageBuilder\ValueObject;

/**
 * The logging configuration that's defined for the image. Image Builder uses the defined settings to direct execution
 * log output during image creation.
 */
final class ImageLoggingConfiguration
{
    /**
     * The log group name that Image Builder uses for image creation. If not specified, the log group name defaults to
     * `/aws/imagebuilder/image-name`.
     *
     * @var string|null
     */
    private $logGroupName;

    /**
     * @param array{
     *   logGroupName?: string|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->logGroupName = $input['logGroupName'] ?? null;
    }

    /**
     * @param array{
     *   logGroupName?: string|null,
     * }|ImageLoggingConfiguration $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getLogGroupName(): ?string
    {
        return $this->logGroupName;
    }
}
