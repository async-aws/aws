<?php

namespace AsyncAws\Lambda\Enum;

final class Runtime
{
    public const DOTNETCORE_1_0 = 'dotnetcore1.0';
    public const DOTNETCORE_2_0 = 'dotnetcore2.0';
    public const DOTNETCORE_2_1 = 'dotnetcore2.1';
    public const DOTNETCORE_3_1 = 'dotnetcore3.1';
    public const DOTNET_10 = 'dotnet10';
    public const DOTNET_6 = 'dotnet6';
    public const DOTNET_8 = 'dotnet8';
    public const GO_1_X = 'go1.x';
    public const JAVA_11 = 'java11';
    public const JAVA_17 = 'java17';
    public const JAVA_21 = 'java21';
    public const JAVA_25 = 'java25';
    public const JAVA_8 = 'java8';
    public const JAVA_8_AL_2 = 'java8.al2';
    public const NODEJS = 'nodejs';
    public const NODEJS_10_X = 'nodejs10.x';
    public const NODEJS_12_X = 'nodejs12.x';
    public const NODEJS_14_X = 'nodejs14.x';
    public const NODEJS_16_X = 'nodejs16.x';
    public const NODEJS_18_X = 'nodejs18.x';
    public const NODEJS_20_X = 'nodejs20.x';
    public const NODEJS_22_X = 'nodejs22.x';
    public const NODEJS_24_X = 'nodejs24.x';
    public const NODEJS_4_3 = 'nodejs4.3';
    public const NODEJS_4_3_EDGE = 'nodejs4.3-edge';
    public const NODEJS_6_10 = 'nodejs6.10';
    public const NODEJS_8_10 = 'nodejs8.10';
    public const PROVIDED = 'provided';
    public const PROVIDED_AL_2 = 'provided.al2';
    public const PROVIDED_AL_2023 = 'provided.al2023';
    public const PYTHON_2_7 = 'python2.7';
    public const PYTHON_3_10 = 'python3.10';
    public const PYTHON_3_11 = 'python3.11';
    public const PYTHON_3_12 = 'python3.12';
    public const PYTHON_3_13 = 'python3.13';
    public const PYTHON_3_14 = 'python3.14';
    public const PYTHON_3_6 = 'python3.6';
    public const PYTHON_3_7 = 'python3.7';
    public const PYTHON_3_8 = 'python3.8';
    public const PYTHON_3_9 = 'python3.9';
    public const RUBY_2_5 = 'ruby2.5';
    public const RUBY_2_7 = 'ruby2.7';
    public const RUBY_3_2 = 'ruby3.2';
    public const RUBY_3_3 = 'ruby3.3';
    public const RUBY_3_4 = 'ruby3.4';

    public static function exists(string $value): bool
    {
        return isset([
            self::DOTNETCORE_1_0 => true,
            self::DOTNETCORE_2_0 => true,
            self::DOTNETCORE_2_1 => true,
            self::DOTNETCORE_3_1 => true,
            self::DOTNET_10 => true,
            self::DOTNET_6 => true,
            self::DOTNET_8 => true,
            self::GO_1_X => true,
            self::JAVA_11 => true,
            self::JAVA_17 => true,
            self::JAVA_21 => true,
            self::JAVA_25 => true,
            self::JAVA_8 => true,
            self::JAVA_8_AL_2 => true,
            self::NODEJS => true,
            self::NODEJS_10_X => true,
            self::NODEJS_12_X => true,
            self::NODEJS_14_X => true,
            self::NODEJS_16_X => true,
            self::NODEJS_18_X => true,
            self::NODEJS_20_X => true,
            self::NODEJS_22_X => true,
            self::NODEJS_24_X => true,
            self::NODEJS_4_3 => true,
            self::NODEJS_4_3_EDGE => true,
            self::NODEJS_6_10 => true,
            self::NODEJS_8_10 => true,
            self::PROVIDED => true,
            self::PROVIDED_AL_2 => true,
            self::PROVIDED_AL_2023 => true,
            self::PYTHON_2_7 => true,
            self::PYTHON_3_10 => true,
            self::PYTHON_3_11 => true,
            self::PYTHON_3_12 => true,
            self::PYTHON_3_13 => true,
            self::PYTHON_3_14 => true,
            self::PYTHON_3_6 => true,
            self::PYTHON_3_7 => true,
            self::PYTHON_3_8 => true,
            self::PYTHON_3_9 => true,
            self::RUBY_2_5 => true,
            self::RUBY_2_7 => true,
            self::RUBY_3_2 => true,
            self::RUBY_3_3 => true,
            self::RUBY_3_4 => true,
        ][$value]);
    }
}
