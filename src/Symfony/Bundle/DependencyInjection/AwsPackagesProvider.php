<?php

declare(strict_types=1);

namespace AsyncAws\Symfony\Bundle\DependencyInjection;

class AwsPackagesProvider
{
    public static function getAllServices(): array
    {
        return [
            'cloud_formation' => [
                'class' => \AsyncAws\CloudFormation\CloudFormationClient::class,
                'package' => 'async-aws/cloud-formation',
            ],
            'lambda' => [
                'class' => \AsyncAws\Lambda\LambdaClient::class,
                'package' => 'async-aws/lambda',
            ],
            's3' => [
                'class' => \AsyncAws\S3\S3Client::class,
                'package' => 'async-aws/s3',
            ],
            'ses' => [
                'class' => \AsyncAws\Ses\SesClient::class,
                'package' => 'async-aws/ses',
            ],
            'sns' => [
                'class' => \AsyncAws\Sns\SnsClient::class,
                'package' => 'async-aws/sns',
            ],
            'sqs' => [
                'class' => \AsyncAws\Sqs\SqsClient::class,
                'package' => 'async-aws/sqs',
            ],
            'sts' => [
                'class' => \AsyncAws\Core\Sts\StsClient::class,
                'package' => 'async-aws/core',
            ],
        ];
    }

    public static function getServiceNames(): array
    {
        $services = self::getAllServices();

        return array_keys($services);
    }
}
