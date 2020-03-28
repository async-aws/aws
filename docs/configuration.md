
# Configuration

There are some configuration you can pass to an API client. Use an instance of the
`Configuration` class or a plain array.

```php
use AsyncAws\Sqs\SqsClient;
use AsyncAws\Core\Configuration;

$config = Configuration::create([
    'region' => 'eu-central-1',
]);
$sqs = new SqsClient($config);

// Or with an array
$sqs = new SqsClient([
    'region' => 'eu-central-1',
]);
```

## Configuration reference

Below is a list of all supported configuration keys, their default value and what
they are used for.

### region

**Default:** 'us-east-1'

The AWS region the client should be targeting.

### profile

**Default:** 'default'

The name of the AWS Profile configured when using [credential and configuration files](/authentication/credentials-file.md)
for authentication.

### accessKeyId

The AWS access key id used for authentication.

### accessKeySecret

The AWS access key secret used for authentication.

### sessionToken

The AWS session token passed alongside temporary credentials.

See [AWS documentation](https://docs.aws.amazon.com/IAM/latest/UserGuide/id_credentials_temp_use-resources.html)
and [CLI reference](https://docs.aws.amazon.com/cli/latest/reference/sts/get-session-token.html)
for more information.

### sharedCredentialsFile

**Default:** '~/.aws/credentials'

The credentials file to look in when using [credential and configuration files](/authentication/credentials-file.md)
for authentication.

### sharedConfigFile

**Default:** '~/.aws/config'

The config file to look in when using [credential and configuration files](/authentication/credentials-file.md)
for authentication.

### endpoint

**Default:** 'https://%service%.%region%.amazonaws.com'

### roleArn

The Amazon Resource Name (ARN) of the role that the caller is assuming when using
the [WebIdentity Provider](/authentication/web-identity.md)

### webIdentityTokenFile

Path to the file that contains the OAuth 2.0 access token when using the [WebIdentity Provider](/authentication/web-identity.md)

### roleSessionName

**Default:** 'async-aws-' followed by random chars

An identifier for the assumed role session

