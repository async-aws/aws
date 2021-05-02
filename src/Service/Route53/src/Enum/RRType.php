<?php

namespace AsyncAws\Route53\Enum;

/**
 * The type of resource record set to begin the record listing from.
 * Valid values for basic resource record sets: `A` | `AAAA` | `CAA` | `CNAME` | `MX` | `NAPTR` | `NS` | `PTR` | `SOA` |
 * `SPF` | `SRV` | `TXT`
 * Values for weighted, latency, geolocation, and failover resource record sets: `A` | `AAAA` | `CAA` | `CNAME` | `MX` |
 * `NAPTR` | `PTR` | `SPF` | `SRV` | `TXT`
 * Values for alias resource record sets:.
 *
 * - **API Gateway custom regional API or edge-optimized API**: A
 * - **CloudFront distribution**: A or AAAA
 * - **Elastic Beanstalk environment that has a regionalized subdomain**: A
 * - **Elastic Load Balancing load balancer**: A | AAAA
 * - **S3 bucket**: A
 * - **VPC interface VPC endpoint**: A
 * - **Another resource record set in this hosted zone:** The type of the resource record set that the alias references.
 *
 * Constraint: Specifying `type` without specifying `name` returns an `InvalidInput` error.
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
