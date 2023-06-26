<?php

namespace AsyncAws\Lambda\ValueObject;

/**
 * An Lambda layer [^1].
 *
 * [^1]: https://docs.aws.amazon.com/lambda/latest/dg/configuration-layers.html
 */
final class Layer
{
    /**
     * The Amazon Resource Name (ARN) of the function layer.
     */
    private $arn;

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
     *   Arn?: null|string,
     *   CodeSize?: null|int,
     *   SigningProfileVersionArn?: null|string,
     *   SigningJobArn?: null|string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->arn = $input['Arn'] ?? null;
        $this->codeSize = $input['CodeSize'] ?? null;
        $this->signingProfileVersionArn = $input['SigningProfileVersionArn'] ?? null;
        $this->signingJobArn = $input['SigningJobArn'] ?? null;
    }

    /**
     * @param array{
     *   Arn?: null|string,
     *   CodeSize?: null|int,
     *   SigningProfileVersionArn?: null|string,
     *   SigningJobArn?: null|string,
     * }|Layer $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getArn(): ?string
    {
        return $this->arn;
    }

    public function getCodeSize(): ?int
    {
        return $this->codeSize;
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
