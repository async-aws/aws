# Change Log

## NOT RELEASED

### Changed

- Allow passing explicit null values for optional fields of input objects
- AWS enhancement: Documentation updates.

## 2.0.0

### BC-BREAK

- The type for `\AsyncAws\CloudWatchLogs\Input\FilterLogEventsRequest::getEndTime`, `\AsyncAws\CloudWatchLogs\Input\FilterLogEventsRequest::getStartTime`, `\AsyncAws\CloudWatchLogs\Input\FilterLogEventsRequest::setEndTime` and `\AsyncAws\CloudWatchLogs\Input\FilterLogEventsRequest::setStartTime` uses `int` instead of `string` to reflect the AWS type.
- The return type for the methods `getIngestionTime` and `getTimestamp` of `\AsyncAws\CloudWatchLogs\ValueObject\FilteredLogEvent` uses `int` instead of `string` to reflect the AWS type.
- The return type for `\AsyncAws\CloudWatchLogs\ValueObject\InputLogEvent::getTimestamp` uses `int` instead of `string` to reflect the AWS type.
- The return type for the methods `getCreationTime`, `getFirstEventTimestamp`, `getLastEventTimestamp`, `getLastIngestionTime` and `getStoredBytes` of `\AsyncAws\CloudWatchLogs\ValueObject\LogStream` uses `int` instead of `string` to reflect the AWS type.

### Changed

- Improve parameter type and return type in phpdoc

## 1.6.0

### Added

- Added `createLogStream` operation
- AWS enhancement: Documentation updates.
- AWS api-change: Bug fix: logGroupName is now not a required field in GetLogEvents, FilterLogEvents, GetLogGroupFields, and DescribeLogStreams APIs as logGroupIdentifier can be provided instead

## 1.5.0

### Added

- AWS api-change: Added filter on `logGroupIdentifier` in  `createLogGroup`, `DescribeLogStreams` and `FilterLogEvents`

## 1.4.0

### Added

- AWS api-change: Added `fips-us-gov-east-1` and `fips-us-gov-west-1` regions
- AWS enhancement: Documentation updates.

## 1.3.0

### Added

- AWS api-change: Added `us-iso-west-1` region
- AWS api-change: Use specific configuration for `us` regions
- Added operation `filterLogEvents`

## 1.2.0

### Added

- AWS api-change: Update documentation
- Added operation `createLogGroup`

### Fixed

- Assert the provided Input can be json-encoded.

## 1.1.0

### Added

- Changed case of object's properties to camelCase.
- Added documentation in class headers.
- Added domain exceptions.

## 1.0.2

### Fixed

- If provided an unrecognized region, then fallback to default region config

## 1.0.1

### Fixed

- Make sure we throw exception from async-aws/core

## 1.0.0

### Added

- Support for PHP 8

## 0.2.0

### Removed

- Removes methods `getServiceCode`, `getSignatureVersion` and `getSignatureScopeName` from Client.

### Fixed

- Add return typehint for `describeLogStreams`

## 0.1.0

First version
