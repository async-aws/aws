# Change Log

## NOT RELEASED

### Added

- AWS api-change: This release introduces the bandwidth reduction filter for the HEVC encoder, increases the limits of outputs per job, and updates support for the Nagra SDK to version 1.14.7.
- AWS api-change: This release introduces a new MXF Profile for XDCAM which is strictly compliant with the SMPTE RDD 9 standard and improved handling of output name modifiers.
- AWS api-change: This release introduces a noise reduction pre-filter, linear interpolation deinterlace mode, video pass-through, updated default job settings, and expanded LC-AAC Stereo audio bitrate ranges.
- AWS api-change: AWS Elemental MediaConvert SDK now supports conversion of 608 paint-on captions to pop-on captions for SCC sources.
- AWS api-change: AWS Elemental MediaConvert SDK now supports passthrough of ID3v2 tags for audio inputs to audio-only HLS outputs.
- AWS api-change: The AWS Elemental MediaConvert SDK has improved handling for different input and output color space combinations.
- AWS api-change: The AWS Elemental MediaConvert SDK has added support for HDR10 to SDR tone mapping, and animated GIF video input sources.
- AWS api-change: The AWS Elemental MediaConvert SDK has added improved scene change detection capabilities and a bandwidth reduction filter, along with video quality enhancements, to the AVC encoder.
- AWS api-change: Enabled FIPS endpoints for GovCloud (US) regions in SDK.
- AWS api-change: The AWS Elemental MediaConvert SDK has added support for compact DASH manifest generation, audio normalization using TruePeak measurements, and the ability to clip the sample range in the color corrector.
- AWS api-change: The AWS Elemental MediaConvert SDK has added support for configurable ID3 eMSG box attributes and the ability to signal them with InbandEventStream tags in DASH and CMAF outputs.
- AWS api-change: The AWS Elemental MediaConvert SDK has added support for setting the SDR reference white point for HDR conversions and conversion of HDR10 to DolbyVision without mastering metadata.
- AWS api-change: MediaConvert now supports specifying the minimum percentage of the HRD buffer available at the end of each encoded video segment.
- AWS enhancement: AWS Elemental MediaConvert SDK has released support for automatic DolbyVision metadata generation when converting HDR10 to DolbyVision.
- AWS api-change: AWS Elemental MediaConvert SDK has added support for rules that constrain Automatic-ABR rendition selection when generating ABR package ladders.
- AWS api-change: AWS Elemental MediaConvert SDK has added support for rules that constrain Automatic-ABR rendition selection when generating ABR package ladders.
- AWS api-change: AWS Elemental MediaConvert SDK nows supports creation of Dolby Vision profile 8.1, the ability to generate black frames of video, and introduces audio-only DASH and CMAF support.
- AWS api-change: AWS Elemental MediaConvert SDK has added support for the pass-through of WebVTT styling to WebVTT outputs, pass-through of KLV metadata to supported formats, and improved filter support for processing 444/RGB content.
- AWS api-change: AWS Elemental MediaConvert SDK has added support for reading timecode from AVCHD sources and now provides the ability to segment WebVTT at the same interval as the video and audio in HLS packages.
- AWS api-change: AWS Elemental MediaConvert SDK has added support for 4K AV1 output resolutions & 10-bit AV1 color, the ability to ingest sidecar Dolby Vision XML metadata files, and the ability to flag WebVTT and IMSC tracks for accessibility in HLS.
- AWS api-change: AWS Elemental MediaConvert SDK has added strength levels to the Sharpness Filter and now permits OGG files to be specified as sidecar audio inputs.

## 0.1.0

First version
