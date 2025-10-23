<?php

namespace AsyncAws\Comprehend\ValueObject;

/**
 * Returns the code for the dominant language in the input text and the level of confidence that Amazon Comprehend has
 * in the accuracy of the detection.
 */
final class DominantLanguage
{
    /**
     * The RFC 5646 language code for the dominant language. For more information about RFC 5646, see Tags for Identifying
     * Languages [^1] on the *IETF Tools* web site.
     *
     * [^1]: https://tools.ietf.org/html/rfc5646
     *
     * @var string|null
     */
    private $languageCode;

    /**
     * The level of confidence that Amazon Comprehend has in the accuracy of the detection.
     *
     * @var float|null
     */
    private $score;

    /**
     * @param array{
     *   LanguageCode?: string|null,
     *   Score?: float|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->languageCode = $input['LanguageCode'] ?? null;
        $this->score = $input['Score'] ?? null;
    }

    /**
     * @param array{
     *   LanguageCode?: string|null,
     *   Score?: float|null,
     * }|DominantLanguage $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getLanguageCode(): ?string
    {
        return $this->languageCode;
    }

    public function getScore(): ?float
    {
        return $this->score;
    }
}
