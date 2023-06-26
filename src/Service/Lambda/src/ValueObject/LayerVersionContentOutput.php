<?php

namespace AsyncAws\Lambda\ValueObject;

/**
 * Details about a version of an Lambda layer [^1].
 *
 * [^1]: https://docs.aws.amazon.com/lambda/latest/dg/configuration-layers.html
 */
final class LayerVersionContentOutput
{
    /**
     * A link to the layer archive in Amazon S3 that is valid for 10 minutes.
     *
     * @var string|null
     */
    private $location;

    /**
     * The SHA-256 hash of the layer archive.
     *
     * @var string|null
     */
    private $codeSha256;

    /**
     * The size of the layer archive in bytes.
     *
     * @var int|null
     */
    private $codeSize;

    /**
     * The Amazon Resource Name (ARN) for a signing profile version.
     *
     * @var string|null
     */
    private $signingProfileVersionArn;

    /**
     * The Amazon Resource Name (ARN) of a signing job.
     *
     * @var string|null
     */
    private $signingJobArn;

    /**
     * @param array{
     *   Location?: null|string,
     *   CodeSha256?: null|string,
     *   CodeSize?: null|int,
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

    /**
     * @param array{
     *   Location?: null|string,
     *   CodeSha256?: null|string,
     *   CodeSize?: null|int,
     *   SigningProfileVersionArn?: null|string,
     *   SigningJobArn?: null|string,
     * }|LayerVersionContentOutput $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getCodeSha256(): ?string
    {
        return $this->codeSha256;
    }

    public function getCodeSize(): ?int
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
