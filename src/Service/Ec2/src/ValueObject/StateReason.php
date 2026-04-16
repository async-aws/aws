<?php

namespace AsyncAws\Ec2\ValueObject;

/**
 * Describes a state change.
 */
final class StateReason
{
    /**
     * The reason code for the state change.
     *
     * @var string|null
     */
    private $code;

    /**
     * The message for the state change.
     *
     * - `Server.InsufficientInstanceCapacity`: There was insufficient capacity available to satisfy the launch request.
     * - `Server.InternalError`: An internal error caused the instance to terminate during launch.
     * - `Server.ScheduledStop`: The instance was stopped due to a scheduled retirement.
     * - `Server.SpotInstanceShutdown`: The instance was stopped because the number of Spot requests with a maximum price
     *   equal to or higher than the Spot price exceeded available capacity or because of an increase in the Spot price.
     * - `Server.SpotInstanceTermination`: The instance was terminated because the number of Spot requests with a maximum
     *   price equal to or higher than the Spot price exceeded available capacity or because of an increase in the Spot
     *   price.
     * - `Client.InstanceInitiatedShutdown`: The instance was shut down from the operating system of the instance.
     * - `Client.InstanceTerminated`: The instance was terminated or rebooted during AMI creation.
     * - `Client.InternalError`: A client error caused the instance to terminate during launch.
     * - `Client.InvalidSnapshot.NotFound`: The specified snapshot was not found.
     * - `Client.UserInitiatedHibernate`: Hibernation was initiated on the instance.
     * - `Client.UserInitiatedShutdown`: The instance was shut down using the Amazon EC2 API.
     * - `Client.VolumeLimitExceeded`: The limit on the number of EBS volumes or total storage was exceeded. Decrease usage
     *   or request an increase in your account limits.
     *
     * @var string|null
     */
    private $message;

    /**
     * @param array{
     *   Code?: string|null,
     *   Message?: string|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->code = $input['Code'] ?? null;
        $this->message = $input['Message'] ?? null;
    }

    /**
     * @param array{
     *   Code?: string|null,
     *   Message?: string|null,
     * }|StateReason $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }
}
