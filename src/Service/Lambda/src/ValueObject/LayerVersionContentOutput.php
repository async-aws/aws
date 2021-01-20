<?php

namespace AsyncAws\Lambda\ValueObject;

/**
 * Details about the layer version.
 */
final class LayerVersionContentOutput
{
    /**
     * A link to the layer archive in Amazon S3 that is valid for 10 minutes.
     */
    private $Location;

    /**
     * The SHA-256 hash of the layer archive.
     */
    private $CodeSha256;

    /**
     * The size of the layer archive in bytes.
     */
    private $CodeSize;

    /**
     * The Amazon Resource Name (ARN) for a signing profile version.
     */
    private $SigningProfileVersionArn;

    /**
     * The Amazon Resource Name (ARN) of a signing job.
     */
    private $SigningJobArn;

    /**
     * @param array{
     *   Location?: null|string,
     *   CodeSha256?: null|string,
     *   CodeSize?: null|string,
     *   SigningProfileVersionArn?: null|string,
     *   SigningJobArn?: null|string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->Location = $input['Location'] ?? null;
        $this->CodeSha256 = $input['CodeSha256'] ?? null;
        $this->CodeSize = $input['CodeSize'] ?? null;
        $this->SigningProfileVersionArn = $input['SigningProfileVersionArn'] ?? null;
        $this->SigningJobArn = $input['SigningJobArn'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getCodeSha256(): ?string
    {
        return $this->CodeSha256;
    }

    public function getCodeSize(): ?string
    {
        return $this->CodeSize;
    }

    public function getLocation(): ?string
    {
        return $this->Location;
    }

    public function getSigningJobArn(): ?string
    {
        return $this->SigningJobArn;
    }

    public function getSigningProfileVersionArn(): ?string
    {
        return $this->SigningProfileVersionArn;
    }
}
