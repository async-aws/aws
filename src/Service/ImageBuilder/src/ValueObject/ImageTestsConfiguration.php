<?php

namespace AsyncAws\ImageBuilder\ValueObject;

/**
 * Configure image tests for your pipeline build. Tests run after building the image, to verify that the AMI or
 * container image is valid before distributing it.
 */
final class ImageTestsConfiguration
{
    /**
     * Determines if tests should run after building the image. Image Builder defaults to enable tests to run following the
     * image build, before image distribution.
     *
     * @var bool|null
     */
    private $imageTestsEnabled;

    /**
     * The maximum time in minutes that tests are permitted to run.
     *
     * > The timeout property is not currently active. This value is ignored.
     *
     * @var int|null
     */
    private $timeoutMinutes;

    /**
     * @param array{
     *   imageTestsEnabled?: bool|null,
     *   timeoutMinutes?: int|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->imageTestsEnabled = $input['imageTestsEnabled'] ?? null;
        $this->timeoutMinutes = $input['timeoutMinutes'] ?? null;
    }

    /**
     * @param array{
     *   imageTestsEnabled?: bool|null,
     *   timeoutMinutes?: int|null,
     * }|ImageTestsConfiguration $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getImageTestsEnabled(): ?bool
    {
        return $this->imageTestsEnabled;
    }

    public function getTimeoutMinutes(): ?int
    {
        return $this->timeoutMinutes;
    }
}
