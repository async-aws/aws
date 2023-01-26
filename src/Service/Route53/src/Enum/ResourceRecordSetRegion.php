<?php

namespace AsyncAws\Route53\Enum;

/**
 * *Latency-based resource record sets only:* The Amazon EC2 Region where you created the resource that this resource
 * record set refers to. The resource typically is an Amazon Web Services resource, such as an EC2 instance or an ELB
 * load balancer, and is referred to by an IP address or a DNS domain name, depending on the record type.
 * When Amazon Route 53 receives a DNS query for a domain name and type for which you have created latency resource
 * record sets, Route 53 selects the latency resource record set that has the lowest latency between the end user and
 * the associated Amazon EC2 Region. Route 53 then returns the value that is associated with the selected resource
 * record set.
 * Note the following:.
 *
 * - You can only specify one `ResourceRecord` per latency resource record set.
 * - You can only create one latency resource record set for each Amazon EC2 Region.
 * - You aren't required to create latency resource record sets for all Amazon EC2 Regions. Route 53 will choose the
 *   region with the best latency from among the regions that you create latency resource record sets for.
 * - You can't create non-latency resource record sets that have the same values for the `Name` and `Type` elements as
 *   latency resource record sets.
 */
final class ResourceRecordSetRegion
{
    public const AF_SOUTH_1 = 'af-south-1';
    public const AP_EAST_1 = 'ap-east-1';
    public const AP_NORTHEAST_1 = 'ap-northeast-1';
    public const AP_NORTHEAST_2 = 'ap-northeast-2';
    public const AP_NORTHEAST_3 = 'ap-northeast-3';
    public const AP_SOUTHEAST_1 = 'ap-southeast-1';
    public const AP_SOUTHEAST_2 = 'ap-southeast-2';
    public const AP_SOUTHEAST_3 = 'ap-southeast-3';
    public const AP_SOUTHEAST_4 = 'ap-southeast-4';
    public const AP_SOUTH_1 = 'ap-south-1';
    public const AP_SOUTH_2 = 'ap-south-2';
    public const CA_CENTRAL_1 = 'ca-central-1';
    public const CN_NORTHWEST_1 = 'cn-northwest-1';
    public const CN_NORTH_1 = 'cn-north-1';
    public const EU_CENTRAL_1 = 'eu-central-1';
    public const EU_CENTRAL_2 = 'eu-central-2';
    public const EU_NORTH_1 = 'eu-north-1';
    public const EU_SOUTH_1 = 'eu-south-1';
    public const EU_SOUTH_2 = 'eu-south-2';
    public const EU_WEST_1 = 'eu-west-1';
    public const EU_WEST_2 = 'eu-west-2';
    public const EU_WEST_3 = 'eu-west-3';
    public const ME_CENTRAL_1 = 'me-central-1';
    public const ME_SOUTH_1 = 'me-south-1';
    public const SA_EAST_1 = 'sa-east-1';
    public const US_EAST_1 = 'us-east-1';
    public const US_EAST_2 = 'us-east-2';
    public const US_WEST_1 = 'us-west-1';
    public const US_WEST_2 = 'us-west-2';

    public static function exists(string $value): bool
    {
        return isset([
            self::AF_SOUTH_1 => true,
            self::AP_EAST_1 => true,
            self::AP_NORTHEAST_1 => true,
            self::AP_NORTHEAST_2 => true,
            self::AP_NORTHEAST_3 => true,
            self::AP_SOUTHEAST_1 => true,
            self::AP_SOUTHEAST_2 => true,
            self::AP_SOUTHEAST_3 => true,
            self::AP_SOUTHEAST_4 => true,
            self::AP_SOUTH_1 => true,
            self::AP_SOUTH_2 => true,
            self::CA_CENTRAL_1 => true,
            self::CN_NORTHWEST_1 => true,
            self::CN_NORTH_1 => true,
            self::EU_CENTRAL_1 => true,
            self::EU_CENTRAL_2 => true,
            self::EU_NORTH_1 => true,
            self::EU_SOUTH_1 => true,
            self::EU_SOUTH_2 => true,
            self::EU_WEST_1 => true,
            self::EU_WEST_2 => true,
            self::EU_WEST_3 => true,
            self::ME_CENTRAL_1 => true,
            self::ME_SOUTH_1 => true,
            self::SA_EAST_1 => true,
            self::US_EAST_1 => true,
            self::US_EAST_2 => true,
            self::US_WEST_1 => true,
            self::US_WEST_2 => true,
        ][$value]);
    }
}
