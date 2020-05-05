---
category: authentication
---

# Using ECS Container Authentication Endpoint

When you run code within an ECS image, AsyncAws is able to fetch Credentials from the
[Role](https://docs.aws.amazon.com/IAM/latest/UserGuide/id_roles.html) attached to the instance using and internal aws endpoint.

When running an application on ECS, this is the simplest way to grant permissions to the application. You
have nothing to configure on the application, you only grant permissions on the Role attached to the instance.

More about this in the AWS official documentation: [IAM Roles for Tasks](https://docs.aws.amazon.com/AmazonECS/latest/developerguide/task-iam-roles.html)
