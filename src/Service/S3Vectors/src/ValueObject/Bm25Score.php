<?php

namespace AsyncAws\S3Vectors\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * The raw BM25 relevance score for a single searchable metadata key.
 */
final class Bm25Score
{
    /**
     * The searchable metadata key that the score applies to.
     *
     * @var string
     */
    private $metadataKey;

    /**
     * The BM25 relevance score.
     *
     * @var float
     */
    private $score;

    /**
     * @param array{
     *   metadataKey: string,
     *   score: float,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->metadataKey = $input['metadataKey'] ?? $this->throwException(new InvalidArgument('Missing required field "metadataKey".'));
        $this->score = $input['score'] ?? $this->throwException(new InvalidArgument('Missing required field "score".'));
    }

    /**
     * @param array{
     *   metadataKey: string,
     *   score: float,
     * }|Bm25Score $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getMetadataKey(): string
    {
        return $this->metadataKey;
    }

    public function getScore(): float
    {
        return $this->score;
    }

    /**
     * @return never
     */
    private function throwException(\Throwable $exception)
    {
        throw $exception;
    }
}
