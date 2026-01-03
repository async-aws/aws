---
category: clients
---

# Clients overview

AsyncAws has implemented the most popular API clients. On this page you find
some installation instructions and a complete list of clients.

## Install and configure

You install a client with Composer. Here is an example to install DynamoDb:

```shell
composer require async-aws/dynamo-db
```

To instantiate a DynamoDb client (or any other client) you could provide four arguments.
All arguments are optional and sensible defaults are used.

```php
use AsyncAws\Core\Configuration;
use AsyncAws\Core\Credentials\ConfigurationProvider;
use AsyncAws\Core\HttpClient\AwsHttpClientFactory;
use AsyncAws\DynamoDb\DynamoDbClient;

$config = Configuration::create([
    'region' => 'eu-central-1',
]);
$credentialProvider = new ConfigurationProvider();
$httpClient = HttpClient::create(); // ... Instance of Symfony's HttpClientInterface
// Or, to enable automatic retries on top of your own HttpClient
// $httpClient = AwsHttpClientFactory::createRetryableClient($httpClient); T
$logger = // ... A PSR-3 logger

$dynamoDb = new DynamoDbClient($config, $credentialProvider, $httpClient, $logger);
```

A common way to instantiate a DynamoDb client might look like:

```php
use AsyncAws\DynamoDb\DynamoDbClient;

$dynamoDb = new DynamoDbClient([
   'region' => 'eu-central-1',
   'accessKeyId' => 'AKIAIOSFODNN7EXAMPLE',
   'accessKeySecret' => 'wJalrXUtnFEMI/K7MDENG/bPxRfiCYEXAMPLEKEY',
]);
```

See section about [authentication](/authentication/index.md) to learn more about
different ways to authenticate.

### Dependency injection container

If your application is using a dependency injection container, you may configure a
client like:

#### Symfony

```yaml
services:
    AsyncAws\DynamoDb\DynamoDbClient:
        arguments:
            - region: "eu-central-1"
              accessKeyId: "AKIAIOSFODNN7EXAMPLE"
              accessKeySecret: "wJalrXUtnFEMI/K7MDENG/bPxRfiCYEXAMPLEKEY"
            - ~ # Use the default authentication providers
            - "@http_client"
            - "@logger"
```

If you are using Symfony you may ease configuration by install the [Symfony Bundle](/integration/symfony-bundle.md).

#### Laravel

```php
use Illuminate\Support\ServiceProvider;
use AsyncAws\DynamoDb\DynamoDbClient;

class AsyncAwsProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(
            DynamoDbClient::class,
            function($app) {
                return new DynamoDbClient([
                   'region' => 'eu-central-1',
                   'accessKeyId' => 'AKIAIOSFODNN7EXAMPLE',
                   'accessKeySecret' => 'wJalrXUtnFEMI/K7MDENG/bPxRfiCYEXAMPLEKEY',
                ]);
            }
        );
    }
}
```

### Client factory

If you don't use dependency injection, you might be interested in the `AwsClientFactory`
that can be used to instantiate API clients.

```php
use AsyncAws\Core\AwsClientFactory;

$factory = new AwsClientFactory([
   'region' => 'eu-central-1',
   'accessKeyId' => 'AKIAIOSFODNN7EXAMPLE',
   'accessKeySecret' => 'wJalrXUtnFEMI/K7MDENG/bPxRfiCYEXAMPLEKEY',
]);

$dynamoDb = $factory->dynamoDb();
$sqs = $factory->sqs();
```

## Use

Every API client has functions that represent "operations" or API calls. Every such
function takes an Input object (or array) as parameter and will return a Result.
See the API client's class to learn what operations are supported and what the input
and output are.

All Input objects for SQS is located in [`AsyncAws\Sqs\Input\*`](https://github.com/async-aws/aws/tree/master/src/Service/Sqs/src/Input)
and all Result objects are located in [`AsyncAws\Sqs\Result\*`](https://github.com/async-aws/aws/tree/master/src/Service/Sqs/src/Result).

Your IDE will also be helpful to discover function and how to use them. See example
from PHPStorm:

[![PHPStorm function help](/assets/image/compare/aa-method.png)](/assets/image/compare/aa-method.png)

## All supported clients

Here is a list of supported clients. If there is a need for another client or a new
operation, it can be automatically generated. See the [contribution guide](/contribute/index.md)
for more information.

| Api Client                                  | Package name                                                                                              |
|---------------------------------------------|-----------------------------------------------------------------------------------------------------------|
| [AppSync](./app-sync.md)                    | [async-aws/app-sync](https://packagist.org/packages/async-aws/app-sync)                                   |
| [Athena](./athena.md)                       | [async-aws/athena](https://packagist.org/packages/async-aws/athena)                                       |
| [BedrockAgent](./bedrock-agent.md)          | [async-aws/bedrock-agent](https://packagist.org/packages/async-aws/bedrock-agent)                         |
| [BedrockRuntime](./bedrock-runtime.md)      | [async-aws/bedrock-runtime](https://packagist.org/packages/async-aws/bedrock-runtime)                     |
| [CloudFormation](./cf.md)                   | [async-aws/cloud-formation](https://packagist.org/packages/async-aws/cloud-formation)                     |
| [CloudFront](./cloud-front.md)              | [async-aws/cloud-front](https://packagist.org/packages/async-aws/cloud-front)                             |
| [CloudWatch](./cloud-watch.md)              | [async-aws/cloud-watch](https://packagist.org/packages/async-aws/cloud-watch)                             |
| [CloudWatchLogs](./cloud-watch-logs.md)     | [async-aws/cloud-watch-logs](https://packagist.org/packages/async-aws/cloud-watch-logs)                   |
| [CodeBuild](./code-build.md)                | [async-aws/code-build](https://packagist.org/packages/async-aws/code-build)                               |
| [CodeCommit](./code-commit.md)              | [async-aws/code-commit](https://packagist.org/packages/async-aws/code-commit)                             |
| [CodeDeploy](./code-deploy.md)              | [async-aws/code-deploy](https://packagist.org/packages/async-aws/code-deploy)                             |
| [CognitoIdentityProvider](./cognito-idp.md) | [async-aws/cognito-identity-provider](https://packagist.org/packages/async-aws/cognito-identity-provider) |
| [Comprehend](./comprehend.md)               | [async-aws/comprehend](https://packagist.org/packages/async-aws/comprehend)                               |
| [DynamoDb](./dynamodb.md)                   | [async-aws/dynamo-db](https://packagist.org/packages/async-aws/dynamo-db)                                 |
| [ECR](./ecr.md)                             | [async-aws/ecr](https://packagist.org/packages/async-aws/ecr)                                             |
| [ElastiCache](./elasti-cache.md)            | [async-aws/elasti-cache](https://packagist.org/packages/async-aws/elasti-cache)                           |
| [EventBridge](./event-bridge.md)            | [async-aws/event-bridge](https://packagist.org/packages/async-aws/event-bridge)                           |
| [Firehose](./firehose.md)                   | [async-aws/event-bridge](https://packagist.org/packages/async-aws/firehose)                               |
| [IAM](./iam.md)                             | [async-aws/iam](https://packagist.org/packages/async-aws/iam)                                             |
| [Iot](./iot.md)                             | [async-aws/iot](https://packagist.org/packages/async-aws/iot)                                             |
| [IotData](./iot-data.md)                    | [async-aws/iot-data](https://packagist.org/packages/async-aws/iot-data)                                   |
| [Kinesis](./kinesis.md)                     | [async-aws/kinesis](https://packagist.org/packages/async-aws/kinesis)                                     |
| [KMS](./kms.md)                             | [async-aws/kms](https://packagist.org/packages/async-aws/kms)                                             |
| [Lambda](./lambda.md)                       | [async-aws/lambda](https://packagist.org/packages/async-aws/lambda)                                       |
| [LocationService](./location-service.md)    | [async-aws/location-service](https://packagist.org/packages/async-aws/location-service)                   |
| [MediaConvert](./media-convert.md)          | [async-aws/media-convert](https://packagist.org/packages/async-aws/media-convert)                         |
| [RdsDataService](./rds-data-service.md)     | [async-aws/rds-data-service](https://packagist.org/packages/async-aws/rds-data-service)                   |
| [Rekognition](./rekognition.md)             | [async-aws/rekognition](https://packagist.org/packages/async-aws/rekognition)                             |
| [Route53](./route53.md)                     | [async-aws/route53](https://packagist.org/packages/async-aws/route53)                                     |
| [S3](./s3.md)                               | [async-aws/s3](https://packagist.org/packages/async-aws/s3)                                               |
| [S3Vectors](./s3-vectors.md)                | [async-aws/s3-vectors](https://packagist.org/packages/async-aws/s3-vectors)                               |
| [Scheduler](./scheduler.md)                 | [async-aws/scheduler](https://packagist.org/packages/async-aws/scheduler)                                 |
| [SecretsManager](./secrets-manager.md)      | [async-aws/secrets-manager](https://packagist.org/packages/async-aws/secrets-manager)                     |
| [SES](./ses.md)                             | [async-aws/ses](https://packagist.org/packages/async-aws/ses)                                             |
| [SNS](./sns.md)                             | [async-aws/sns](https://packagist.org/packages/async-aws/sns)                                             |
| [SQS](./sqs.md)                             | [async-aws/sqs](https://packagist.org/packages/async-aws/sqs)                                             |
| [SSM](./ssm.md)                             | [async-aws/ssm](https://packagist.org/packages/async-aws/ssm)                                             |
| [SSO](./sso.md)                             | [async-aws/sso](https://packagist.org/packages/async-aws/sso)                                             |
| [SSOOIDC](./sso-oidc.md)                    | [async-aws/sso-oidc](https://packagist.org/packages/async-aws/sso-oidc)                                   |
| [STS](./sts.md)                             | [async-aws/core](https://packagist.org/packages/async-aws/core)                                           |
| [StepFunctions](./step-functions.md)        | [async-aws/step-functions](https://packagist.org/packages/async-aws/step-functions)                       |
| [TimestreamQuery](./timestream-query.md)    | [async-aws/timestream-query](https://packagist.org/packages/async-aws/timestream-query)                   |
| [TimestreamWrite](./timestream-write.md)    | [async-aws/timestream-write](https://packagist.org/packages/async-aws/timestream-write)                   |
| [Translate](./translate.md)                 | [async-aws/translate](https://packagist.org/packages/async-aws/translate)                                 |
| [XRay](./x-ray.md)                          | [async-aws/x-ray](https://packagist.org/packages/async-aws/x-ray)                                         |
