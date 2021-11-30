# Change Log

## NOT RELEASED

## 0.1.6

### Added

- AWS api-change: Added `us-fips` regions
- AWS api-change: Added `ca-central-1` region
- AWS api-change: Use specific configuration for `us` regions
- AWS api-change: This release added new KnownGender types for Celebrity Recognition.

## 0.1.5

### Added

- AWS api-change: Add AWS tagging support for Amazon Rekognition collections, stream processors, and Custom Label models
- AWS api-change: Improve documentation
- AWS api-change: Add new attributes to Rekognition RecognizeCelebities and GetCelebrityInfo API operations.
- Added operation `getCelebrityInfo`
- Added operation `recognizeCelebrities`

### Fixed

- Assert the provided Input can be json-encoded.

## 0.1.4

### Added

- Changed case of object's properties to camelCase.
- Added documentation in class headers.
- Added domain exceptions

## 0.1.3

### Fixed

- If provided an unrecognized region, then fallback to default region config

## 0.1.2

### Fixed

- Make sure we throw exception from async-aws/core

## 0.1.1

### Added

- Support for `ca-central-1` and `rekognition-fips.ca-central-1` regions

## 0.1.0

First version
