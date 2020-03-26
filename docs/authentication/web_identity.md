---
category: authentication
---

# Using WebIdentity

When user have been authenticated in a mobile or web application with a web
identity provider (Amazon Cognito, Login with Amazon, Facebook, Google, or any
OpenID Connect-compatible identity provider). AsyncAws is able to fetch
[Credentials from a Role](https://docs.amazonaws.cn/en_us/IAM/latest/UserGuide/id_roles_create_for-idp_oidc.html)
assumed by the WebIdentity.

That authentication mechanism allows use of fined-grained Roles in an EKS
cluster (AWS managed service for K8S). It let you assign a specific role per
POD. Similar to [EC2 Instance Metadata](#using-ec2-instance-metadata) you
have nothing to configure on the application, you only configure the role and
permission assumed by the POD's ServiceAccount. See [AWS documentation](https://aws.amazon.com/blogs/opensource/introducing-fine-grained-iam-roles-service-accounts/)
for more information.

