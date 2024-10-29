---
category: authentication
---

# Using EKS Pod Identity

When you run code within an EKS cluster that has the Pod Identity Agent enabled, AsyncAws is able to fetch Credentials from the service account attached to
your pod using the [Pod Identity Agent](https://docs.aws.amazon.com/eks/latest/userguide/pod-identities.html).

When running an application on EKS, this is the simplest way to grant permissions to the application. You
have nothing to configure on the application, you only grant permissions on the Role attached to the service account.
