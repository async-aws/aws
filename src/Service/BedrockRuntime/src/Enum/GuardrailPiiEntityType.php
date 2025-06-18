<?php

namespace AsyncAws\BedrockRuntime\Enum;

final class GuardrailPiiEntityType
{
    public const ADDRESS = 'ADDRESS';
    public const AGE = 'AGE';
    public const AWS_ACCESS_KEY = 'AWS_ACCESS_KEY';
    public const AWS_SECRET_KEY = 'AWS_SECRET_KEY';
    public const CA_HEALTH_NUMBER = 'CA_HEALTH_NUMBER';
    public const CA_SOCIAL_INSURANCE_NUMBER = 'CA_SOCIAL_INSURANCE_NUMBER';
    public const CREDIT_DEBIT_CARD_CVV = 'CREDIT_DEBIT_CARD_CVV';
    public const CREDIT_DEBIT_CARD_EXPIRY = 'CREDIT_DEBIT_CARD_EXPIRY';
    public const CREDIT_DEBIT_CARD_NUMBER = 'CREDIT_DEBIT_CARD_NUMBER';
    public const DRIVER_ID = 'DRIVER_ID';
    public const EMAIL = 'EMAIL';
    public const INTERNATIONAL_BANK_ACCOUNT_NUMBER = 'INTERNATIONAL_BANK_ACCOUNT_NUMBER';
    public const IP_ADDRESS = 'IP_ADDRESS';
    public const LICENSE_PLATE = 'LICENSE_PLATE';
    public const MAC_ADDRESS = 'MAC_ADDRESS';
    public const NAME = 'NAME';
    public const PASSWORD = 'PASSWORD';
    public const PHONE = 'PHONE';
    public const PIN = 'PIN';
    public const SWIFT_CODE = 'SWIFT_CODE';
    public const UK_NATIONAL_HEALTH_SERVICE_NUMBER = 'UK_NATIONAL_HEALTH_SERVICE_NUMBER';
    public const UK_NATIONAL_INSURANCE_NUMBER = 'UK_NATIONAL_INSURANCE_NUMBER';
    public const UK_UNIQUE_TAXPAYER_REFERENCE_NUMBER = 'UK_UNIQUE_TAXPAYER_REFERENCE_NUMBER';
    public const URL = 'URL';
    public const USERNAME = 'USERNAME';
    public const US_BANK_ACCOUNT_NUMBER = 'US_BANK_ACCOUNT_NUMBER';
    public const US_BANK_ROUTING_NUMBER = 'US_BANK_ROUTING_NUMBER';
    public const US_INDIVIDUAL_TAX_IDENTIFICATION_NUMBER = 'US_INDIVIDUAL_TAX_IDENTIFICATION_NUMBER';
    public const US_PASSPORT_NUMBER = 'US_PASSPORT_NUMBER';
    public const US_SOCIAL_SECURITY_NUMBER = 'US_SOCIAL_SECURITY_NUMBER';
    public const VEHICLE_IDENTIFICATION_NUMBER = 'VEHICLE_IDENTIFICATION_NUMBER';

    public static function exists(string $value): bool
    {
        return isset([
            self::ADDRESS => true,
            self::AGE => true,
            self::AWS_ACCESS_KEY => true,
            self::AWS_SECRET_KEY => true,
            self::CA_HEALTH_NUMBER => true,
            self::CA_SOCIAL_INSURANCE_NUMBER => true,
            self::CREDIT_DEBIT_CARD_CVV => true,
            self::CREDIT_DEBIT_CARD_EXPIRY => true,
            self::CREDIT_DEBIT_CARD_NUMBER => true,
            self::DRIVER_ID => true,
            self::EMAIL => true,
            self::INTERNATIONAL_BANK_ACCOUNT_NUMBER => true,
            self::IP_ADDRESS => true,
            self::LICENSE_PLATE => true,
            self::MAC_ADDRESS => true,
            self::NAME => true,
            self::PASSWORD => true,
            self::PHONE => true,
            self::PIN => true,
            self::SWIFT_CODE => true,
            self::UK_NATIONAL_HEALTH_SERVICE_NUMBER => true,
            self::UK_NATIONAL_INSURANCE_NUMBER => true,
            self::UK_UNIQUE_TAXPAYER_REFERENCE_NUMBER => true,
            self::URL => true,
            self::USERNAME => true,
            self::US_BANK_ACCOUNT_NUMBER => true,
            self::US_BANK_ROUTING_NUMBER => true,
            self::US_INDIVIDUAL_TAX_IDENTIFICATION_NUMBER => true,
            self::US_PASSPORT_NUMBER => true,
            self::US_SOCIAL_SECURITY_NUMBER => true,
            self::VEHICLE_IDENTIFICATION_NUMBER => true,
        ][$value]);
    }
}
