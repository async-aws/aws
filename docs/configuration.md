
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

The name of the AWS Profile configured when using [credential and configuration files](/authentication/credentials_file.md)
for authentication.

### accessKeyId

The AWS access key id used for authentication.

### accessKeySecret

The AWS access key secret used for authentication.

### sessionToken

A token passed to AWS API to use temporary credentials.

### sharedCredentialsFile

**Default:** '~/.aws/credentials'

The credentials file to look in when using [credential and configuration files](/authentication/credentials_file.md)
for authentication.

### sharedConfigFile

**Default:** '~/.aws/config'

The config file to look in when using [credential and configuration files](/authentication/credentials_file.md)
for authentication.

### endpoint

**Default:** 'https://%service%.%region%.amazonaws.com'

### roleArn

The AWS Arn to the role used with the [WebIdentity Provider](/authentication/web_identity.md)

### webIdentityTokenFile

The file containing the WebIdentityToken used with the [WebIdentity Provider](/authentication/web_identity.md)

### roleSessionName

The session name used with the [WebIdentity Provider](/authentication/web_identity.md)
