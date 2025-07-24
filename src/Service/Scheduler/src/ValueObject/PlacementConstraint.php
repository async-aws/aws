<?php

namespace AsyncAws\Scheduler\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Scheduler\Enum\PlacementConstraintType;

/**
 * An object representing a constraint on task placement.
 */
final class PlacementConstraint
{
    /**
     * A cluster query language expression to apply to the constraint. You cannot specify an expression if the constraint
     * type is `distinctInstance`. For more information, see Cluster query language [^1] in the *Amazon ECS Developer
     * Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/latest/developerguide/cluster-query-language.html
     *
     * @var string|null
     */
    private $expression;

    /**
     * The type of constraint. Use `distinctInstance` to ensure that each task in a particular group is running on a
     * different container instance. Use `memberOf` to restrict the selection to a group of valid candidates.
     *
     * @var PlacementConstraintType::*|string|null
     */
    private $type;

    /**
     * @param array{
     *   expression?: null|string,
     *   type?: null|PlacementConstraintType::*|string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->expression = $input['expression'] ?? null;
        $this->type = $input['type'] ?? null;
    }

    /**
     * @param array{
     *   expression?: null|string,
     *   type?: null|PlacementConstraintType::*|string,
     * }|PlacementConstraint $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getExpression(): ?string
    {
        return $this->expression;
    }

    /**
     * @return PlacementConstraintType::*|string|null
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->expression) {
            $payload['expression'] = $v;
        }
        if (null !== $v = $this->type) {
            if (!PlacementConstraintType::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "type" for "%s". The value "%s" is not a valid "PlacementConstraintType".', __CLASS__, $v));
            }
            $payload['type'] = $v;
        }

        return $payload;
    }
}
