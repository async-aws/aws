# Change Log

## NOT RELEASED

## 3.4.0

### Added

- AWS api-change: Add support for the managed query result in the workgroup APIs. The managed query result configuration enables users to store query results to Athena owned storage.

## 3.3.0

### Added

- AWS api-change: Added `eu-isoe-west-1` region
- AWS api-change: rewrite declaration of regions

### Changed

- AWS enhancement: Documentation updates.

### Fixed

- Fix the lowest bound for the `symfony/polyfill-uuid` requirement

## 3.2.0

### Added

- AWS api-change: Added `us-isof-east-1` and `us-isof-south-1` regions

## 3.1.0

### Added

- AWS api-change: Add FEDERATED type to CreateDataCatalog. This creates Athena Data Catalog, AWS Lambda connector, and AWS Glue connection. Create/DeleteDataCatalog returns DataCatalog. Add Status, ConnectionType, and Error to DataCatalog and DataCatalogSummary. Add DeleteCatalogOnly to delete Athena Catalog only.
- AWS api-change: Added `ap-southeast-5` region

## 3.0.0

### BC-BREAK

- AWS api-change: Removing FEDERATED from Create/List/Delete/GetDataCatalog API

### Changed

- use strict comparison `null !==` instead of `!`

## 2.3.0

### Added

- AWS api-change: Added `fips-ca-central-1` and `fips-ca-west-1` regions.
- AWS api-change: List/Get/Update/Delete/CreateDataCatalog now integrate with AWS Glue connections. Users can create a Glue connection through Athena or use a Glue connection to define their Athena federated parameters.

### Changed

- Enable compiler optimization for the `sprintf` function.

## 2.2.1

### Changed

- Add `Accept: application/json` header in request to fix incompatibility with 3rd party providers
- AWS enhancement: Documentation updates.

## 2.2.0

### Added

- AWS api-change: Added `ca-west-1` region

## 2.1.1

### Changed

- AWS enhancement: Documentation updates.

## 2.1.0

### Added

- Added `il-central-1` region
- AWS api-change: Adding SerivicePreProcessing time metric
- AWS api-change: Adding IdentityCenter enabled request for interactive query

### Changed

- Allow passing explicit null values for optional fields of input objects

## 2.0.0

### BC-BREAK

- The return type for `\AsyncAws\Athena\Result\GetQueryResultsOutput::getUpdateCount` uses `int` instead of `string` to reflect the AWS type.
- The return type for `\AsyncAws\Athena\ValueObject\CalculationStatistics::getDpuExecutionInMillis` uses `int` instead of `string` to reflect the AWS type.
- The return type for the methods `getDataScannedInBytes`, `getEngineExecutionTimeInMillis`, `getQueryPlanningTimeInMillis`, `getQueryQueueTimeInMillis`, `getServiceProcessingTimeInMillis` and `getTotalExecutionTimeInMillis` of `\AsyncAws\Athena\ValueObject\QueryExecutionStatistics` uses `int` instead of `string` to reflect the AWS type.
- The return type for `\AsyncAws\Athena\ValueObject\SessionConfiguration::getIdleTimeoutSeconds` uses `int` instead of `string` to reflect the AWS type.
- The return type for `\AsyncAws\Athena\ValueObject\SessionStatistics::getDpuExecutionInMillis` uses `int` instead of `string` to reflect the AWS type.
- The return type for `\AsyncAws\Athena\ValueObject\WorkGroupConfiguration::getBytesScannedCutoffPerQuery` uses `int` instead of `string` to reflect the AWS type.

### Added

- Added `me-central-1` region
- AWS api-change: Add support for the `ap-south-2`, `ap-southeast-4`, `eu-central-2` and `eu-south-2` regions
- Avoid overriding the exception message with the raw message
- Use int as the PHP representation of long fields in generated code

### Changed

- Improve parameter type and return type in phpdoc

## 1.0.0

### Added

- Added `us-iso-east-1` region
- AWS enhancement: Documentation updates.
- AWS api-change: This release includes support for using Apache Spark in Amazon Athena.

## 0.1.0

First version
