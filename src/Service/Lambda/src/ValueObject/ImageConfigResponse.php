<?php

namespace AsyncAws\Lambda\ValueObject;

/**
 * Response to a `GetFunctionConfiguration` request.
 */
final class ImageConfigResponse
{
    /**
     * Configuration values that override the container image Dockerfile.
     *
     * @var ImageConfig|null
     */
    private $imageConfig;

    /**
     * Error response to `GetFunctionConfiguration`.
     *
     * @var ImageConfigError|null
     */
    private $error;

    /**
     * @param array{
     *   ImageConfig?: ImageConfig|array|null,
     *   Error?: ImageConfigError|array|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->imageConfig = isset($input['ImageConfig']) ? ImageConfig::create($input['ImageConfig']) : null;
        $this->error = isset($input['Error']) ? ImageConfigError::create($input['Error']) : null;
    }

    /**
     * @param array{
     *   ImageConfig?: ImageConfig|array|null,
     *   Error?: ImageConfigError|array|null,
     * }|ImageConfigResponse $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getError(): ?ImageConfigError
    {
        return $this->error;
    }

    public function getImageConfig(): ?ImageConfig
    {
        return $this->imageConfig;
    }
}
