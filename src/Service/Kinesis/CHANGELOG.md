# Change Log

## NOT RELEASED

### Added

- AWS api-change: Added StreamARN parameter for Kinesis Data Streams APIs. Added a new opaque pagination token for ListStreams. SDKs will auto-generate Account Endpoint when accessing Kinesis Data Streams.
- BC BREAK: `listStreams` now yield over stream names AND descriptions

## 1.1.0

### Added

- AWS api-change: Added `us-iso-west-1` region
- AWS api-change: Use specific configuration for `us` regions
- AWS api-change: Amazon Kinesis Data Streams now supports on demand streams.

## 1.0.0

### Fixed

- Handle not-defined array properties

## 0.1.0

First version
