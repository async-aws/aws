<?php

namespace AsyncAws\Ec2\ValueObject;

/**
 * Describes a watermark attached to an AMI.
 */
final class ImageWatermark
{
    /**
     * The watermark identifier, in `accountId:watermarkName` format (for example, `123456789012:approvedAmi`). The
     * `accountId` portion is the Amazon Web Services account ID of the watermark creator. The `watermarkName` portion is
     * customer-provided.
     *
     * @var string|null
     */
    private $watermarkKey;

    /**
     * The Region where the watermark was originally attached.
     *
     * @var string|null
     */
    private $sourceImageRegion;

    /**
     * The ID of the AMI to which the watermark was originally attached.
     *
     * @var string|null
     */
    private $sourceImageId;

    /**
     * The creation date of the source AMI, in the following format: *YYYY*-*MM*-*DD*T*HH*:*MM*:*SS*.*ssssss*+*HH*:*MM*.
     *
     * @var \DateTimeImmutable|null
     */
    private $sourceImageCreationTime;

    /**
     * The date and time the watermark was attached to the AMI, in the following format:
     * *YYYY*-*MM*-*DD*T*HH*:*MM*:*SS*.*ssssss*+*HH*:*MM*.
     *
     * @var \DateTimeImmutable|null
     */
    private $watermarkCreationTime;

    /**
     * @param array{
     *   WatermarkKey?: string|null,
     *   SourceImageRegion?: string|null,
     *   SourceImageId?: string|null,
     *   SourceImageCreationTime?: \DateTimeImmutable|null,
     *   WatermarkCreationTime?: \DateTimeImmutable|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->watermarkKey = $input['WatermarkKey'] ?? null;
        $this->sourceImageRegion = $input['SourceImageRegion'] ?? null;
        $this->sourceImageId = $input['SourceImageId'] ?? null;
        $this->sourceImageCreationTime = $input['SourceImageCreationTime'] ?? null;
        $this->watermarkCreationTime = $input['WatermarkCreationTime'] ?? null;
    }

    /**
     * @param array{
     *   WatermarkKey?: string|null,
     *   SourceImageRegion?: string|null,
     *   SourceImageId?: string|null,
     *   SourceImageCreationTime?: \DateTimeImmutable|null,
     *   WatermarkCreationTime?: \DateTimeImmutable|null,
     * }|ImageWatermark $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getSourceImageCreationTime(): ?\DateTimeImmutable
    {
        return $this->sourceImageCreationTime;
    }

    public function getSourceImageId(): ?string
    {
        return $this->sourceImageId;
    }

    public function getSourceImageRegion(): ?string
    {
        return $this->sourceImageRegion;
    }

    public function getWatermarkCreationTime(): ?\DateTimeImmutable
    {
        return $this->watermarkCreationTime;
    }

    public function getWatermarkKey(): ?string
    {
        return $this->watermarkKey;
    }
}
