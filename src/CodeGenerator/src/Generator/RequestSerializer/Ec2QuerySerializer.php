<?php

declare(strict_types=1);

namespace AsyncAws\CodeGenerator\Generator\RequestSerializer;

use AsyncAws\CodeGenerator\Definition\ListShape;
use AsyncAws\CodeGenerator\Definition\MapShape;
use AsyncAws\CodeGenerator\Definition\Member;
use AsyncAws\CodeGenerator\Definition\StructureMember;

/**
 * EC2 wire-format variant of the query protocol.
 *
 * Differences vs standard query:
 *  - Lists are encoded as `Parent.$index` — no `.member` infix, the list-member
 *    locationName is ignored, and the flattened/non-flattened distinction is
 *    irrelevant (every list serializes positionally).
 *  - Structure-member names prefer `queryName` verbatim, else `ucfirst(locationName)`,
 *    else the PHP key (already PascalCase in AWS EC2 models).
 *
 * Mirrors aws-sdk-php's `Aws\Api\Serializer\Ec2ParamBuilder`.
 *
 * @internal
 */
class Ec2QuerySerializer extends QuerySerializer
{
    protected function getQueryName(Member $member, string $default): string
    {
        if (null !== $member->getQueryName()) {
            return $member->getQueryName();
        }
        if (null !== $locationName = $member->getLocationName()) {
            return ucfirst($locationName);
        }

        return $default;
    }

    protected function getName(Member $member): string
    {
        if (!$member instanceof StructureMember) {
            throw new \RuntimeException('Guessing the name for this member is not yet implemented');
        }

        $name = $this->getQueryName($member, $member->getName());
        $shape = $member->getShape();

        if ($shape instanceof ListShape) {
            return $name;
        }

        if ($shape instanceof MapShape) {
            return $name . ($shape->isFlattened() ? '' : '.entry');
        }

        return $name;
    }
}
