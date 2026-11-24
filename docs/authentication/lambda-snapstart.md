---
category: authentication
---

# Using Lambda SnapStart

When you run code within a Lambda function, AsyncAws reads the Credentials of the
[execution role](https://docs.aws.amazon.com/lambda/latest/dg/lambda-intro-execution-role.html) from the
[environment variables](./environment.md) injected by Lambda.

When [SnapStart](https://docs.aws.amazon.com/lambda/latest/dg/snapstart.html) is activated, Lambda does not
inject these variables, because the Credentials would expire before the function is restored from its snapshot.
AsyncAws fetches them instead from the endpoint defined in the `AWS_CONTAINER_CREDENTIALS_FULL_URI` environment
variable, and authenticates with the token defined in the `AWS_CONTAINER_AUTHORIZATION_TOKEN` environment variable.

You have nothing to configure on the application, you only grant permissions on the execution role of the function.

More about this in the AWS official documentation: [Using SnapStart with AWS SDKs](https://docs.aws.amazon.com/lambda/latest/dg/snapstart-activate.html#snapstart-credentials)
