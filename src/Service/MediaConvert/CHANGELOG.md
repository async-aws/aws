# Change Log

## NOT RELEASED

### Added

- AWS api-change: TODO

## 1.13.0

### Added

- AWS api-change: This release adds a follow source mode for audio output channel count, an AES audio frame wrapping option for MXF outputs, and an option to signal DolbyVision compatibility using the SUPPLEMENTAL-CODECS tag in HLS manifests.

### Changed

- Remove redundant ext-json requirement

## 1.12.0

### Added

- Add `UNKNOWN_TO_SDK` value to enums that is used when the API returns an value that is not (or not yet) known by the AsyncAws
- AWS api-change: This release adds the ability to set resolution for the black video generator.
- AWS api-change: Adds SlowPalPitchCorrection to audio pitch correction settings. Enables opacity for VideoOverlays. Adds REMUX_ALL option to enable multi-rendition passthrough to VideoSelector for allow listed accounts.
- AWS api-change: Lowers minimum duration for black video generator. Adds support for embedding and signing C2PA content credentials in DASH and CMAF HLS outputs.
- AWS api-change: Adds support for tile encoding in HEVC and audio for video overlays.

### Dependency bumped

- Drop support for PHP versions lower than 8.2

### Changed

- Apply new CodingStandard from latest php-cs-fixer.
- Change case of of various properties.
- Remove unnecessary `use` statements

## 1.11.0

### Added

- AWS api-change: This release adds support for input rendition selection for HLS input, adds new Share API to enable sharing jobs with AWS Support for support investigations, and adds INCLUDE_AS_TS to iFrameOnlyManifest setting for HLS outputs.
- AWS api-change: Remove incorrect endpoint tests

## 1.10.0

### Added

- AWS api-change: This release includes support for embedding and signing C2PA content credentials in MP4 outputs.
- AWS api-change: This release adds a new SPECIFIED_OPTIMAL option for handling DDS when using DVB-Sub with high resolution video.
- AWS api-change: This release expands the range of supported audio outputs to include xHE, 192khz FLAC and the deprecation of dual mono for AC3.
- AWS api-change: This release adds support for TAMS server integration with MediaConvert inputs.

## 1.9.0

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
