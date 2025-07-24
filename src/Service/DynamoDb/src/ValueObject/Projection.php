<?php

namespace AsyncAws\DynamoDb\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\DynamoDb\Enum\ProjectionType;

/**
 * Represents attributes that are copied (projected) from the table into an index. These are in addition to the primary
 * key attributes and index key attributes, which are automatically projected.
 */
final class Projection
{
    /**
     * The set of attributes that are projected into the index:
     *
     * - `KEYS_ONLY` - Only the index and primary keys are projected into the index.
     * - `INCLUDE` - In addition to the attributes described in `KEYS_ONLY`, the secondary index will include other non-key
     *   attributes that you specify.
     * - `ALL` - All of the table attributes are projected into the index.
     *
     * When using the DynamoDB console, `ALL` is selected by default.
     *
     * @var ProjectionType::*|string|null
     */
    private $projectionType;

    /**
     * Represents the non-key attribute names which will be projected into the index.
     *
     * For global and local secondary indexes, the total count of `NonKeyAttributes` summed across all of the secondary
     * indexes, must not exceed 100. If you project the same attribute into two different indexes, this counts as two
     * distinct attributes when determining the total. This limit only applies when you specify the ProjectionType of
     * `INCLUDE`. You still can specify the ProjectionType of `ALL` to project all attributes from the source table, even if
     * the table has more than 100 attributes.
     *
     * @var string[]|null
     */
    private $nonKeyAttributes;

    /**
     * @param array{
     *   ProjectionType?: null|ProjectionType::*|string,
     *   NonKeyAttributes?: null|string[],
     * } $input
     */
    public function __construct(array $input)
    {
        $this->projectionType = $input['ProjectionType'] ?? null;
        $this->nonKeyAttributes = $input['NonKeyAttributes'] ?? null;
    }

    /**
     * @param array{
     *   ProjectionType?: null|ProjectionType::*|string,
     *   NonKeyAttributes?: null|string[],
     * }|Projection $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return string[]
     */
    public function getNonKeyAttributes(): array
    {
        return $this->nonKeyAttributes ?? [];
    }

    /**
     * @return ProjectionType::*|string|null
     */
    public function getProjectionType(): ?string
    {
        return $this->projectionType;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->projectionType) {
            if (!ProjectionType::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "ProjectionType" for "%s". The value "%s" is not a valid "ProjectionType".', __CLASS__, $v));
            }
            $payload['ProjectionType'] = $v;
        }
        if (null !== $v = $this->nonKeyAttributes) {
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
