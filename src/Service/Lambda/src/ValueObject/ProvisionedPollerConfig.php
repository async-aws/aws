<?php

namespace AsyncAws\Lambda\ValueObject;

/**
 * The provisioned mode [^1] configuration for the event source. Use Provisioned Mode to customize the minimum and
 * maximum number of event pollers for your event source.
 *
 * [^1]: https://docs.aws.amazon.com/lambda/latest/dg/invocation-eventsourcemapping.html#invocation-eventsourcemapping-provisioned-mode
 */
final class ProvisionedPollerConfig
{
    /**
     * The minimum number of event pollers this event source can scale down to. For Amazon SQS events source mappings,
     * default is 2, and minimum 2 required. For Amazon MSK and self-managed Apache Kafka event source mappings, default is
     * 1.
     *
     * @var int|null
     */
    private $minimumPollers;

    /**
     * The maximum number of event pollers this event source can scale up to. For Amazon SQS events source mappings, default
     * is 200, and minimum value allowed is 2. For Amazon MSK and self-managed Apache Kafka event source mappings, default
     * is 200, and minimum value allowed is 1.
     *
     * @var int|null
     */
    private $maximumPollers;

    /**
     * (Amazon MSK and self-managed Apache Kafka) The name of the provisioned poller group. Use this option to group
     * multiple ESMs within the event source's VPC to share Event Poller Unit (EPU) capacity. You can use this option to
     * optimize Provisioned mode costs for your ESMs. You can group up to 100 ESMs per poller group and aggregate maximum
     * pollers across all ESMs in a group cannot exceed 2000.
     *
     * @var string|null
     */
    private $pollerGroupName;

    /**
     * @param array{
     *   MinimumPollers?: int|null,
     *   MaximumPollers?: int|null,
     *   PollerGroupName?: string|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->minimumPollers = $input['MinimumPollers'] ?? null;
        $this->maximumPollers = $input['MaximumPollers'] ?? null;
        $this->pollerGroupName = $input['PollerGroupName'] ?? null;
    }

    /**
     * @param array{
     *   MinimumPollers?: int|null,
     *   MaximumPollers?: int|null,
     *   PollerGroupName?: string|null,
     * }|ProvisionedPollerConfig $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getMaximumPollers(): ?int
    {
        return $this->maximumPollers;
    }

    public function getMinimumPollers(): ?int
    {
        return $this->minimumPollers;
    }

    public function getPollerGroupName(): ?string
    {
        return $this->pollerGroupName;
    }
}
