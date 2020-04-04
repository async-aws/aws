---
category: authentication
---

# Using the AWS Credentials File and Credential Profiles

The AWS CLI stores configuration and credential in [plain text files](https://docs.aws.amazon.com/cli/latest/userguide/cli-configure-files.html).

The format of the AWS credentials file should look something like the following.

```ini
; ~/.aws/credentials
[default]
aws_access_key_id = AKIAIOSFODNN7EXAMPLE
aws_secret_access_key = wJalrXUtnFEMI/K7MDENG/bPxRfiCYEXAMPLEKEY

[project1]
aws_access_key_id = AKIAIOSFODNN7EXAMPLE
aws_secret_access_key = wJalrXUtnFEMI/K7MDENG/bPxRfiCYEXAMPLEKEY
```

Each section represents a credential `Profile` which can be referenced in the Configuration

```php
use AsyncAws\Core\AwsClientFactory;

$client = new AwsClientFactory([
    'profile' => 'project1'
]);
```

> **Note**: When not defined, AsyncAWS will use the profile named `default`.

The profile can refer to a [Role](https://docs.aws.amazon.com/IAM/latest/UserGuide/id_roles.html) and specify the
source of another profile that contains the IAM user credentials with permission to use the role.

```ini
; ~/.aws/config:
[profile user1]
aws_access_key_id = AKIAIOSFODNN7EXAMPLE
aws_secret_access_key = wJalrXUtnFEMI/K7MDENG/bPxRfiCYEXAMPLEKEY

[profile marketing]
role_arn = arn:aws:iam::123456789012:role/marketing
source_profile = user1
```

AWS also store Credentials in the `config` file that should like.

```ini
; ~/.aws/config:
[profile default]
aws_access_key_id = AKIAIOSFODNN7EXAMPLE
aws_secret_access_key = wJalrXUtnFEMI/K7MDENG/bPxRfiCYEXAMPLEKEY
region=eu-west-1

[profile project2]
aws_access_key_id = AKIAIOSFODNN7EXAMPLE
aws_secret_access_key = wJalrXUtnFEMI/K7MDENG/bPxRfiCYEXAMPLEKEY
region=us-west-2
```

The path to the `credentials` and `config` file can be *optionnaly* configured either with Configuration or env variables.

```shell
export AWS_SHARED_CREDENTIALS_FILE=/path/to/shared_credentials_file
export AWS_CONFIG_FILE=/path/to/config_file
```

```php
use AsyncAws\Core\AwsClientFactory;

$client = new AwsClientFactory([
    'sharedCredentialsFile' => '/path/to/shared_credentials_file',
    'sharedConfigFile' => '/path/to/config_file',
]);
```
