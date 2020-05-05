---
category: authentication
---

# Using ECS Container Authentication Endpoint

When you run code within an ECS image, AsyncAws is able to fetch Credentials from the
[Role](https://docs.aws.amazon.com/IAM/latest/UserGuide/id_roles.html) attached to the instance using and internal aws endpoint.

This type of credentials will use the environment value of `AWS_CONTAINER_CREDENTIALS_RELATIVE_URI` to generate an url that returns the temporary credentials.

The current IP Address used to fetch the credentials: `169.254.170.2`

When running an application on ECS, this is the simplest way to grant permissions to the application. You
have nothing to configure on the application, you only grant permissions on the Role attached to the instance.

