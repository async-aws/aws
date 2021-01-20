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
    private $location;

    /**
     * The SHA-256 hash of the layer archive.
     */
    private $codeSha256;

    /**
     * The size of the layer archive in bytes.
     */
    private $codeSize;

    /**
     * The Amazon Resource Name (ARN) for a signing profile version.
     */
    private $signingProfileVersionArn;

    /**
     * The Amazon Resource Name (ARN) of a signing job.
     */
    private $signingJobArn;

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
        $this->location = $input['Location'] ?? null;
        $this->codeSha256 = $input['CodeSha256'] ?? null;
        $this->codeSize = $input['CodeSize'] ?? null;
        $this->signingProfileVersionArn = $input['SigningProfileVersionArn'] ?? null;
        $this->signingJobArn = $input['SigningJobArn'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getCodeSha256(): ?string
    {
        return $this->codeSha256;
    }

    public function getCodeSize(): ?string
    {
        return $this->codeSize;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function getSigningJobArn(): ?string
    {
        return $this->signingJobArn;
    }

    public function getSigningProfileVersionArn(): ?string
    {
        return $this->signingProfileVersionArn;
    }
}
