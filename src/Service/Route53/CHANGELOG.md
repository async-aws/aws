# Change Log

## NOT RELEASED

### Added

- AWS api-change: Amazon Route 53 now supports the ISOB West Region for private DNS for Amazon VPCs and cloudwatch healthchecks.
- AWS api-change: Added `us-isob-west-1` region
- AWS api-change: Adds support for new route53 feature: accelerated recovery.
- AWS api-change: Amazon Route 53 now supports the EU (Germany) Region (eusc-de-east-1) for latency records, geoproximity records, and private DNS for Amazon VPCs in that region

### Dependency bumped

- Drop support for PHP versions lower than 8.2

### Changed

- Apply new CodingStandard from latest php-cs-fixer.
- Use a more stable sorting for the list of generated region metadata
- Remove unnecessary `use` statements

## 2.11.0

### Added

- AWS api-change: Amazon Route 53 now supports the Asia Pacific (New Zealand) Region (ap-southeast-6) for latency records, geoproximity records, and private DNS for Amazon VPCs in that region.

## 2.10.0

### Added

- AWS api-change: Amazon Route 53 now supports the Asia Pacific (Taipei) Region (ap-east-2) for latency records, geoproximity records, and private DNS for Amazon VPCs in that region.
- AWS api-change: Amazon Route 53 now supports the iso-e regions for private DNS Amazon VPCs and cloudwatch healthchecks.

## 2.9.0

### Added

- AWS api-change: Added us-gov-east-1 and us-gov-west-1 as valid Latency Based Routing regions for change-resource-record-sets.
- AWS api-change: Added `eu-isoe-west-1` region

### Changed

- Normalize the composer requirements
- Sort exception alphabetically.

## 2.8.0

### Added

- AWS api-change: Amazon Route 53 now supports the iso-f regions for private DNS Amazon VPCs and cloudwatch healthchecks.

## 2.7.0

### Added

- AWS api-change: Added `us-isof-east-1`  and `us-isof-south-1` regions

### Changed

- trust AWS's API response: And not check if required headers are present

## 2.6.0

### Added

- AWS api-change: Amazon Route 53 now supports the Asia Pacific (Thailand) Region (ap-southeast-7) for latency records, geoproximity records, and private DNS for Amazon VPCs in that region
- AWS api-change: Amazon Route 53 now supports the Mexico (Central) Region (mx-central-1) for latency records, geoproximity records, and private DNS for Amazon VPCs in that region

## 2.5.0

### Added

- AWS api-change: This release adds support for TLSA, SSHFP, SVCB, and HTTPS record types.

### Changed

- use strict comparison `null !==` instead of `!`

## 2.4.0

### Added

- AWS api-change: Amazon Route 53 now supports the Asia Pacific (Malaysia) Region (ap-southeast-5) for latency records, geoproximity records, and private DNS for Amazon VPCs in that region.

### Changed

- Enable compiler optimization for the `sprintf` function.

## 2.3.1

### Changed

- AWS enhancement: Documentation updates.

## 2.3.0

### Added

- AWS api-change: Route53 now supports geoproximity routing in AWS regions

### Changed

- AWS enhancement: Documentation updates.

## 2.2.0

### Added

- AWS api-change: Amazon Route 53 now supports the Canada West (Calgary) Region (ca-west-1) for latency records, geoproximity records, and private DNS for Amazon VPCs in that region.

## 2.1.0

### Added

- AWS api-change: Add hostedzonetype filter to ListHostedZones API.

### Changed

- Allow passing explicit null values for optional fields of input objects

## 2.0.0

### BC-BREAK

- The return type for `\AsyncAws\Route53\ValueObject\HostedZone::getResourceRecordSetCount` uses `int` instead of `string` to reflect the AWS type.
- The return type for the methods `getTtl` and `getWeight` of `\AsyncAws\Route53\ValueObject\ResourceRecordSet` uses `int` instead of `string` to reflect the AWS type.

### Added

- AWS enhancement: Documentation updates.
- AWS api-change: Amazon Route 53 now supports the Israel (Tel Aviv) Region (il-central-1) for latency records, geoproximity records, and private DNS for Amazon VPCs in that region.
- Avoid overriding the exception message with the raw message
- Use int as the PHP representation of long fields in generated code

### Changed

- Improve parameter type and return type in phpdoc

## 1.1.0

### Added

- AWS api-change: Amazon Route 53 now supports the Asia Pacific (Melbourne) Region (ap-southeast-4) for latency records, geoproximity records, and private DNS for Amazon VPCs in that region.

## 1.0.0

### Changed

- AWS api-change: Add new APIs to support Route 53 IP Based Routing
- AWS enhancement: SDK doc update for Route 53 to update some parameters with new information.
- AWS enhancement: Documentation updates.
- AWS api-change: Amazon Route 53 now supports the Middle East (UAE) Region (me-central-1) for latency records, geoproximity records, and private DNS for Amazon VPCs in that region.
- AWS api-change: Amazon Route 53 now supports the Europe (Zurich) Region (eu-central-2) for latency records, geoproximity records, and private DNS for Amazon VPCs in that region.
- AWS api-change: Amazon Route 53 now supports the Europe (Spain) Region (eu-south-2) for latency records, geoproximity records, and private DNS for Amazon VPCs in that region.
- AWS api-change: Amazon Route 53 now supports the Asia Pacific (Hyderabad) Region (ap-south-2) for latency records, geoproximity records, and private DNS for Amazon VPCs in that region.

## 0.1.2

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
