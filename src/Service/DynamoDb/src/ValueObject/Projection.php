<?php

namespace AsyncAws\DynamoDb\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\DynamoDb\Enum\ProjectionType;

/**
 * Represents attributes that are copied (projected) from the table into the local secondary index. These are in
 * addition to the primary key attributes and index key attributes, which are automatically projected.
 */
final class Projection
{
    /**
     * The set of attributes that are projected into the index:.
     */
    private $ProjectionType;

    /**
     * Represents the non-key attribute names which will be projected into the index.
     */
    private $NonKeyAttributes;

    /**
     * @param array{
     *   ProjectionType?: null|ProjectionType::*,
     *   NonKeyAttributes?: null|string[],
     * } $input
     */
    public function __construct(array $input)
    {
        $this->ProjectionType = $input['ProjectionType'] ?? null;
        $this->NonKeyAttributes = $input['NonKeyAttributes'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return string[]
     */
    public function getNonKeyAttributes(): array
    {
        return $this->NonKeyAttributes ?? [];
    }

    /**
     * @return ProjectionType::*|null
     */
    public function getProjectionType(): ?string
    {
        return $this->ProjectionType;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->ProjectionType) {
            if (!ProjectionType::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "ProjectionType" for "%s". The value "%s" is not a valid "ProjectionType".', __CLASS__, $v));
            }
            $payload['ProjectionType'] = $v;
        }
        if (null !== $v = $this->NonKeyAttributes) {
            $index = -1;
            $payload['NonKeyAttributes'] = [];
            foreach ($v as $listValue) {
                ++$index;
                $payload['NonKeyAttributes'][$index] = $listValue;
            }
        }

        return $payload;
    }
}
