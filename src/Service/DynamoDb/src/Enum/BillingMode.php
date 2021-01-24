<?php

namespace AsyncAws\DynamoDb\Enum;

/**
 * Controls how you are charged for read and write throughput and how you manage capacity. This setting can be changed
 * later.
 *
 * - `PROVISIONED` - We recommend using `PROVISIONED` for predictable workloads. `PROVISIONED` sets the billing mode to
 *   Provisioned Mode.
 * - `PAY_PER_REQUEST` - We recommend using `PAY_PER_REQUEST` for unpredictable workloads. `PAY_PER_REQUEST` sets the
 *   billing mode to On-Demand Mode.
 *
 * @see https://docs.aws.amazon.com/amazondynamodb/latest/developerguide/HowItWorks.ReadWriteCapacityMode.html#HowItWorks.ProvisionedThroughput.Manual
 * @see https://docs.aws.amazon.com/amazondynamodb/latest/developerguide/HowItWorks.ReadWriteCapacityMode.html#HowItWorks.OnDemand
 */
final class BillingMode
{
    public const PAY_PER_REQUEST = 'PAY_PER_REQUEST';
    public const PROVISIONED = 'PROVISIONED';

    public static function exists(string $value): bool
    {
        return isset([
            self::PAY_PER_REQUEST => true,
            self::PROVISIONED => true,
        ][$value]);
    }
}
