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

- [Hard-Coded Configuration](./hard_coded.md)
- [Environment Variables](./environment.md)
- [WebIdentity](./web_identity.md)
- [Credential and Configuration Files](./credentials_file.md)
- [EC2 Instance Metadata](./ec2_metadata.md)

## Testing with authentication providers

The default provider chain could be too slow or too complex for testing. It is recommended
to use the `NullProvider` in tests where you dont provide valid configuration values.
