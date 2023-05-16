# Change Log

## NOT RELEASED

### Added

- AWS enhancement: Documentation updates.
- AWS api-change: This release adds a new attribute FaceOccluded. Additionally, you can now select attributes individually (e.g. ["DEFAULT", "FACE_OCCLUDED", "AGE_RANGE"] instead of ["ALL"]), which can reduce response time.
- Added operation `detectModerationLabels`
- AWS api-change: This release adds a new EyeDirection attribute in Amazon Rekognition DetectFaces and IndexFaces APIs which predicts the yaw and pitch angles of a person's eye gaze direction for each face detected in the image.
- AWS api-change: Added support for aggregating moderation labels by video segment timestamps for Stored Video Content Moderation APIs and added additional information about the job to all Stored Video Get API responses.
- AWS api-change: Added new status result to Liveness session status.
- AWS api-change: This release adds support for Face Liveness APIs in Amazon Rekognition. Updates UpdateStreamProcessor to return ResourceInUseException Exception. Minor updates to API documentation.
- AWS api-change: Adds support for "aliases" and "categories", inclusion and exclusion filters for labels and label categories, and aggregating labels by video segment timestamps for Stored Video Label Detection APIs.
- AWS api-change: Adding support for ImageProperties feature to detect dominant colors and image brightness, sharpness, and contrast, inclusion and exclusion filters for labels and label categories, new fields to the API response, "aliases" and "categories"
- AWS api-change: This release adds APIs which support copying an Amazon Rekognition Custom Labels model and managing project policies across AWS account.
- AWS api-change: This release introduces support for the automatic scaling of inference units used by Amazon Rekognition Custom Labels models.
- AWS enhancement: Documentation updates for Amazon Rekognition.
- AWS api-change: This release adds support to configure stream-processor resources for label detections on streaming-videos. UpateStreamProcessor API is also launched with this release, which could be used to update an existing stream-processor.
- AWS api-change: This release introduces a new field IndexFacesModelVersion, which is the version of the face detect and storage model that was used when indexing the face vector.
- AWS api-change: This release added new KnownGender types for Celebrity Recognition.

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
