# Change Log

## NOT RELEASED

### Changed

- Add `Accept: application/json` header in request to fix incompatibility with 3rd party providers

## 2.1.1

### Changed

- AWS enhancement: Documentation updates.

## 2.1.0

### Added

- AWS api-change: Added new kind of Exceptions in transactions and execute statement operations

## 2.0.1

### Changed

- Allow passing explicit null values for optional fields of input objects

## 2.0.0

### BC-BREAK

- The return type for `\AsyncAws\RdsDataService\Exception\StatementTimeoutException::getDbConnectionId` uses `int` instead of `string` to reflect the AWS type.
- The return type for `\AsyncAws\RdsDataService\Result\ExecuteStatementResponse::getNumberOfRecordsUpdated` uses `int` instead of `string` to reflect the AWS type.
- The return type for `\AsyncAws\RdsDataService\ValueObject\ArrayValue::getLongValues` uses `int` instead of `string` to reflect the AWS type.
- The return type for `\AsyncAws\RdsDataService\ValueObject\Field::getLongValue` uses `int` instead of `string` to reflect the AWS type.

### Added

- Avoid overriding the exception message with the raw message
- Use int as the PHP representation of long fields in generated code

### Changed

- Improve parameter type and return type in phpdoc

## 1.0.0

### Added

- AWS api-change: Support to receive SQL query results in the form of a simplified JSON string. This enables developers using the new JSON string format to more easily convert it to an object using popular JSON string parsing libraries.
- AWS enhancement: Documentation updates.
- Added `fips` regions
- AWS enhancement: Documentation updates for RDS Data API

## 0.1.4

### Added

- AWS api-change: Added `us-iso-west-1` region

## 0.1.3

### Added

- AWS api-change: With the Data API, you can now use UUID and JSON data types as input to your database.

### Fixed

- Assert the provided Input can be json-encoded.

## 0.1.2

### Added

- Added documentation in class headers.
- Added domain exceptions.

## 0.1.1

### Fixed

- Fallback to default region config if provided region is not defined

## 0.1.0

First version
