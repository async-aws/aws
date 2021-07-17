---
category: contribute
---

# Generating new code

## Creating a new client operation

If you want to create a new operation to a client, you may just generate it and complete the
test stubs. Below is an example to generate an operation for DynamoDB.

1. Clone the async-aws/aws repository.
1. Run `composer install`
1. Run `./generate DynamoDb` and press "1" for generate a new operation.
1. Select the operation you want to generate. Don't generate operations that you don't need.
1. Use the [AWS Api Reference](https://docs.aws.amazon.com/amazondynamodb/latest/APIReference/API_Operations.html) to fill the expected Input/Result.

If you started working on a new operation, please submit a "Draft PR" to show your
progress. Don't hesitate asking for help.

## Creating a new client

If you want to create a new AWS client, there are a few steps to take. Below
is an example for DynamoDB.

1. Clone the async-aws/aws repository.
1. Run `composer install`
1. Copy `./src/Service/.template` to `./src/Service/DynamoDb`
1. Replace all occurrences of `Foobar` to `DynamoDb` and `foobar` to `dynamo-db`
1. Rename `FoobarClient` into `DynamoDbClient`
1. Add `dynamoDb` method to the client factory `src/Core/src/AwsClientFactory.php`
1. Register the service in the `services` section of the `./manifest.json` file.
   1. Look at the official SDK for resources in `./src/data`. ([DynamoDB example](https://github.com/aws/aws-sdk-php/tree/3.133.23/src/data/dynamodb/2012-08-10))
   1. Add a link to `source`, and, if the files exists, links to `documentation`, `example`, `pagination`, `waiter` etc.
   1. Leave the `methods` key empty. (`"methods": []`)
   1. Provide the base URL to the AWS API reference (use [AWS](https://docs.aws.amazon.com/) or [Google](https://www.google.com/search?q=dynamodb+api+reference))
1. Configure the autoload section of `./composer.json` to include the new service (`"AsyncAws\\DynamoDb\\": "src/Service/DynamoDb/src"`)
1. Do the same thing with the autoload-dev section of `./composer.json`.
1. Run `composer dump-autoload`
1. Add the operations you want following the [process previously defined](#creating-a-new-client-operation)
1. Update the packages overview section of the root `README.md` file
1. Create a `./docs/clients/dynamodb.md` file documenting the client
1. Update the `./docs/clients/index.md` and `./couscous.yml` files to point to the new client documentation

If you started working on a new client, please submit a "Draft PR" to show your
progress. Don't hesitate asking for help.
