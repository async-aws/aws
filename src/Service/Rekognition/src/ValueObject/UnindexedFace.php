<?php

namespace AsyncAws\Rekognition\ValueObject;

use AsyncAws\Rekognition\Enum\Reason;

/**
 * A face that IndexFaces detected, but didn't index. Use the `Reasons` response attribute to determine why a face
 * wasn't indexed.
 */
final class UnindexedFace
{
    /**
     * An array of reasons that specify why a face wasn't indexed.
     *
     * - EXTREME_POSE - The face is at a pose that can't be detected. For example, the head is turned too far away from the
     *   camera.
     * - EXCEEDS_MAX_FACES - The number of faces detected is already higher than that specified by the `MaxFaces` input
     *   parameter for `IndexFaces`.
     * - LOW_BRIGHTNESS - The image is too dark.
     * - LOW_SHARPNESS - The image is too blurry.
     * - LOW_CONFIDENCE - The face was detected with a low confidence.
     * - SMALL_BOUNDING_BOX - The bounding box around the face is too small.
     *
     * @var list<Reason::*>|null
     */
    private $reasons;

    /**
     * The structure that contains attributes of a face that `IndexFaces`detected, but didn't index.
     *
     * @var FaceDetail|null
     */
    private $faceDetail;

    /**
     * @param array{
     *   Reasons?: array<Reason::*>|null,
     *   FaceDetail?: FaceDetail|array|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->reasons = $input['Reasons'] ?? null;
        $this->faceDetail = isset($input['FaceDetail']) ? FaceDetail::create($input['FaceDetail']) : null;
    }

    /**
     * @param array{
     *   Reasons?: array<Reason::*>|null,
     *   FaceDetail?: FaceDetail|array|null,
     * }|UnindexedFace $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getFaceDetail(): ?FaceDetail
    {
        return $this->faceDetail;
    }

    /**
     * @return list<Reason::*>
     */
    public function getReasons(): array
    {
        return $this->reasons ?? [];
    }
}
