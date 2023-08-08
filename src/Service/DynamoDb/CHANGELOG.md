# Change Log

## NOT RELEASED

## 2.0.0

### BC-BREAK

- The return type for the methods `getIndexSizeBytes` and `getItemCount` of `\AsyncAws\DynamoDb\ValueObject\GlobalSecondaryIndexDescription` uses `int` instead of `string` to reflect the AWS type.
- The return type for the methods `getIndexSizeBytes` and `getItemCount` of `\AsyncAws\DynamoDb\ValueObject\LocalSecondaryIndexDescription` uses `int` instead of `string` to reflect the AWS type.
- The return type for the methods `getReadCapacityUnits` and `getWriteCapacityUnits` of `\AsyncAws\DynamoDb\ValueObject\ProvisionedThroughput` uses `int` instead of `string` to reflect the AWS type.
- The return type for the methods `getNumberOfDecreasesToday`, `getReadCapacityUnits` and `getWriteCapacityUnits` of `\AsyncAws\DynamoDb\ValueObject\ProvisionedThroughputDescription` uses `int` instead of `string` to reflect the AWS type.
- The return type for `\AsyncAws\DynamoDb\ValueObject\ProvisionedThroughputOverride::getReadCapacityUnits` uses `int` instead of `string` to reflect the AWS type.
- The return type for the methods `getItemCount` and `getTableSizeBytes` of `\AsyncAws\DynamoDb\ValueObject\TableDescription` uses `int` instead of `string` to reflect the AWS type.

### Added

- AWS enhancement: Documentation updates.
- AWS api-change: This release adds ReturnValuesOnConditionCheckFailure parameter to PutItem, UpdateItem, DeleteItem, ExecuteStatement, BatchExecuteStatement and ExecuteTransaction APIs. When set to ALL_OLD, API returns a copy of the item as it was when a conditional write failed
- Avoid overriding the exception message with the raw message
- Use int as the PHP representation of long fields in generated code

### Changed

- Improve parameter type and return type in phpdoc

## 1.4.0

### Added

- AWS enhancement: Documentation updates.
- AWS api-change: Adds deletion protection support to DynamoDB tables. Tables with deletion protection enabled cannot be deleted. Deletion protection is disabled by default, can be enabled via the CreateTable or UpdateTable APIs, and is visible in TableDescription. This setting is not replicated for Global Tables.

## 1.3.0

### Added

- Added support for endpoint discovery
- AWS enhancement: Documentation updates.
- Added operation `executeStatement`

## 1.2.0

### Added

- AWS api-change: Added `us-iso-west-1` region
- AWS api-change: Added `ca-central-1` region
- AWS api-change: Use specific configuration for `us` regions
- AWS enhancement: Updated Help section for "dynamodb update-contributor-insights" API
- AWS api-change: Add support for Table Classes and introduce the Standard Infrequent Access table class.
- AWS api-change: DynamoDB PartiQL now supports ReturnConsumedCapacity, which returns capacity units consumed by PartiQL APIs if the request specified returnConsumedCapacity parameter. PartiQL APIs include ExecuteStatement, BatchExecuteStatement, and ExecuteTransaction.

## 1.1.1

### Fixed

- Assert the provided Input can be json-encoded.

## 1.1.0

### Added

- Changed case of object's properties to camelCase.
- Added documentation in class headers.
- Added domain exceptions.

## 1.0.1

### Fixed

- Make sure required Map properties are validated before sending the request
- Make sure empty Map properties are converted to `{}` in Json request.
- If provided an unrecognized region, then fallback to default region config

## 1.0.0

### Added

- AWS api-change: This release adds a new ReplicaStatus REGION DISABLED for the Table description. This state indicates that the AWS Region for the replica is inaccessible because the AWS Region is disabled.
- AWS api-change: This release adds a new ReplicaStatus INACCESSIBLE_ENCRYPTION_CREDENTIALS for the Table description, indicating when a key used to encrypt a regional replica table is not accessible.

### Fixed

- Make sure we throw exception from async-aws/core

## 0.3.1

### Added

- Added operation `BatchWriteItem`
- Added operation `BatchGetItem`

### Fixed

- Internally we make a difference between null and an empty array

## 0.3.0

### Added

- Added operation `UpdateTimeToLiveInput`

### Removed

- Removes methods `getServiceCode`, `getSignatureVersion` and `getSignatureScopeName` from Client.

### Fixed

- Add return typehint for `listTables`, `query` and `scan`

## 0.2.0

### Added

- Support for async-aws/core 1.0

### Changed

- DTO classes are final

## 0.1.0

First version
