# Maintainer notes

A few notes that are good to know if you contribute or maintain this library.

## Generated code

Most functions in our API clients are generated. So are the result classes. They
are generated from the JSON provided by the official [AWS PHP SDK](https://github.com/aws/aws-sdk-php).
This will assure correctness and it will be easy to keep up to with API changes.

To create a class run the `generate` command.

```cli
./generate

# Or
./generate S3 CreateBucket
```

The `./manifest.json` file contains information where the source is located
and some metadata about the generated files and methods.

You may also regenerate an existing operation and result classes:

```cli
./generate S3 CreateBucket
./generate S3 --all
```

Or regenerate everything:

```cli
./generate --all
```

## Creating a new client operation

If you want to create a new operation to a client, you may just generate it and complete the
test stubs. Below is an example to generate an operation for DynamoDB.

1. Clone the async-aws/aws repository.
1. Run `composer install`
1. Run `./generate DynamoDb` and press "1" for generate a new operation.
1. Select the operation you want to generate. Don't generate operations that you dont need.
1. Use the [AWS Api Reference](https://docs.aws.amazon.com/amazondynamodb/latest/APIReference/API_Operations.html) to fill the expected Input/Result.

If you started working on a new operation, please submit a "Draft PR" to show your
progress. Don't hesitate asking for help.

## Creating a new client

If you want to create a new AWS client, there are a few steps to take. Below
is an example for DynamoDB.

1. Clone the async-aws/aws repository.
1. Run `composer install`
1. Copy `./src/Service/.template` to `./src/Service/DynamoDb`
1. Rename all occurrences of `Foobar` to `DynamoDb` and `foobar` to `dynamo-db`
1. Register the service in the `services` section of the `./manifest.json` file.
   1. Look at the official SDK for resources in `./src/data`. ([DynamoDB example](https://github.com/aws/aws-sdk-php/tree/3.133.23/src/data/dynamodb/2012-08-10))
   1. Add a link to `source`, and, if the files exists, links to `documentation`, `example`, `pagination`, `waiter` etc.
   1. Leave the `methods` key empty. (`"methods": []`)
1. Adds operations you want following the [process previously defined](#creating-a-new-client-operation)

If you started working on a new client, please submit a "Draft PR" to show your
progress. Don't hesitate asking for help.
