# Change Log

## NOT RELEASED

### Added

- AWS api-change: This release adds a dryRun option to Image Builder create APIs (except CreateImage), structured failure context on failed images including component and distribution failure details, and step retry attempt tracking.
- AWS api-change: This release adds parentInstance to infrastructure configurations, so builds can use an already-running instance from an Amazon EC2 Auto Scaling group. It also adds terminateInstanceOnCompletion, which keeps build and test instances running after image creation ends.

### Changed

- AWS enhancement: Documentation updates.

## 1.2.0

### Added

- AWS api-change: Adds support for AMI watermarks in Image Builder.

## 1.1.0

### Added

- Added `DeleteImage`, `ListImages` and `ListImageBuildVersions` operations

## 1.0.0

First version
