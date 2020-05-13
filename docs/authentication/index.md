---
category: authentication
---

# Authentication

To make requests to AWS, [credentials](https://docs.aws.amazon.com/IAM/latest/UserGuide/id_credentials_access-keys.html)
are required. There are multiple ways to authenticate and the method you prefer probably
depends on where and how you run the code.

## How authentication works

Each API client needs an Authentication provider. The provider will use some logic
to return a `Credentials` object. It is a value object to store username and
password (in simple terms).

By default AsyncAWS uses a ChainProvider that iterates over all providers and uses
the first provider in the chain that returns credentials without an error.

The providers are currently chained in the following order:

1. [Hard-Coded Configuration](./hard-coded.md)
1. [Environment Variables](./environment.md)
1. [WebIdentity](./web-identity.md)
1. [Credential and Configuration Files](./credentials-file.md)
1. [ECS Container Credentials](./ecs-container.md)
1. [EC2 Instance Metadata](./ec2-metadata.md)

## Testing with authentication providers

The default provider chain could be too slow or too complex for testing. It is recommended
to use the `NullProvider` in tests where you don't provide valid configuration values.

## Caching credential

Fetching credential from AWS metadata endpoint (EC2 Instance, ECS Container,
WebIdentity...) for each request can introduce latency. AsyncAws provides a
`PsrCacheProvider` and a `SymfonyCacheProvider` decorator to persist and reuse
credentials between each request.

```php
use AsyncAws\Core\Credentials\CacheProvider;
use AsyncAws\Core\Credentials\ChainProvider;
use AsyncAws\Core\Credentials\SymfonyCacheProvider;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

$provider = ChainProvider::createDefaultChain();
$provider = new SymfonyCacheProvider($provider, new FilesystemAdapter('test'));

// optional, decorate the provider with the in-memory Cache provider
$provider = new CacheProvider($provider);

$s3 = new S3Client([], $provider);
```
