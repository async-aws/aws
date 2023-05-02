# Change Log

## NOT RELEASED

### Added

- Added operation `Sign`
- Added `ap-southeast-4-fips` region
- AWS api-change: Added `ap-southeast-4` and `il-central-1-fips` regions.
- AWS api-change: This release makes the NitroEnclave request parameter Recipient and the response field for CiphertextForRecipient available in AWS SDKs. It also adds the regex pattern for CloudHsmClusterId validation.

## 1.0.0

### Added

- Added `me-central-1`, `me-central-1-fips`, `ap-south-2-fips`, `eu-south-2-fips`, `eu-central-2-fips`, `eu-central-2`, `eu-south-2` and `ap-south-2` regions
- AWS api-change: AWS KMS introduces the External Key Store (XKS), a new feature for customers who want to protect their data with encryption keys stored in an external key management system under their control.

## 0.1.1

### Added

- AWS api-change: Adds support for KMS keys and APIs that generate and verify HMAC codes
- AWS enhancement: Documentation updates.
- AWS api-change: Added support for the SM2 KeySpec in China Partition Regions
- AWS enhancement: Add HMAC best practice tip, annual rotation of AWS managed keys.

## 0.1.0

First version
