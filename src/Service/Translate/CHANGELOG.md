# Change Log

## NOT RELEASED

## 1.3.0

### Added

- Add `UNKNOWN_TO_SDK` value to enums that is used when the API returns an value that is not (or not yet) known by the AsyncAws

### Dependency bumped

- Drop support for PHP versions lower than 8.2

### Changed

- Apply new CodingStandard from latest php-cs-fixer.
- Use a more stable sorting for the list of generated region metadata
- Remove unnecessary `use` statements

## 1.2.1

### Changed

- Sort exception alphabetically.

## 1.2.0

### Added

- AWS api-change: Added `us-isof-east-1`  and `us-isof-south-1` regions
- AWS api-change: Added `us-iso-east-1-fips` region
- ```
- AWS api-change: Define list of supported region

## 1.1.0

### Added

- AWS api-change: Added `us-west-1-fips` region

### Changed

- Enable compiler optimization for the `sprintf` function.

## 1.0.2

### Changed

- Add `Accept: application/json` header in request to fix incompatibility with 3rd party providers

## 1.0.1

### Changed

- AWS enhancement: Documentation updates.

## 1.0.0

- Empty release

## 0.1.2

### Added

- AWS api-change: Added support for Brevity translation settings feature.

### Changed

- Allow passing explicit null values for optional fields of input objects

## 0.1.1

### Added

- AWS enhancement: Documentation updates.
- Avoid overriding the exception message with the raw message

### Changed

- Improve parameter type and return type in phpdoc

## 0.1.0

First version
