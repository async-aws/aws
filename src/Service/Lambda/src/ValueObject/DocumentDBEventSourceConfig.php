<?php

namespace AsyncAws\Lambda\ValueObject;

use AsyncAws\Lambda\Enum\FullDocument;

/**
 * Specific configuration settings for a DocumentDB event source.
 */
final class DocumentDBEventSourceConfig
{
    /**
     * The name of the database to consume within the DocumentDB cluster.
     *
     * @var string|null
     */
    private $databaseName;

    /**
     * The name of the collection to consume within the database. If you do not specify a collection, Lambda consumes all
     * collections.
     *
     * @var string|null
     */
    private $collectionName;

    /**
     * Determines what DocumentDB sends to your event stream during document update operations. If set to UpdateLookup,
     * DocumentDB sends a delta describing the changes, along with a copy of the entire document. Otherwise, DocumentDB
     * sends only a partial document that contains the changes.
     *
     * @var FullDocument::*|null
     */
    private $fullDocument;

    /**
     * @param array{
     *   DatabaseName?: string|null,
     *   CollectionName?: string|null,
     *   FullDocument?: FullDocument::*|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->databaseName = $input['DatabaseName'] ?? null;
        $this->collectionName = $input['CollectionName'] ?? null;
        $this->fullDocument = $input['FullDocument'] ?? null;
    }

    /**
     * @param array{
     *   DatabaseName?: string|null,
     *   CollectionName?: string|null,
     *   FullDocument?: FullDocument::*|null,
     * }|DocumentDBEventSourceConfig $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getCollectionName(): ?string
    {
        return $this->collectionName;
    }

    public function getDatabaseName(): ?string
    {
        return $this->databaseName;
    }

    /**
     * @return FullDocument::*|null
     */
    public function getFullDocument(): ?string
    {
        return $this->fullDocument;
    }
}
