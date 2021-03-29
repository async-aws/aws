<?php

namespace AsyncAws\Lambda\Enum;

/**
 * The runtime environment for the Lambda function.
 */
final class Runtime
{
    public const DOTNETCORE_1_0 = 'dotnetcore1.0';
    public const DOTNETCORE_2_0 = 'dotnetcore2.0';
    public const DOTNETCORE_2_1 = 'dotnetcore2.1';
    public const DOTNETCORE_3_1 = 'dotnetcore3.1';
    public const GO_1_X = 'go1.x';
    public const JAVA_11 = 'java11';
    public const JAVA_8 = 'java8';
    public const JAVA_8_AL_2 = 'java8.al2';
    public const NODEJS = 'nodejs';
    public const NODEJS_10_X = 'nodejs10.x';
    public const NODEJS_12_X = 'nodejs12.x';
    public const NODEJS_14_X = 'nodejs14.x';
    public const NODEJS_4_3 = 'nodejs4.3';
    public const NODEJS_4_3_EDGE = 'nodejs4.3-edge';
    public const NODEJS_6_10 = 'nodejs6.10';
    public const NODEJS_8_10 = 'nodejs8.10';
    public const PROVIDED = 'provided';
    public const PROVIDED_AL_2 = 'provided.al2';
    public const PYTHON_2_7 = 'python2.7';
    public const PYTHON_3_6 = 'python3.6';
    public const PYTHON_3_7 = 'python3.7';
    public const PYTHON_3_8 = 'python3.8';
    public const RUBY_2_5 = 'ruby2.5';
    public const RUBY_2_7 = 'ruby2.7';

    public static function exists(string $value): bool
    {
        return isset([
            self::DOTNETCORE_1_0 => true,
            self::DOTNETCORE_2_0 => true,
            self::DOTNETCORE_2_1 => true,
            self::DOTNETCORE_3_1 => true,
            self::GO_1_X => true,
            self::JAVA_11 => true,
            self::JAVA_8 => true,
            self::JAVA_8_AL_2 => true,
            self::NODEJS => true,
            self::NODEJS_10_X => true,
            self::NODEJS_12_X => true,
            self::NODEJS_14_X => true,
            self::NODEJS_4_3 => true,
            self::NODEJS_4_3_EDGE => true,
            self::NODEJS_6_10 => true,
            self::NODEJS_8_10 => true,
            self::PROVIDED => true,
            self::PROVIDED_AL_2 => true,
            self::PYTHON_2_7 => true,
            self::PYTHON_3_6 => true,
            self::PYTHON_3_7 => true,
            self::PYTHON_3_8 => true,
            self::RUBY_2_5 => true,
            self::RUBY_2_7 => true,
        ][$value]);
    }
}
