<?php

declare(strict_types=1);

namespace AsyncAws\Symfony\Bundle\DependencyInjection;

class AwsPackagesProvider
{
    public static function getAllServices(): array
    {
        return [
            's3' => [
                'class' => \AsyncAws\S3\S3Client::class,
                'package' => 'async-aws/s3',
            ],
            'ses' => [
                'class' => \AsyncAws\Ses\SesClient::class,
                'package' => 'async-aws/ses',
            ],
            'sqs' => [
                'class' => \AsyncAws\Sqs\SqsClient::class,
                'package' => 'async-aws/sqs',
            ],
            'sts' => [
                'class' => \AsyncAws\Core\Sts\StsClient::class,
                'package' => 'async-aws/core',
            ],
            'sns' => [
                'class' => \AsyncAws\Sns\SnsClient::class,
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
