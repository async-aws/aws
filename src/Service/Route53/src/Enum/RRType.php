<?php

namespace AsyncAws\Route53\Enum;

/**
 * The DNS record type. For information about different record types and how data is encoded for them, see Supported DNS
 * Resource Record Types in the *Amazon Route 53 Developer Guide*.
 * Valid values for basic resource record sets: `A` | `AAAA` | `CAA` | `CNAME` | `DS` |`MX` | `NAPTR` | `NS` | `PTR` |
 * `SOA` | `SPF` | `SRV` | `TXT`
 * Values for weighted, latency, geolocation, and failover resource record sets: `A` | `AAAA` | `CAA` | `CNAME` | `MX` |
 * `NAPTR` | `PTR` | `SPF` | `SRV` | `TXT`. When creating a group of weighted, latency, geolocation, or failover
 * resource record sets, specify the same value for all of the resource record sets in the group.
 * Valid values for multivalue answer resource record sets: `A` | `AAAA` | `MX` | `NAPTR` | `PTR` | `SPF` | `SRV` |
 * `TXT`.
 *
 * > SPF records were formerly used to verify the identity of the sender of email messages. However, we no longer
 * > recommend that you create resource record sets for which the value of `Type` is `SPF`. RFC 7208, *Sender Policy
 * > Framework (SPF) for Authorizing Use of Domains in Email, Version 1*, has been updated to say, "...[I]ts existence
 * > and mechanism defined in [RFC4408] have led to some interoperability issues. Accordingly, its use is no longer
 * > appropriate for SPF version 1; implementations are not to use it." In RFC 7208, see section 14.1, The SPF DNS
 * > Record Type.
 *
 * Values for alias resource record sets:
 *
 * - **Amazon API Gateway custom regional APIs and edge-optimized APIs:**`A`
 * - **CloudFront distributions:**`A`
 *   If IPv6 is enabled for the distribution, create two resource record sets to route traffic to your distribution, one
 *   with a value of `A` and one with a value of `AAAA`.
 * - **Amazon API Gateway environment that has a regionalized subdomain**: `A`
 * - **ELB load balancers:**`A` | `AAAA`
 * - **Amazon S3 buckets:**`A`
 * - **Amazon Virtual Private Cloud interface VPC endpoints**`A`
 * - **Another resource record set in this hosted zone:** Specify the type of the resource record set that you're
 *   creating the alias for. All values are supported except `NS` and `SOA`.
 *
 *   > If you're creating an alias record that has the same name as the hosted zone (known as the zone apex), you can't
 *   > route traffic to a record for which the value of `Type` is `CNAME`. This is because the alias record must have
 *   > the same type as the record you're routing traffic to, and creating a CNAME record for the zone apex isn't
 *   > supported even for an alias record.
 *
 * @see https://docs.aws.amazon.com/Route53/latest/DeveloperGuide/ResourceRecordTypes.html
 * @see http://tools.ietf.org/html/rfc7208#section-14.1
 */
final class RRType
{
    public const A = 'A';
    public const AAAA = 'AAAA';
    public const CAA = 'CAA';
    public const CNAME = 'CNAME';
    public const DS = 'DS';
    public const MX = 'MX';
    public const NAPTR = 'NAPTR';
    public const NS = 'NS';
    public const PTR = 'PTR';
    public const SOA = 'SOA';
    public const SPF = 'SPF';
    public const SRV = 'SRV';
    public const TXT = 'TXT';

    public static function exists(string $value): bool
    {
        return isset([
            self::A => true,
            self::AAAA => true,
            self::CAA => true,
            self::CNAME => true,
            self::DS => true,
            self::MX => true,
            self::NAPTR => true,
            self::NS => true,
            self::PTR => true,
            self::SOA => true,
            self::SPF => true,
            self::SRV => true,
            self::TXT => true,
        ][$value]);
    }
}
