<?php

namespace AsyncAws\Rekognition\ValueObject;

/**
 * Provides information about a celebrity recognized by the RecognizeCelebrities operation.
 */
final class Celebrity
{
    /**
     * An array of URLs pointing to additional information about the celebrity. If there is no additional information about
     * the celebrity, this list is empty.
     */
    private $urls;

    /**
     * The name of the celebrity.
     */
    private $name;

    /**
     * A unique identifier for the celebrity.
     */
    private $id;

    /**
     * Provides information about the celebrity's face, such as its location on the image.
     */
    private $face;

    /**
     * The confidence, in percentage, that Amazon Rekognition has that the recognized face is the celebrity.
     */
    private $matchConfidence;

    /**
     * @param array{
     *   Urls?: null|string[],
     *   Name?: null|string,
     *   Id?: null|string,
     *   Face?: null|ComparedFace|array,
     *   MatchConfidence?: null|float,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->urls = $input['Urls'] ?? null;
        $this->name = $input['Name'] ?? null;
        $this->id = $input['Id'] ?? null;
        $this->face = isset($input['Face']) ? ComparedFace::create($input['Face']) : null;
        $this->matchConfidence = $input['MatchConfidence'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getFace(): ?ComparedFace
    {
        return $this->face;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getMatchConfidence(): ?float
    {
        return $this->matchConfidence;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @return string[]
     */
    public function getUrls(): array
    {
        return $this->urls ?? [];
    }
}
