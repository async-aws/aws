# AsyncAws client

If you are one of those people that like the Amazon PHP SDK but hate the fact that you need to download Guzzle, PSR-7 and every AWS API client to use it?

This is the library for you!

See full documentation on [https://async-aws.com](https://async-aws.com).

## Packages overview

| Package name                                                                                  | Badges                                                                                                                                                                                                                                                                                                                             | BC check                                                                                                                                                                       | Commits since last release
| --------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ | -------------------------------|
| [async-aws/core](https://github.com/async-aws/core)                                           | [![Latest Stable Version](https://poser.pugx.org/async-aws/core/v/stable)](https://packagist.org/packages/async-aws/core)                                           [![Total Downloads](https://poser.pugx.org/async-aws/core/downloads)](https://packagist.org/packages/async-aws/core)                                           | [![](https://github.com/async-aws/core/workflows/BC%20Check/badge.svg?branch=master)](https://github.com/async-aws/core/actions)                                               | [![](https://async-aws-pr.github.io/commits-since-release-counter/core.svg)](https://github.com/async-aws/core/actions)
| [async-aws/cloud-formation](https://github.com/async-aws/cloud-formation)                     | [![Latest Stable Version](https://poser.pugx.org/async-aws/cloud-formation/v/stable)](https://packagist.org/packages/async-aws/cloud-formation)                     [![Total Downloads](https://poser.pugx.org/async-aws/cloud-formation/downloads)](https://packagist.org/packages/async-aws/cloud-formation)                     | [![](https://github.com/async-aws/cloud-formation/workflows/BC%20Check/badge.svg?branch=master)](https://github.com/async-aws/cloud-formation/actions)                         | [![](https://async-aws-pr.github.io/commits-since-release-counter/cloud-formation.svg)](https://github.com/async-aws/cloud-formation/actions)
| [async-aws/cloud-front](https://github.com/async-aws/cloud-front)                             | [![Latest Stable Version](https://poser.pugx.org/async-aws/cloud-front/v/stable)](https://packagist.org/packages/async-aws/cloud-front)                             [![Total Downloads](https://poser.pugx.org/async-aws/cloud-front/downloads)](https://packagist.org/packages/async-aws/cloud-front)                             | [![](https://github.com/async-aws/cloud-front/workflows/BC%20Check/badge.svg?branch=master)](https://github.com/async-aws/cloud-front/actions)                                 | [![](https://async-aws-pr.github.io/commits-since-release-counter/cloud-front.svg)](https://github.com/async-aws/cloud-front/actions)
| [async-aws/cloud-watch-logs](https://github.com/async-aws/cloud-watch-logs)                   | [![Latest Stable Version](https://poser.pugx.org/async-aws/cloud-watch-logs/v/stable)](https://packagist.org/packages/async-aws/cloud-watch-logs)                   [![Total Downloads](https://poser.pugx.org/async-aws/cloud-watch-logs/downloads)](https://packagist.org/packages/async-aws/cloud-watch-logs)                   | [![](https://github.com/async-aws/cloud-watch-logs/workflows/BC%20Check/badge.svg?branch=master)](https://github.com/async-aws/cloud-watch-logs/actions)                       | [![](https://async-aws-pr.github.io/commits-since-release-counter/cloud-watch-logs.svg)](https://github.com/async-aws/cloud-watch-logs/actions)
| [async-aws/code-deploy](https://github.com/async-aws/code-deploy)                             | [![Latest Stable Version](https://poser.pugx.org/async-aws/code-deploy/v/stable)](https://packagist.org/packages/async-aws/code-deploy)                             [![Total Downloads](https://poser.pugx.org/async-aws/code-deploy/downloads)](https://packagist.org/packages/async-aws/code-deploy)                             | [![](https://github.com/async-aws/code-deploy/workflows/BC%20Check/badge.svg?branch=master)](https://github.com/async-aws/code-deploy/actions)                                 | [![](https://async-aws-pr.github.io/commits-since-release-counter/code-deploy.svg)](https://github.com/async-aws/code-deploy/actions)
| [async-aws/cognito-identity-provider](https://github.com/async-aws/cognito-identity-provider) | [![Latest Stable Version](https://poser.pugx.org/async-aws/cognito-identity-provider/v/stable)](https://packagist.org/packages/async-aws/cognito-identity-provider) [![Total Downloads](https://poser.pugx.org/async-aws/cognito-identity-provider/downloads)](https://packagist.org/packages/async-aws/cognito-identity-provider) | [![](https://github.com/async-aws/cognito-identity-provider/workflows/BC%20Check/badge.svg?branch=master)](https://github.com/async-aws/cognito-identity-provider/actions)     | [![](https://async-aws-pr.github.io/commits-since-release-counter/cognito-identity-provider.svg)](https://github.com/async-aws/cognito-identity-provider/actions)
| [async-aws/dynamo-db](https://github.com/async-aws/dynamo-db)                                 | [![Latest Stable Version](https://poser.pugx.org/async-aws/dynamo-db/v/stable)](https://packagist.org/packages/async-aws/dynamo-db)                                 [![Total Downloads](https://poser.pugx.org/async-aws/dynamo-db/downloads)](https://packagist.org/packages/async-aws/dynamo-db)                                 | [![](https://github.com/async-aws/dynamo-db/workflows/BC%20Check/badge.svg?branch=master)](https://github.com/async-aws/dynamo-db/actions)                                     | [![](https://async-aws-pr.github.io/commits-since-release-counter/dynamo-db.svg)](https://github.com/async-aws/dynamo-db/actions)
| [async-aws/event-bridge](https://github.com/async-aws/event-bridge)                           | [![Latest Stable Version](https://poser.pugx.org/async-aws/event-bridge/v/stable)](https://packagist.org/packages/async-aws/event-bridge)                           [![Total Downloads](https://poser.pugx.org/async-aws/event-bridge/downloads)](https://packagist.org/packages/async-aws/event-bridge)                           | [![](https://github.com/async-aws/event-bridge/workflows/BC%20Check/badge.svg?branch=master)](https://github.com/async-aws/event-bridge/actions)                               | [![](https://async-aws-pr.github.io/commits-since-release-counter/event-bridge.svg)](https://github.com/async-aws/event-bridge/actions)
| [async-aws/iam](https://github.com/async-aws/iam)                                             | [![Latest Stable Version](https://poser.pugx.org/async-aws/iam/v/stable)](https://packagist.org/packages/async-aws/iam)                                             [![Total Downloads](https://poser.pugx.org/async-aws/iam/downloads)](https://packagist.org/packages/async-aws/iam)                                             | [![](https://github.com/async-aws/iam/workflows/BC%20Check/badge.svg?branch=master)](https://github.com/async-aws/iam/actions)                                                 | [![](https://async-aws-pr.github.io/commits-since-release-counter/iam.svg)](https://github.com/async-aws/iam/actions)
| [async-aws/lambda](https://github.com/async-aws/lambda)                                       | [![Latest Stable Version](https://poser.pugx.org/async-aws/lambda/v/stable)](https://packagist.org/packages/async-aws/lambda)                                       [![Total Downloads](https://poser.pugx.org/async-aws/lambda/downloads)](https://packagist.org/packages/async-aws/lambda)                                       | [![](https://github.com/async-aws/lambda/workflows/BC%20Check/badge.svg?branch=master)](https://github.com/async-aws/lambda/actions)                                           | [![](https://async-aws-pr.github.io/commits-since-release-counter/lambda.svg)](https://github.com/async-aws/lambda/actions)
| [async-aws/rds-data-service](https://github.com/async-aws/rds-data-service)                   | [![Latest Stable Version](https://poser.pugx.org/async-aws/rds-data-service/v/stable)](https://packagist.org/packages/rds-data-service/lambda)                      [![Total Downloads](https://poser.pugx.org/async-aws/rds-data-service/downloads)](https://packagist.org/packages/async-aws/rds-data-service)                   | [![](https://github.com/async-aws/rds-data-service/workflows/BC%20Check/badge.svg?branch=master)](https://github.com/async-aws/rds-data-service/actions)                       | [![](https://async-aws-pr.github.io/commits-since-release-counter/rds-data-service.svg)](https://github.com/async-aws/rds-data-service/actions)
| [async-aws/s3](https://github.com/async-aws/s3)                                               | [![Latest Stable Version](https://poser.pugx.org/async-aws/s3/v/stable)](https://packagist.org/packages/async-aws/s3)                                               [![Total Downloads](https://poser.pugx.org/async-aws/s3/downloads)](https://packagist.org/packages/async-aws/s3)                                               | [![](https://github.com/async-aws/s3/workflows/BC%20Check/badge.svg?branch=master)](https://github.com/async-aws/s3/actions)                                                   | [![](https://async-aws-pr.github.io/commits-since-release-counter/s3.svg)](https://github.com/async-aws/s3/actions)
| [async-aws/ses](https://github.com/async-aws/ses)                                             | [![Latest Stable Version](https://poser.pugx.org/async-aws/ses/v/stable)](https://packagist.org/packages/async-aws/ses)                                             [![Total Downloads](https://poser.pugx.org/async-aws/ses/downloads)](https://packagist.org/packages/async-aws/ses)                                             | [![](https://github.com/async-aws/ses/workflows/BC%20Check/badge.svg?branch=master)](https://github.com/async-aws/ses/actions)                                                 | [![](https://async-aws-pr.github.io/commits-since-release-counter/ses.svg)](https://github.com/async-aws/ses/actions)
| [async-aws/sns](https://github.com/async-aws/sns)                                             | [![Latest Stable Version](https://poser.pugx.org/async-aws/sns/v/stable)](https://packagist.org/packages/async-aws/sns)                                             [![Total Downloads](https://poser.pugx.org/async-aws/sns/downloads)](https://packagist.org/packages/async-aws/sns)                                             | [![](https://github.com/async-aws/sns/workflows/BC%20Check/badge.svg?branch=master)](https://github.com/async-aws/sns/actions)                                                 | [![](https://async-aws-pr.github.io/commits-since-release-counter/sns.svg)](https://github.com/async-aws/sns/actions)
| [async-aws/sqs](https://github.com/async-aws/sqs)                                             | [![Latest Stable Version](https://poser.pugx.org/async-aws/sqs/v/stable)](https://packagist.org/packages/async-aws/sqs)                                             [![Total Downloads](https://poser.pugx.org/async-aws/sqs/downloads)](https://packagist.org/packages/async-aws/sqs)                                             | [![](https://github.com/async-aws/sqs/workflows/BC%20Check/badge.svg?branch=master)](https://github.com/async-aws/sqs/actions)                                                 | [![](https://async-aws-pr.github.io/commits-since-release-counter/sqs.svg)](https://github.com/async-aws/sqs/actions)
| [async-aws/ssm](https://github.com/async-aws/ssm)                                             | [![Latest Stable Version](https://poser.pugx.org/async-aws/ssm/v/stable)](https://packagist.org/packages/async-aws/ssm)                                             [![Total Downloads](https://poser.pugx.org/async-aws/ssm/downloads)](https://packagist.org/packages/async-aws/ssm)                                             | [![](https://github.com/async-aws/ssm/workflows/BC%20Check/badge.svg?branch=master)](https://github.com/async-aws/ssm/actions)                                                 | [![](https://async-aws-pr.github.io/commits-since-release-counter/ssm.svg)](https://github.com/async-aws/ssm/actions)
| **Integrations** | | | |
| [async-aws/async-aws-bundle](https://github.com/async-aws/symfony-bundle)                     | [![Latest Stable Version](https://poser.pugx.org/async-aws/async-aws-bundle/v/stable)](https://packagist.org/packages/async-aws/async-aws-bundle)                   [![Total Downloads](https://poser.pugx.org/async-aws/async-aws-bundle/downloads)](https://packagist.org/packages/async-aws/async-aws-bundle)                   | [![](https://github.com/async-aws/symfony-bundle/workflows/BC%20Check/badge.svg?branch=master)](https://github.com/async-aws/symfony-bundle/actions)                           | [![](https://async-aws-pr.github.io/commits-since-release-counter/symfony-bundle.svg)](https://github.com/async-aws/symfony-bundle/actions)
| [async-aws/dynamo-db-session](https://github.com/async-aws/dynamo-db-session)                 | [![Latest Stable Version](https://poser.pugx.org/async-aws/dynamo-db-session/v/stable)](https://packagist.org/packages/async-aws/dynamo-db-session)                 [![Total Downloads](https://poser.pugx.org/async-aws/dynamo-db-session/downloads)](https://packagist.org/packages/async-aws/dynamo-db-session)                 | [![](https://github.com/async-aws/dynamo-db-session/workflows/BC%20Check/badge.svg?branch=master)](https://github.com/async-aws/dynamo-db-session/actions)                     | [![](https://async-aws-pr.github.io/commits-since-release-counter/dynamo-db-session.svg)](https://github.com/async-aws/dynamo-db-session/actions)
| [async-aws/flysystem-s3](https://github.com/async-aws/flysystem-s3)                           | [![Latest Stable Version](https://poser.pugx.org/async-aws/flysystem-s3/v/stable)](https://packagist.org/packages/async-aws/flysystem-s3)                           [![Total Downloads](https://poser.pugx.org/async-aws/flysystem-s3/downloads)](https://packagist.org/packages/async-aws/flysystem-s3)                           | [![](https://github.com/async-aws/flysystem-s3/workflows/BC%20Check/badge.svg?branch=master)](https://github.com/async-aws/flysystem-s3/actions)                               | [![](https://async-aws-pr.github.io/commits-since-release-counter/flysystem-s3.svg)](https://github.com/async-aws/flysystem-s3/actions)
| [async-aws/illuminate-cache](https://github.com/async-aws/illuminate-cache)                   | [![Latest Stable Version](https://poser.pugx.org/async-aws/illuminate-cache/v/stable)](https://packagist.org/packages/async-aws/illuminate-cache)                   [![Total Downloads](https://poser.pugx.org/async-aws/illuminate-cache/downloads)](https://packagist.org/packages/async-aws/illuminate-cache)                   | [![](https://github.com/async-aws/illuminate-cache/workflows/BC%20Check/badge.svg?branch=master)](https://github.com/async-aws/illuminate-cache/actions)                       | [![](https://async-aws-pr.github.io/commits-since-release-counter/illuminate-cache.svg)](https://github.com/async-aws/illuminate-cache/actions)
| [async-aws/illuminate-filesystem](https://github.com/async-aws/illuminate-filesystem)         | [![Latest Stable Version](https://poser.pugx.org/async-aws/illuminate-filesystem/v/stable)](https://packagist.org/packages/async-aws/illuminate-filesystem)         [![Total Downloads](https://poser.pugx.org/async-aws/illuminate-filesystem/downloads)](https://packagist.org/packages/async-aws/illuminate-filesystem)         | [![](https://github.com/async-aws/illuminate-filesystem/workflows/BC%20Check/badge.svg?branch=master)](https://github.com/async-aws/illuminate-filesystem/actions)             | [![](https://async-aws-pr.github.io/commits-since-release-counter/illuminate-filesystem.svg)](https://github.com/async-aws/illuminate-filesystem/actions)
| [async-aws/illuminate-mail](https://github.com/async-aws/illuminate-mail)                     | [![Latest Stable Version](https://poser.pugx.org/async-aws/illuminate-mail/v/stable)](https://packagist.org/packages/async-aws/illuminate-mail)                     [![Total Downloads](https://poser.pugx.org/async-aws/illuminate-mail/downloads)](https://packagist.org/packages/async-aws/illuminate-mail)                     | [![](https://github.com/async-aws/illuminate-mail/workflows/BC%20Check/badge.svg?branch=master)](https://github.com/async-aws/illuminate-mail/actions)                         | [![](https://async-aws-pr.github.io/commits-since-release-counter/illuminate-mail.svg)](https://github.com/async-aws/illuminate-mail/actions)
| [async-aws/illuminate-queue](https://github.com/async-aws/illuminate-queue)                   | [![Latest Stable Version](https://poser.pugx.org/async-aws/illuminate-queue/v/stable)](https://packagist.org/packages/async-aws/illuminate-queue)                   [![Total Downloads](https://poser.pugx.org/async-aws/illuminate-queue/downloads)](https://packagist.org/packages/async-aws/illuminate-queue)                   | [![](https://github.com/async-aws/illuminate-queue/workflows/BC%20Check/badge.svg?branch=master)](https://github.com/async-aws/illuminate-queue/actions)                       | [![](https://async-aws-pr.github.io/commits-since-release-counter/illuminate-queue.svg)](https://github.com/async-aws/illuminate-queue/actions)
| [async-aws/monolog-cloud-watch](https://github.com/async-aws/monolog-cloud-watch)             | [![Latest Stable Version](https://poser.pugx.org/async-aws/monolog-cloud-watch/v/stable)](https://packagist.org/packages/async-aws/monolog-cloud-watch)             [![Total Downloads](https://poser.pugx.org/async-aws/monolog-cloud-watch/downloads)](https://packagist.org/packages/async-aws/monolog-cloud-watch)             | [![](https://github.com/async-aws/monolog-cloud-watch/workflows/BC%20Check/badge.svg?branch=master)](https://github.com/async-aws/monolog-cloud-watch/actions)                 | [![](https://async-aws-pr.github.io/commits-since-release-counter/monolog-cloud-watch.svg)](https://github.com/async-aws/monolog-cloud-watch/actions)
| [async-aws/simple-s3](https://github.com/async-aws/simple-s3)                                 | [![Latest Stable Version](https://poser.pugx.org/async-aws/simple-s3/v/stable)](https://packagist.org/packages/async-aws/simple-s3)                                 [![Total Downloads](https://poser.pugx.org/async-aws/simple-s3/downloads)](https://packagist.org/packages/async-aws/simple-s3)                                 | [![](https://github.com/async-aws/simple-s3/workflows/BC%20Check/badge.svg?branch=master)](https://github.com/async-aws/simple-s3/actions)                                     | [![](https://async-aws-pr.github.io/commits-since-release-counter/simple-s3.svg)](https://github.com/async-aws/simple-s3/actions)

The main repository is not tagged and cannot be installed as a composer package.
