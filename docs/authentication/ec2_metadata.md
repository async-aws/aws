---
category: authentication
---

# Using EC2 Instance Metadata

When you run code within an EC2 instance (or EKS, Lambda), AsyncAws is able to fetch Credentials from the
[Role](https://docs.aws.amazon.com/IAM/latest/UserGuide/id_roles.html) attached to the instance.

When running a single application on the Server, this is the simplest way to grant permissions to the application. You
have nothing to configure on the application, you only grant permissions on the Role attached to the instance.
