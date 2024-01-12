# Change Log

## NOT RELEASED

## 1.0.0

- Empty release

## 0.1.3

### Added

- AWS api-change: This release includes video engine updates including HEVC improvements, support for ingesting VP9 encoded video in MP4 containers, and support for user-specified 3D LUTs.

## 0.1.2

### Added

- AWS api-change: This release includes additional audio channel tags in Quicktime outputs, support for film grain synthesis for AV1 outputs, ability to create audio-only FLAC outputs, and ability to specify Amazon S3 destination storage class.
- AWS api-change: This release supports the creation of of audio-only tracks in CMAF output groups.
- AWS api-change: This release adds the ability to replace video frames without modifying the audio essence.
- AWS api-change: This release includes the ability to specify any input source as the primary input for corresponding follow modes, and allows users to specify fit and fill behaviors without resizing content.

### Changed

- Allow passing explicit null values for optional fields of input objects
- Change the way `AudioSelectorGroup` and `AudioSelector` are populated

## 0.1.1

### Added

- Avoid overriding the exception message with the raw message
- AWS api-change: This release introduces the bandwidth reduction filter for the HEVC encoder, increases the limits of outputs per job, and updates support for the Nagra SDK to version 1.14.7.
- AWS enhancement: Documentation updates.

### Changed

- Improve parameter type and return type in phpdoc

## 0.1.0

First version
