# AsyncAws client

If you are one of those people that like the Amazon PHP SDK but hate the fact that you need to download Guzzle, PSR-7 and every AWS API client to use it?

This is the library for you!

See full documentation on [https://async-aws.com](https://async-aws.com).

## Packages overview

| Package name                                                                                  | Badges                                                                                                                                                                                                                                                                                                                             | BC check                  |
| --------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ------------------------- |
| [async-aws/core](https://github.com/async-aws/core)                                           | [![Latest Stable Version](https://poser.pugx.org/async-aws/core/v/stable)](https://packagist.org/packages/async-aws/core)                                           [![Total Downloads](https://poser.pugx.org/async-aws/core/downloads)](https://packagist.org/packages/async-aws/core)                                           | [![](https://github.com/async-aws/core/workflows/BC%20Check/badge.svg?branch=master)](https://github.com/async-aws/core/actions)
| [async-aws/cloud-formation](https://github.com/async-aws/cloud-formation)                     | [![Latest Stable Version](https://poser.pugx.org/async-aws/cloud-formation/v/stable)](https://packagist.org/packages/async-aws/cloud-formation)                     [![Total Downloads](https://poser.pugx.org/async-aws/cloud-formation/downloads)](https://packagist.org/packages/async-aws/cloud-formation)                     | [![](https://github.com/async-aws/cloud-formation/workflows/BC%20Check/badge.svg?branch=master)](https://github.com/async-aws/cloud-formation/actions)
| [async-aws/cloud-watch-logs](https://github.com/async-aws/cloud-watch-logs)                   | [![Latest Stable Version](https://poser.pugx.org/async-aws/cloud-watch-logs/v/stable)](https://packagist.org/packages/async-aws/cloud-watch-logs)                   [![Total Downloads](https://poser.pugx.org/async-aws/cloud-watch-logs/downloads)](https://packagist.org/packages/async-aws/cloud-watch-logs)                   | [![](https://github.com/async-aws/cloud-watch-logs/workflows/BC%20Check/badge.svg?branch=master)](https://github.com/async-aws/cloud-watch-logs/actions)
| [async-aws/code-deploy](https://github.com/async-aws/code-deploy)                             | [![Latest Stable Version](https://poser.pugx.org/async-aws/code-deploy/v/stable)](https://packagist.org/packages/async-aws/code-deploy)                             [![Total Downloads](https://poser.pugx.org/async-aws/code-deploy/downloads)](https://packagist.org/packages/async-aws/code-deploy)                             | [![](https://github.com/async-aws/code-deploy/workflows/BC%20Check/badge.svg?branch=master)](https://github.com/async-aws/code-deploy/actions)
| [async-aws/cognito-identity-provider](https://github.com/async-aws/cognito-identity-provider) | [![Latest Stable Version](https://poser.pugx.org/async-aws/cognito-identity-provider/v/stable)](https://packagist.org/packages/async-aws/cognito-identity-provider) [![Total Downloads](https://poser.pugx.org/async-aws/cognito-identity-provider/downloads)](https://packagist.org/packages/async-aws/cognito-identity-provider) | [![](https://github.com/async-aws/cognito-identity-provider/workflows/BC%20Check/badge.svg?branch=master)](https://github.com/async-aws/cognito-identity-provider/actions)
| [async-aws/dynamo-db](https://github.com/async-aws/dynamo-db)                                 | [![Latest Stable Version](https://poser.pugx.org/async-aws/dynamo-db/v/stable)](https://packagist.org/packages/async-aws/dynamo-db)                                 [![Total Downloads](https://poser.pugx.org/async-aws/dynamo-db/downloads)](https://packagist.org/packages/async-aws/dynamo-db)                                 | [![](https://github.com/async-aws/dynamo-db/workflows/BC%20Check/badge.svg?branch=master)](https://github.com/async-aws/dynamo-db/actions)
| [async-aws/lambda](https://github.com/async-aws/lambda)                                       | [![Latest Stable Version](https://poser.pugx.org/async-aws/lambda/v/stable)](https://packagist.org/packages/async-aws/lambda)                                       [![Total Downloads](https://poser.pugx.org/async-aws/lambda/downloads)](https://packagist.org/packages/async-aws/lambda)                                       | [![](https://github.com/async-aws/lambda/workflows/BC%20Check/badge.svg?branch=master)](https://github.com/async-aws/lambda/actions)
| [async-aws/s3](https://github.com/async-aws/s3)                                               | [![Latest Stable Version](https://poser.pugx.org/async-aws/s3/v/stable)](https://packagist.org/packages/async-aws/s3)                                               [![Total Downloads](https://poser.pugx.org/async-aws/s3/downloads)](https://packagist.org/packages/async-aws/s3)                                               | [![](https://github.com/async-aws/s3/workflows/BC%20Check/badge.svg?branch=master)](https://github.com/async-aws/s3/actions)
| [async-aws/ses](https://github.com/async-aws/ses)                                             | [![Latest Stable Version](https://poser.pugx.org/async-aws/ses/v/stable)](https://packagist.org/packages/async-aws/ses)                                             [![Total Downloads](https://poser.pugx.org/async-aws/ses/downloads)](https://packagist.org/packages/async-aws/ses)                                             | [![](https://github.com/async-aws/ses/workflows/BC%20Check/badge.svg?branch=master)](https://github.com/async-aws/ses/actions)
| [async-aws/sns](https://github.com/async-aws/sns)                                             | [![Latest Stable Version](https://poser.pugx.org/async-aws/sns/v/stable)](https://packagist.org/packages/async-aws/sns)                                             [![Total Downloads](https://poser.pugx.org/async-aws/sns/downloads)](https://packagist.org/packages/async-aws/sns)                                             | [![](https://github.com/async-aws/sns/workflows/BC%20Check/badge.svg?branch=master)](https://github.com/async-aws/sns/actions)
| [async-aws/sqs](https://github.com/async-aws/sqs)                                             | [![Latest Stable Version](https://poser.pugx.org/async-aws/sqs/v/stable)](https://packagist.org/packages/async-aws/sqs)                                             [![Total Downloads](https://poser.pugx.org/async-aws/sqs/downloads)](https://packagist.org/packages/async-aws/sqs)                                             | [![](https://github.com/async-aws/sqs/workflows/BC%20Check/badge.svg?branch=master)](https://github.com/async-aws/sqs/actions)
| [async-aws/ssm](https://github.com/async-aws/ssm)                                             | [![Latest Stable Version](https://poser.pugx.org/async-aws/ssm/v/stable)](https://packagist.org/packages/async-aws/ssm)                                             [![Total Downloads](https://poser.pugx.org/async-aws/ssm/downloads)](https://packagist.org/packages/async-aws/ssm)                                             | [![](https://github.com/async-aws/ssm/workflows/BC%20Check/badge.svg?branch=master)](https://github.com/async-aws/ssm/actions)
| [async-aws/flysystem-s3](https://github.com/async-aws/flysystem-s3)                           | [![Latest Stable Version](https://poser.pugx.org/async-aws/flysystem-s3/v/stable)](https://packagist.org/packages/async-aws/flysystem-s3)                           [![Total Downloads](https://poser.pugx.org/async-aws/flysystem-s3/downloads)](https://packagist.org/packages/async-aws/flysystem-s3)                           | [![](https://github.com/async-aws/flysystem-s3/workflows/BC%20Check/badge.svg?branch=master)](https://github.com/async-aws/flysystem-s3/actions)
| [async-aws/async-aws-bundle](https://github.com/async-aws/symfony-bundle)                     | [![Latest Stable Version](https://poser.pugx.org/async-aws/async-aws-bundle/v/stable)](https://packagist.org/packages/async-aws/async-aws-bundle)                   [![Total Downloads](https://poser.pugx.org/async-aws/async-aws-bundle/downloads)](https://packagist.org/packages/async-aws/async-aws-bundle)                   | [![](https://github.com/async-aws/symfony-bundle/workflows/BC%20Check/badge.svg?branch=master)](https://github.com/async-aws/symfony-bundle/actions)
| [async-aws/monolog-cloud-watch](https://github.com/async-aws/monolog-cloud-watch)             | [![Latest Stable Version](https://poser.pugx.org/async-aws/monolog-cloud-watch/v/stable)](https://packagist.org/packages/async-aws/monolog-cloud-watch)             [![Total Downloads](https://poser.pugx.org/async-aws/monolog-cloud-watch/downloads)](https://packagist.org/packages/async-aws/monolog-cloud-watch)             | [![](https://github.com/async-aws/monolog-cloud-watch/workflows/BC%20Check/badge.svg?branch=master)](https://github.com/async-aws/monolog-cloud-watch/actions)
| [async-aws/illuminate-cache](https://github.com/async-aws/illuminate-cache)                   | [![Latest Stable Version](https://poser.pugx.org/async-aws/illuminate-cache/v/stable)](https://packagist.org/packages/async-aws/illuminate-cache)                   [![Total Downloads](https://poser.pugx.org/async-aws/illuminate-cache/downloads)](https://packagist.org/packages/async-aws/illuminate-cache)                   | [![](https://github.com/async-aws/illuminate-cache/workflows/BC%20Check/badge.svg?branch=master)](https://github.com/async-aws/illuminate-cache/actions)
| [async-aws/illuminate-filesystem](https://github.com/async-aws/illuminate-filesystem)         | [![Latest Stable Version](https://poser.pugx.org/async-aws/illuminate-filesystem/v/stable)](https://packagist.org/packages/async-aws/illuminate-filesystem)         [![Total Downloads](https://poser.pugx.org/async-aws/illuminate-filesystem/downloads)](https://packagist.org/packages/async-aws/illuminate-filesystem)         | [![](https://github.com/async-aws/illuminate-filesystem/workflows/BC%20Check/badge.svg?branch=master)](https://github.com/async-aws/illuminate-filesystem/actions)
| [async-aws/illuminate-mail](https://github.com/async-aws/illuminate-mail)                     | [![Latest Stable Version](https://poser.pugx.org/async-aws/illuminate-mail/v/stable)](https://packagist.org/packages/async-aws/illuminate-mail)                     [![Total Downloads](https://poser.pugx.org/async-aws/illuminate-mail/downloads)](https://packagist.org/packages/async-aws/illuminate-mail)                     | [![](https://github.com/async-aws/illuminate-mail/workflows/BC%20Check/badge.svg?branch=master)](https://github.com/async-aws/illuminate-queue/mail)
| [async-aws/illuminate-queue](https://github.com/async-aws/illuminate-queue)                   | [![Latest Stable Version](https://poser.pugx.org/async-aws/illuminate-queue/v/stable)](https://packagist.org/packages/async-aws/illuminate-queue)                   [![Total Downloads](https://poser.pugx.org/async-aws/illuminate-queue/downloads)](https://packagist.org/packages/async-aws/illuminate-queue)                   | [![](https://github.com/async-aws/illuminate-queue/workflows/BC%20Check/badge.svg?branch=master)](https://github.com/async-aws/illuminate-queue/actions)

The main repository is not tagged and cannot be installed as a composer package.
