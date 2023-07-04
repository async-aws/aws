# Change Log

## NOT RELEASED

### BC-BREAK

- The return type for `\AsyncAws\Athena\Result\GetQueryResultsOutput::getUpdateCount` uses `int` instead of `string` to reflect the AWS type.
- The return type for `\AsyncAws\Athena\ValueObject\CalculationStatistics::getDpuExecutionInMillis` uses `int` instead of `string` to reflect the AWS type.
- The return type for the methods `getDataScannedInBytes`, `getEngineExecutionTimeInMillis`, `getQueryPlanningTimeInMillis`, `getQueryQueueTimeInMillis`, `getServiceProcessingTimeInMillis` and `getTotalExecutionTimeInMillis` of `\AsyncAws\Athena\ValueObject\QueryExecutionStatistics` uses `int` instead of `string` to reflect the AWS type.
- The return type for `\AsyncAws\Athena\ValueObject\SessionConfiguration::getIdleTimeoutSeconds` uses `int` instead of `string` to reflect the AWS type.
- The return type for `\AsyncAws\Athena\ValueObject\SessionStatistics::getDpuExecutionInMillis` uses `int` instead of `string` to reflect the AWS type.
- The return type for `\AsyncAws\Athena\ValueObject\WorkGroupConfiguration::getBytesScannedCutoffPerQuery` uses `int` instead of `string` to reflect the AWS type.


### Added

- AWS api-change: TODO

## 1.0.0

### Added

- Added `us-iso-east-1` region
- AWS enhancement: Documentation updates.
- AWS api-change: This release includes support for using Apache Spark in Amazon Athena.

## 0.1.0

First version
