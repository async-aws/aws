<?php

declare(strict_types=1);

namespace AsyncAws\Symfony\DependencyInjection;

class AwsPackagesProvider
{
    public static function getAllServices(): array
    {
        return [
            's3' => [
                'client' => \AsyncAws\S3\S3Client::class,
                'package' => 'async-aws/s3',
            ],
            'ses' => [
                'client' => \AsyncAws\Ses\SesClient::class,
                'package' => 'async-aws/ses',
            ],
            'sqs' => [
                'client' => \AsyncAws\Sqs\SqsClient::class,
                'package' => 'async-aws/sqs',
            ],
            'sts' => [
                'client' => \AsyncAws\Core\Sts\StsClient::class,
                'package' => 'async-aws/core',
            ],
            'sns' => [
                'client' => \AsyncAws\Sns\SnsClient::class,
                'package' => 'async-aws/sns',
            ],
        ];
    }

    public static function getServiceNames(): array
    {
        $services = self::getAllServices();

        return array_keys($services);
    }
}
