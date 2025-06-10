# Change Log

## NOT RELEASED

### Changed

- AWS enhancement: Documentation updates.

## 1.5.0

### Added

- AWS api-change: rework regions definition

### Changed

- Sort exception alphabetically.

## 1.4.0

### Added

- AWS api-change: Added `us-isof-east-1`  and `us-isof-south-1` regions

## 1.3.1

### Changed

- use strict comparison `null !==` instead of `!`

## 1.3.0

### Added

- AWS api-change: This release adds support for tagging projects and datasets with the CreateProject and CreateDataset APIs.

### Changed

- Enable compiler optimization for the `sprintf` function.

## 1.2.1

### Changed

- Add `Accept: application/json` header in request to fix incompatibility with 3rd party providers

## 1.2.0

### Added

- AWS api-change: This release adds ContentType and TaxonomyLevel attributes to DetectModerationLabels and GetMediaAnalysisJob API responses.

## 1.1.0

### Added

- AWS api-change: Amazon Rekognition introduces support for Custom Moderation. This allows the enhancement of accuracy for detect moderation labels operations by creating custom adapters tuned on customer data.

### Changed

- Allow passing explicit null values for optional fields of input objects
- AWS enhancement: Documentation updates.

## 1.0.1

### Changed

- Improve parameter type and return type in phpdoc

## 1.0.0

### Added

- AWS enhancement: Documentation updates.
- AWS api-change: This release adds a new attribute FaceOccluded. Additionally, you can now select attributes individually (e.g. ["DEFAULT", "FACE_OCCLUDED", "AGE_RANGE"] instead of ["ALL"]), which can reduce response time.
- Added operation `detectModerationLabels`
- AWS api-change: This release adds a new EyeDirection attribute in Amazon Rekognition DetectFaces and IndexFaces APIs which predicts the yaw and pitch angles of a person's eye gaze direction for each face detected in the image.
- AWS api-change: Add new `UserId` property in `Face`

## 0.1.7

### Added

- AWS api-change: This release introduces a new field IndexFacesModelVersion, which is the version of the face detect and storage model that was used when indexing the face vector.

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
