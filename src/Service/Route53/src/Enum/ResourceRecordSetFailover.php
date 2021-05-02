<?php

namespace AsyncAws\Route53\Enum;

/**
 * *Failover resource record sets only:* To configure failover, you add the `Failover` element to two resource record
 * sets. For one resource record set, you specify `PRIMARY` as the value for `Failover`; for the other resource record
 * set, you specify `SECONDARY`. In addition, you include the `HealthCheckId` element and specify the health check that
 * you want Amazon Route 53 to perform for each resource record set.
 * Except where noted, the following failover behaviors assume that you have included the `HealthCheckId` element in
 * both resource record sets:.
 *
 * - When the primary resource record set is healthy, Route 53 responds to DNS queries with the applicable value from
 *   the primary resource record set regardless of the health of the secondary resource record set.
 * - When the primary resource record set is unhealthy and the secondary resource record set is healthy, Route 53
 *   responds to DNS queries with the applicable value from the secondary resource record set.
 * - When the secondary resource record set is unhealthy, Route 53 responds to DNS queries with the applicable value
 *   from the primary resource record set regardless of the health of the primary resource record set.
 * - If you omit the `HealthCheckId` element for the secondary resource record set, and if the primary resource record
 *   set is unhealthy, Route 53 always responds to DNS queries with the applicable value from the secondary resource
 *   record set. This is true regardless of the health of the associated endpoint.
 *
 * You can't create non-failover resource record sets that have the same values for the `Name` and `Type` elements as
 * failover resource record sets.
 * For failover alias resource record sets, you must also include the `EvaluateTargetHealth` element and set the value
 * to true.
 * For more information about configuring failover for Route 53, see the following topics in the *Amazon Route 53
 * Developer Guide*:
 *
 * - Route 53 Health Checks and DNS Failover
 * - Configuring Failover in a Private Hosted Zone
 *
 * @see https://docs.aws.amazon.com/Route53/latest/DeveloperGuide/dns-failover.html
 * @see https://docs.aws.amazon.com/Route53/latest/DeveloperGuide/dns-failover-private-hosted-zones.html
 */
final class ResourceRecordSetFailover
{
    public const PRIMARY = 'PRIMARY';
    public const SECONDARY = 'SECONDARY';

    public static function exists(string $value): bool
    {
        return isset([
            self::PRIMARY => true,
            self::SECONDARY => true,
        ][$value]);
    }
}
