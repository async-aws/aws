# Change Log

## NOT RELEASED

## 1.2.0

### Added

- Add `UNKNOWN_TO_SDK` value to enums that is used when the API returns an value that is not (or not yet) known by the AsyncAws

### Dependency bumped

- Drop support for PHP versions lower than 8.2

### Changed

- Apply new CodingStandard from latest php-cs-fixer.
- AWS enhancement: Documentation updates.
- change case of `getFilterBbox` method to `getFilterBBox` and `getRouteBbox` method to `getRouteBBox`
- Remove unnecessary `use` statements

## 1.1.2

### Changed

- AWS enhancement: Documentation updates.

## 1.1.1

### Changed

- Sort exception alphabetically.

## 1.1.0

### Added

- AWS api-change: Added `UNKNOWN_FIELD` constant in `ValidationExceptionReason`

## 1.0.4

### Changed

- Enable compiler optimization for the `sprintf` function.

## 1.0.3

### Changed

- AWS enhancement: Documentation updates.
- Add `Accept: application/json` header in request to fix incompatibility with 3rd party providers

## 1.0.2

### Changed

- AWS enhancement: Documentation updates.

## 1.0.1

### Changed

- AWS enhancement: Documentation updates.

## 1.0.0

- Empty release

## 0.1.2

### Added

- AWS api-change: Added `fips-us-gov-west-1` region
- AWS api-change: This release 1) adds sub-municipality field in Places API for searching and getting places information, and 2) allows optimizing route calculation based on expected arrival time.

### Changed

- Allow passing explicit null values for optional fields of input objects

## 0.1.1

### Added

- Avoid overriding the exception message with the raw message
- AWS api-change: This release adds support for authenticating with Amazon Location Service's Places & Routes APIs with an API Key. Also, with this release developers can publish tracked device position updates to Amazon EventBridge.

### Changed

- Improve parameter type and return type in phpdoc

## 0.1.0

First version
