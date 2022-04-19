<?php

namespace AsyncAws\CodeCommit\ValueObject;

use AsyncAws\CodeCommit\Enum\ChangeTypeEnum;

/**
 * Returns information about a set of differences for a commit specifier.
 */
final class Difference
{
    /**
     * Information about a `beforeBlob` data type object, including the ID, the file mode permission code, and the path.
     */
    private $beforeBlob;

    /**
     * Information about an `afterBlob` data type object, including the ID, the file mode permission code, and the path.
     */
    private $afterBlob;

    /**
     * Whether the change type of the difference is an addition (A), deletion (D), or modification (M).
     */
    private $changeType;

    /**
     * @param array{
     *   beforeBlob?: null|BlobMetadata|array,
     *   afterBlob?: null|BlobMetadata|array,
     *   changeType?: null|ChangeTypeEnum::*,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->beforeBlob = isset($input['beforeBlob']) ? BlobMetadata::create($input['beforeBlob']) : null;
        $this->afterBlob = isset($input['afterBlob']) ? BlobMetadata::create($input['afterBlob']) : null;
        $this->changeType = $input['changeType'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getAfterBlob(): ?BlobMetadata
    {
        return $this->afterBlob;
    }

    public function getBeforeBlob(): ?BlobMetadata
    {
        return $this->beforeBlob;
    }

    /**
     * @return ChangeTypeEnum::*|null
     */
    public function getChangeType(): ?string
    {
        return $this->changeType;
    }
}
