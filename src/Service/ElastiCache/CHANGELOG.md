# Change Log

## NOT RELEASED

### Added

- AWS api-change: This release allows you to modify the encryption in transit setting, for existing Redis clusters. You can now change the TLS configuration of your Redis clusters without the need to re-build or re-provision the clusters or impact application availability.
- AWS api-change: for Redis now supports AWS Identity and Access Management authentication access to Redis clusters starting with redis-engine version 7.0
- AWS api-change: Added support for IPv6 and dual stack for Memcached and Redis clusters. Customers can now launch new Redis and Memcached clusters with IPv6 and dual stack networking support.
- AWS api-change: Adding AutoMinorVersionUpgrade in the DescribeReplicationGroups API
- AWS api-change: Added support for encryption in transit for Memcached clusters. Customers can now launch Memcached cluster with encryption in transit enabled when using Memcached version 1.6.12 or later.
- AWS enhancement: Doc only update for ElastiCache
- AWS enhancement: Doc only update for ElastiCache
- AWS enhancement: Doc only update for ElastiCache
- AWS enhancement: Doc only update for ElastiCache
- AWS enhancement: Documentation update for AWS ElastiCache
- AWS api-change: AWS ElastiCache for Redis has added a new Engine Log LogType in LogDelivery feature. You can now publish the Engine Log from your Amazon ElastiCache for Redis clusters to Amazon CloudWatch Logs and Amazon Kinesis Data Firehose.
- AWS enhancement: Doc only update for ElastiCache
- AWS enhancement: Doc only update for ElastiCache
- AWS api-change: Adding support for r6gd instances for Redis with data tiering. In a cluster with data tiering enabled, when available memory capacity is exhausted, the least recently used data is automatically tiered to solid state drives for cost-effective capacity scaling with minimal performance impact.

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
