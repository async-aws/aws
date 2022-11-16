<?php

namespace AsyncAws\Scheduler\Enum;

/**
 * Specifies whether to propagate the tags from the task definition to the task. If no value is specified, the tags are
 * not propagated. Tags can only be propagated to the task during task creation. To add tags to a task after task
 * creation, use Amazon ECS's `TagResource` API action.
 *
 * @see https://docs.aws.amazon.com/AmazonECS/latest/APIReference/API_TagResource.html
 */
final class PropagateTags
{
    public const TASK_DEFINITION = 'TASK_DEFINITION';

    public static function exists(string $value): bool
    {
        return isset([
            self::TASK_DEFINITION => true,
        ][$value]);
    }
}
