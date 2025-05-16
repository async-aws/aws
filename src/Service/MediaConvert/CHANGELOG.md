# Change Log

## NOT RELEASED

### Added

- AWS api-change: This update enables cropping for video overlays and adds a new STL to Teletext upconversion toggle to preserve styling.

## 1.8.0

### Added

- AWS api-change: This release adds an optional sidecar per-frame video quality metrics report and an ALL_PCM option for audio selectors. It also changes the data type for Probe API response fields related to video and audio bitrate from integer to double.

### Changed

- Sort exception alphabetically.

### Fixed

- Fix the lowest bound for the `symfony/polyfill-uuid` requirement

## 1.7.0

### Added

- AWS api-change: This release adds support for AVC passthrough, the ability to specify PTS offset without padding, and an A/V segment matching feature.
- AWS api-change: This release adds a configurable Quality Level setting for the top rendition of Auto ABR jobs

## 1.6.0

### Added

- AWS api-change: This release adds support for dynamic audio configuration and the ability to disable the deblocking filter for h265 encodes.
- AWS api-change: This release adds support for Animated GIF output, forced chroma sample positioning metadata, and Extensible Wave Container format

## 1.5.0

### Added

- AWS api-change: This release adds support for inserting timecode tracks into MP4 container outputs.
- AWS api-change: use regionalized endpoints
- AWS api-change: This release adds support for the AVC3 codec and fixes an alignment issue with Japanese vertical captions.

## 1.4.1

### Changed

- use strict comparison `null !==` instead of `!`
- AWS enhancement: Documentation updates.

## 1.4.0

### Added

- AWS api-change: This release includes support for dynamic video overlay workflows, including picture-in-picture and squeezeback
- AWS api-change: This release provides support for additional DRM configurations per SPEKE Version 2.0.

### Changed

- Enable compiler optimization for the `sprintf` function.

## 1.3.0

### Added

- AWS api-change: This release includes support for creating I-frame only video segments for DASH trick play.

### Changed

- Add `Accept: application/json` header in request to fix incompatibility with 3rd party providers

## 1.2.0

### Added

- AWS api-change: This release includes support for bringing your own fonts to use for burn-in or DVB-Sub captioning workflows.
- AWS api-change: Change endpoint for `cn-northwest-1` region

## 1.1.0

### Added

- AWS api-change: This release includes support for broadcast-mixed audio description tracks.

### Changed

- Use the regional endpoint instead of the account-specific endpoint

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
