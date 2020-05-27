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
            'cloud_watch_logs' => [
                'class' => \AsyncAws\CloudWatchLogs\CloudWatchLogsClient::class,
                'package' => 'async-aws/cloud-watch-logs',
            ],
            'code_deploy' => [
                'class' => \AsyncAws\CodeDeploy\CodeDeployClient::class,
                'package' => 'async-aws/code-deploy',
            ],
            'cognito_identity_provider' => [
                'class' => \AsyncAws\CognitoIdentityProvider\CognitoIdentityProviderClient::class,
                'package' => 'async-aws/cognito-identity-provider',
            ],
            'dynamo_db' => [
                'class' => \AsyncAws\DynamoDb\DynamoDbClient::class,
                'package' => 'async-aws/dynamo-db',
            ],
            'event_bridge' => [
                'class' => \AsyncAws\EventBridge\EventBridgeClient::class,
                'package' => 'async-aws/event-bridge',
            ],
            'iam' => [
                'class' => \AsyncAws\Iam\IamClient::class,
                'package' => 'async-aws/iam',
            ],
            'lambda' => [
                'class' => \AsyncAws\Lambda\LambdaClient::class,
                'package' => 'async-aws/lambda',
            ],
            's3' => [
                'class' => \AsyncAws\S3\S3Client::class,
                'package' => 'async-aws/s3',
            ],
            'simple_s3' => [
                'class' => \AsyncAws\SimpleS3\SimpleS3Client::class,
                'package' => 'async-aws/simple-s3',
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
            'ssm' => [
                'class' => \AsyncAws\Ssm\SsmClient::class,
                'package' => 'async-aws/ssm',
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
