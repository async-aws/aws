<?php

namespace AsyncAws\S3\Enum;

class Type
{
    public const AMAZON_CUSTOMER_BY_EMAIL = 'AmazonCustomerByEmail';
    public const CANONICAL_USER = 'CanonicalUser';
    public const GROUP = 'Group';
    public const AVAILABLE_TYPE = [
        self::AMAZON_CUSTOMER_BY_EMAIL => true,
        self::CANONICAL_USER => true,
        self::GROUP => true,
    ];
}
