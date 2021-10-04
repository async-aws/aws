<?php

declare(strict_types=1);

namespace AsyncAws\Symfony\Bundle\DependencyInjection;

class AwsPackagesProvider
{
    public static function getAllServices(): array
    {
        return [
            'app_sync' => [
                'class' => \AsyncAws\AppSync\AppSyncClient::class,
                'package' => 'async-aws/app-sync',
            ],
            'cloud_formation' => [
                'class' => \AsyncAws\CloudFormation\CloudFormationClient::class,
                'package' => 'async-aws/cloud-formation',
            ],
            'cloud_front' => [
                'class' => \AsyncAws\CloudFront\CloudFrontClient::class,
                'package' => 'async-aws/cloud-front',
            ],
            'cloud_watch' => [
                'class' => \AsyncAws\CloudWatch\CloudWatchClient::class,
                'package' => 'async-aws/cloud-watch',
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
            'ecr' => [
                'class' => \AsyncAws\Ecr\EcrClient::class,
                'package' => 'async-aws/ecr',
            ],
            'elasti_cache' => [
                'class' => \AsyncAws\ElastiCache\ElastiCacheClient::class,
                'package' => 'async-aws/elasti-cache',
            ],
            'event_bridge' => [
                'class' => \AsyncAws\EventBridge\EventBridgeClient::class,
                'package' => 'async-aws/event-bridge',
            ],
            'firehose' => [
                'class' => \AsyncAws\Firehose\FirehoseClient::class,
                'package' => 'async-aws/firehose',
            ],
            'iam' => [
                'class' => \AsyncAws\Iam\IamClient::class,
                'package' => 'async-aws/iam',
            ],
            'kinesis' => [
                'class' => \AsyncAws\Kinesis\KinesisClient::class,
                'package' => 'async-aws/kinesis',
            ],
            'lambda' => [
                'class' => \AsyncAws\Lambda\LambdaClient::class,
                'package' => 'async-aws/lambda',
            ],
            'rds_data_service' => [
                'class' => \AsyncAws\RdsDataService\RdsDataServiceClient::class,
                'package' => 'async-aws/rds-data-service',
            ],
            'rekognition' => [
                'class' => \AsyncAws\Rekognition\RekognitionClient::class,
                'package' => 'async-aws/rekognition',
            ],
            'route53' => [
                'class' => \AsyncAws\Route53\Route53Client::class,
                'package' => 'async-aws/route53',
            ],
            's3' => [
                'class' => \AsyncAws\S3\S3Client::class,
                'package' => 'async-aws/s3',
            ],
            'simple_s3' => [
                'class' => \AsyncAws\SimpleS3\SimpleS3Client::class,
                'package' => 'async-aws/simple-s3',
            ],
            'secrets_manager' => [
                'class' => \AsyncAws\SecretsManager\SecretsManagerClient::class,
                'package' => 'async-aws/secrets-manager',
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
            'step_functions' => [
                'class' => \AsyncAws\StepFunctions\StepFunctionsClient::class,
                'package' => 'async-aws/step-functions',
            ],
        ];
    }

    public static function getServiceNames(): array
    {
        $services = self::getAllServices();

        return array_keys($services);
    }
}
