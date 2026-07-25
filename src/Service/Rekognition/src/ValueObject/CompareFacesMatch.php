<?php

namespace AsyncAws\Rekognition\ValueObject;

/**
 * Provides information about a face in a target image that matches the source image face analyzed by `CompareFaces`.
 * The `Face` property contains the bounding box of the face in the target image. The `Similarity` property is the
 * confidence that the source image face matches the face in the bounding box.
 */
final class CompareFacesMatch
{
    /**
     * Level of confidence that the faces match.
     *
     * @var float|null
     */
    private $similarity;

    /**
     * Provides face metadata (bounding box and confidence that the bounding box actually contains a face).
     *
     * @var ComparedFace|null
     */
    private $face;

    /**
     * @param array{
     *   Similarity?: float|null,
     *   Face?: ComparedFace|array|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->similarity = $input['Similarity'] ?? null;
        $this->face = isset($input['Face']) ? ComparedFace::create($input['Face']) : null;
    }

    /**
     * @param array{
     *   Similarity?: float|null,
     *   Face?: ComparedFace|array|null,
     * }|CompareFacesMatch $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getFace(): ?ComparedFace
    {
        return $this->face;
    }

    public function getSimilarity(): ?float
    {
        return $this->similarity;
    }
}
