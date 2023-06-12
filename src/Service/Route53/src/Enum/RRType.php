<?php

namespace AsyncAws\Route53\Enum;

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
