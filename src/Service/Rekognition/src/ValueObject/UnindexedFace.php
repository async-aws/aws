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
     */
    private $reasons;

    /**
     * The structure that contains attributes of a face that `IndexFaces`detected, but didn't index.
     */
    private $faceDetail;

    /**
     * @param array{
     *   Reasons?: null|list<Reason::*>,
     *   FaceDetail?: null|FaceDetail|array,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->reasons = $input['Reasons'] ?? null;
        $this->faceDetail = isset($input['FaceDetail']) ? FaceDetail::create($input['FaceDetail']) : null;
    }

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
