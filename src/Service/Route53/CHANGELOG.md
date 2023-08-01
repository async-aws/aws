# Change Log

## NOT RELEASED

### BC-BREAK

- The return type for `\AsyncAws\Route53\ValueObject\HostedZone::getResourceRecordSetCount` uses `int` instead of `string` to reflect the AWS type.
- The return type for the methods `getTtl` and `getWeight` of `\AsyncAws\Route53\ValueObject\ResourceRecordSet` uses `int` instead of `string` to reflect the AWS type.

### Added

- AWS enhancement: Documentation updates.
- AWS api-change: Amazon Route 53 now supports the Israel (Tel Aviv) Region (il-central-1) for latency records, geoproximity records, and private DNS for Amazon VPCs in that region.

## 1.1.0

### Added

- AWS api-change: Amazon Route 53 now supports the Asia Pacific (Melbourne) Region (ap-southeast-4) for latency records, geoproximity records, and private DNS for Amazon VPCs in that region.

## 1.0.0

- AWS api-change: Add new APIs to support Route 53 IP Based Routing
- AWS enhancement: SDK doc update for Route 53 to update some parameters with new information.
- AWS enhancement: Documentation updates.
- AWS api-change: Amazon Route 53 now supports the Middle East (UAE) Region (me-central-1) for latency records, geoproximity records, and private DNS for Amazon VPCs in that region.
- AWS api-change: Amazon Route 53 now supports the Europe (Zurich) Region (eu-central-2) for latency records, geoproximity records, and private DNS for Amazon VPCs in that region.
- AWS api-change: Amazon Route 53 now supports the Europe (Spain) Region (eu-south-2) for latency records, geoproximity records, and private DNS for Amazon VPCs in that region.
- AWS api-change: Amazon Route 53 now supports the Asia Pacific (Hyderabad) Region (ap-south-2) for latency records, geoproximity records, and private DNS for Amazon VPCs in that region.

### 0.1.2

### Added

- AWS api-change: Added `us-iso-west-1` region
- AWS api-change: Added `ap-southeast-3` region
- Fixed parameter trim in the called API urls

## 0.1.1

### Added

- AWS enhancement: Documentation updates for route53
- Fixed camelCased of Dom classes

## 0.1.0

First version
