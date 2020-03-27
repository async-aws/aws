---
category: authentication
---

# Authentication

To make requests to AWS, [credentials](https://docs.aws.amazon.com/IAM/latest/UserGuide/id_credentials_access-keys.html)
are required. There are multiple ways to authenticate.

## Precedence of Providers

By default AsyncAWS uses a Provider that chain over all providers and uses the
first provider in the chain that returns credentials without an error.

The providers are currently chained in the following order:

- [Hard-Coded Configuration](./hard_coded.md)
- [Environment Variables](./environment.md)
- [WebIdentity](./web_identity.md)
- [Credential and Configuration Files](./credentials_file.md)
- [EC2 Instance Metadata](./ec2_metadata.md)
