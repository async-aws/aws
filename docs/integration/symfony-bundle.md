---
category: integration
---

# AsyncAws Symfony Bundle

A small SymfonyBundle that helps with configuration and autowiring.

## Install

```shell
$ composer require async-aws/async-aws-bundle
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
        ses: ~ # This will complain if we dont have that package installed
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
$ php bin/console config:dump-reference async_aws
```
