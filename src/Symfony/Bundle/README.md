# Async AWS Symfony Bundle

![](https://github.com/async-aws/symfony-bundle/workflows/Tests/badge.svg?branch=master)
![](https://github.com/async-aws/symfony-bundle/workflows/BC%20Check/badge.svg?branch=master)

A small SymfonyBundle that helps with configuration and autowiring.

## Install

```cli
composer require async-aws/async-aws-bundle
```

## Configure

```yaml
async_aws:
    config: # Will be merged with other configuration
        region: eu-central-1

    services:
        ses: ~ # This will complain if we dont have that package installed
        sqs:
            config:
                region: us-west-1
        foobar:
            type: sqs
```

The config above will create the following services:

| Service name               | Autowired with                  |
| -------------------------- | ------------------------------- |
| `async_aws.service.ses`    | \AsyncAws\Ses\SesClient
| `async_aws.service.sqs`    | \AsyncAws\Sqs\SqsClient
| `async_aws.service.foobar` | \AsyncAws\Sqs\SqsClient $foobar
| `async_aws.service.s3`     | \AsyncAws\S3\S3Client

For a complete reference of the configuration please run:

```cli
php bin/console config:dump-reference asyncaws
```
