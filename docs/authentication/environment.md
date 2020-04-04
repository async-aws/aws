---
category: authentication
---

# Using Credentials from Environment Variables

AsyncAWS recognize Env variables that are used by most official AWS tools and SDK.

```shell
# The access key for your AWS account.
export AWS_ACCESS_KEY_ID=AKIAIOSFODNN7EXAMPLE

# The secret access key for your AWS account.
export AWS_SECRET_ACCESS_KEY=wJalrXUtnFEMI/K7MDENG/bPxRfiCYEXAMPLEKEY

# The session key for your AWS account. This is needed only when you are using temporary credentials.
export AWS_SESSION_TOKEN=AQoDYXdzEJr...<remainder of security token>
```

> **Note**: You cannot mix env variables with configuration config with hard-coded configuration.

```php
use AsyncAws\Core\AwsClientFactory;

$client = new AwsClientFactory();
```

