<?php

namespace AsyncAws\S3Vectors\ValueObject;

/**
 * Relevance scores for a full-text-only or hybrid search result.
 */
final class QueryScores
{
    /**
     * The Reciprocal Rank Fusion (RRF) score that combines the vector and full-text rankings. This field is returned only
     * for hybrid requests.
     *
     * @var float|null
     */
    private $fused;

    /**
     * The BM25 scores for the searchable metadata keys that the result matched.
     *
     * @var Bm25Score[]|null
     */
    private $bm25Scores;

    /**
     * The distance from the query vector. This field is returned when a hybrid result matches the vector search.
     *
     * @var float|null
     */
    private $vectorDistance;

    /**
     * @param array{
     *   fused?: float|null,
     *   bm25Scores?: array<Bm25Score|array>|null,
     *   vectorDistance?: float|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->fused = $input['fused'] ?? null;
        $this->bm25Scores = isset($input['bm25Scores']) ? array_map([Bm25Score::class, 'create'], $input['bm25Scores']) : null;
        $this->vectorDistance = $input['vectorDistance'] ?? null;
    }

    /**
     * @param array{
     *   fused?: float|null,
     *   bm25Scores?: array<Bm25Score|array>|null,
     *   vectorDistance?: float|null,
     * }|QueryScores $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return Bm25Score[]
     */
    public function getBm25Scores(): array
    {
        return $this->bm25Scores ?? [];
    }

    public function getFused(): ?float
    {
        return $this->fused;
    }

    public function getVectorDistance(): ?float
    {
        return $this->vectorDistance;
    }
}
