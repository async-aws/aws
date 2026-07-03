<?php

namespace AsyncAws\Ses\Enum;

final class DkimSigningAttributesOrigin
{
    public const AWS_SES = 'AWS_SES';
    public const AWS_SES_AF_SOUTH_1 = 'AWS_SES_AF_SOUTH_1';
    public const AWS_SES_AP_NORTHEAST_1 = 'AWS_SES_AP_NORTHEAST_1';
    public const AWS_SES_AP_NORTHEAST_2 = 'AWS_SES_AP_NORTHEAST_2';
    public const AWS_SES_AP_NORTHEAST_3 = 'AWS_SES_AP_NORTHEAST_3';
    public const AWS_SES_AP_SOUTHEAST_1 = 'AWS_SES_AP_SOUTHEAST_1';
    public const AWS_SES_AP_SOUTHEAST_2 = 'AWS_SES_AP_SOUTHEAST_2';
    public const AWS_SES_AP_SOUTHEAST_3 = 'AWS_SES_AP_SOUTHEAST_3';
    public const AWS_SES_AP_SOUTHEAST_5 = 'AWS_SES_AP_SOUTHEAST_5';
    public const AWS_SES_AP_SOUTH_1 = 'AWS_SES_AP_SOUTH_1';
    public const AWS_SES_AP_SOUTH_2 = 'AWS_SES_AP_SOUTH_2';
    public const AWS_SES_CA_CENTRAL_1 = 'AWS_SES_CA_CENTRAL_1';
    public const AWS_SES_CA_WEST_1 = 'AWS_SES_CA_WEST_1';
    public const AWS_SES_EU_CENTRAL_1 = 'AWS_SES_EU_CENTRAL_1';
    public const AWS_SES_EU_CENTRAL_2 = 'AWS_SES_EU_CENTRAL_2';
    public const AWS_SES_EU_NORTH_1 = 'AWS_SES_EU_NORTH_1';
    public const AWS_SES_EU_SOUTH_1 = 'AWS_SES_EU_SOUTH_1';
    public const AWS_SES_EU_WEST_1 = 'AWS_SES_EU_WEST_1';
    public const AWS_SES_EU_WEST_2 = 'AWS_SES_EU_WEST_2';
    public const AWS_SES_EU_WEST_3 = 'AWS_SES_EU_WEST_3';
    public const AWS_SES_IL_CENTRAL_1 = 'AWS_SES_IL_CENTRAL_1';
    public const AWS_SES_ME_CENTRAL_1 = 'AWS_SES_ME_CENTRAL_1';
    public const AWS_SES_ME_SOUTH_1 = 'AWS_SES_ME_SOUTH_1';
    public const AWS_SES_SA_EAST_1 = 'AWS_SES_SA_EAST_1';
    public const AWS_SES_US_EAST_1 = 'AWS_SES_US_EAST_1';
    public const AWS_SES_US_EAST_2 = 'AWS_SES_US_EAST_2';
    public const AWS_SES_US_WEST_1 = 'AWS_SES_US_WEST_1';
    public const AWS_SES_US_WEST_2 = 'AWS_SES_US_WEST_2';
    public const EXTERNAL = 'EXTERNAL';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::AWS_SES => true,
            self::AWS_SES_AF_SOUTH_1 => true,
            self::AWS_SES_AP_NORTHEAST_1 => true,
            self::AWS_SES_AP_NORTHEAST_2 => true,
            self::AWS_SES_AP_NORTHEAST_3 => true,
            self::AWS_SES_AP_SOUTHEAST_1 => true,
            self::AWS_SES_AP_SOUTHEAST_2 => true,
            self::AWS_SES_AP_SOUTHEAST_3 => true,
            self::AWS_SES_AP_SOUTHEAST_5 => true,
            self::AWS_SES_AP_SOUTH_1 => true,
            self::AWS_SES_AP_SOUTH_2 => true,
            self::AWS_SES_CA_CENTRAL_1 => true,
            self::AWS_SES_CA_WEST_1 => true,
            self::AWS_SES_EU_CENTRAL_1 => true,
            self::AWS_SES_EU_CENTRAL_2 => true,
            self::AWS_SES_EU_NORTH_1 => true,
            self::AWS_SES_EU_SOUTH_1 => true,
            self::AWS_SES_EU_WEST_1 => true,
            self::AWS_SES_EU_WEST_2 => true,
            self::AWS_SES_EU_WEST_3 => true,
            self::AWS_SES_IL_CENTRAL_1 => true,
            self::AWS_SES_ME_CENTRAL_1 => true,
            self::AWS_SES_ME_SOUTH_1 => true,
            self::AWS_SES_SA_EAST_1 => true,
            self::AWS_SES_US_EAST_1 => true,
            self::AWS_SES_US_EAST_2 => true,
            self::AWS_SES_US_WEST_1 => true,
            self::AWS_SES_US_WEST_2 => true,
            self::EXTERNAL => true,
        ][$value]);
    }
}
