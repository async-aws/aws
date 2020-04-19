---
category: integration
---

# AsyncAws Symfony Bundle

A small SymfonyBundle that helps with configuration and autowiring.

## Install

```shell
composer require async-aws/async-aws-bundle
```

## Configure

The bundle will autowire all AsyncAws clients that you have installed. You can
provide default configuration to all clients and specific configuration of each
service you define.

This example assume you have installed core, ses, sqs and s3:

```yaml
# config/packages/async_aws.yaml

async_aws:
    config: # Will be merged with other configuration
        region: eu-central-1

    clients:
        ses: ~ # This will complain if we don't have that package installed
        sqs:
            config:
                region: us-west-1
        delayed:
            type: sqs
```

The config above will create the following services:

| Service name               | Autowired with                  |
| -------------------------- | ------------------------------- |
| `async_aws.client.ses`     | AsyncAws\Ses\SesClient
| `async_aws.client.sqs`     | AsyncAws\Sqs\SqsClient
| `async_aws.client.delayed` | AsyncAws\Sqs\SqsClient $delayed
| `async_aws.client.s3`      | AsyncAws\S3\S3Client
| `async_aws.client.sts`     | AsyncAws\Core\Sts\StsClient

For a complete reference of the configuration please run:

```shell
php bin/console config:dump-reference async_aws
```

## Using SSM to store secrets

Since version 4.4, Symfony provides [secrets management](https://symfony.com/doc/current/configuration/secrets.html).
AsyncAws leverage this feature and by storing secrets in [AWS SSM Parameter Store](https://docs.aws.amazon.com/systems-manager/latest/userguide/systems-manager-parameter-store.html):

```yaml
# config/packages/async_aws.yaml

async_aws:
    secrets: ~
#        path: /parameters/my-project
#        recursive: true
#        client: app-secret
#    clients:
#        app-secret:
#            type: ssm
```

Parameters stored in SSM will be available as env variable:

```yaml
# config/packages/doctrine.yaml

doctrine:
    dbal:
        password: '%env(DATABASE_PASSWORD)%'
```

When `recursive` option is `true`, AsyncAws Bundle will retrieve all parameters
within a hierarchy, then will trim the `path` prefix option, replace `/` by `_`
and uppercase the parameter name. ie. in the above configuration, a parameter
stored in `/parameters/my-project/database/password` will be referenced by
`DATABASE_PASSWORD`.
