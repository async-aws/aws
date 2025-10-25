# Change Log

## NOT RELEASED

### Added

- AWS api-change: Added `us-isob-west-1` region

### Changed

- AWS enhancement: Documentation updates.
- Apply new CodingStandard from latest php-cs-fixer.

## 1.10.0

### Added

- AWS api-change: AWS KMS announces the support for on-demand rotation of symmetric-encryption KMS keys with imported key material (EXTERNAL origin).
- AWS api-change: Rework regions configuration
- AWS api-change: AWS KMS announces the support of ML-DSA key pairs that creates post-quantum safe digital signatures.

### Changed

- AWS enhancement: Documentation updates.

## 1.9.0

### Added

- AWS api-change: Added `eu-isoe-west-1` and `eu-isoe-west-1-fips` regions

### Changed

- Sort exception alphabetically.

## 1.8.0

### Added

- AWS api-change: Added `us-isof-east-1` , `us-isof-south-1`, `us-isof-east-1-fips` and `us-isof-south-1-fips` regions

## 1.7.0

### Added

- Added getPublicKey and verify operation
- AWS api-change: Added `ap-southeast-7` and `ap-southeast-7-fips` regions
- AWS api-change: Added `mx-central-1` and `mx-central-1-fips` regions

## 1.6.0

### Added

- AWS api-change: Added `ap-southeast-5` and `ap-southeast-5-fips` regions

### Changed

- Enable compiler optimization for the `sprintf` function.

## 1.5.0

### Added

- AWS api-change: This feature allows customers to use their keys stored in KMS to derive a shared secret which can then be used to establish a secured channel for communication, provide proof of possession, or establish trust with other parties.

### Changed

- Add `Accept: application/json` header in request to fix incompatibility with 3rd party providers

## 1.4.0

### Added

- AWS api-change: change property used for pagination.

### Changed

- AWS enhancement: Documentation updates.

## 1.3.0

### Added

- AWS api-change: Added `ca-west-1` and `ca-west-1-fips` regions

### Changed

- AWS enhancement: Documentation updates.

## 1.2.1

### Changed

- Allow passing explicit null values for optional fields of input objects

## 1.2.0

### Added

- AWS api-change: Added Dry Run Feature to cryptographic and cross-account mutating KMS APIs (14 in all). This feature allows users to test their permissions and parameters before making the actual API call.
- AWS api-change: Add support for the `il-central-1` region
- Avoid overriding the exception message with the raw message

### Changed

- Improve parameter type and return type in phpdoc

## 1.1.0

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
