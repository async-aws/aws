# Change Log

## NOT RELEASED

## 1.4.0

### Added

- AWS api-change: Add support for ScaleConfig
- AWS api-change: Added `eu-isoe-west-1` region

### Changed

- Normalize the composer requirements
- Sort exception alphabetically.

## 1.3.0

### Added

- AWS api-change: Added `us-isof-east-1` and `us-isof-south-1` regions

### Changed

- AWS enhancement: Documentation updates.

## 1.2.4

### Changed

- use strict comparison `null !==` instead of `!`
- AWS enhancement: Documentation updates.

## 1.2.3

### Changed

- AWS enhancement: Documentation updates.
- Enable compiler optimization for the `sprintf` function.

## 1.2.2

### Changed

- AWS enhancement: Documentation updates.

## 1.2.1

### Changed

- Allow passing explicit null values for optional fields of input objects
- AWS enhancement: Documentation updates.

## 1.2.0

### Added

- Avoid overriding the exception message with the raw message

### Changed

- Improve parameter type and return type in phpdoc

## 1.1.0

### Added

- AWS api-change: This release allows you to modify the encryption in transit setting, for existing Redis clusters. You can now change the TLS configuration of your Redis clusters without the need to re-build or re-provision the clusters or impact application availability.
- AWS enhancement: Documentation updates.

## 1.0.0

### Added

- AWS enhancement: Documentation updates.
- AWS api-change: Added support for IPv6 and dual stack for Memcached and Redis clusters. Customers can now launch new Redis and Memcached clusters with IPv6 and dual stack networking support.

## 0.1.1

### Added

- AWS api-change: Added `us-iso-west-1` region
- AWS api-change: Added `us-fips` regions
- AWS api-change: Use specific configuration for `us` regions
- AWS enhancement: Documentation updates.
- AWS api-change: AWS ElastiCache for Redis has added a new Engine Log LogType in LogDelivery feature. You can now publish the Engine Log from your Amazon ElastiCache for Redis clusters to Amazon CloudWatch Logs and Amazon Kinesis Data Firehose.

## 0.1.0

First version
