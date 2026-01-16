# Change Log

## NOT RELEASED

## 1.5.0

### Added

- Add `UNKNOWN_TO_SDK` value to enums that is used when the API returns an value that is not (or not yet) known by the AsyncAws

### Dependency bumped

- Drop support for PHP versions lower than 8.2

### Changed

- Apply new CodingStandard from latest php-cs-fixer.
- Use a more stable sorting for the list of generated region metadata

## 1.4.0

### Added

- AWS api-change: rework regions definition

### Changed

- Sort exception alphabetically.

## 1.3.0

### Added

- AWS api-change: Added `us-isof-east-1` and `us-isof-south-1` regions
- AWS api-change: Added `fips-ca-central-1` region

## 1.2.0

### Added

- AWS api-change: Added `fips-us-iso-east-1` region

## 1.1.4

### Changed

- Enable compiler optimization for the `sprintf` function.

## 1.1.3

### Changed

- Add `Accept: application/json` header in request to fix incompatibility with 3rd party providers

## 1.1.2

### Changed

- AWS enhancement: Documentation updates.

## 1.1.1

### Changed

- AWS enhancement: Documentation updates.

## 1.1.0

### Added

- Avoid overriding the exception message with the raw message

### Changed

- Improve parameter type and return type in phpdoc

## 1.0.0

### Added

- AWS enhancement: Documentation updates.
- AWS api-change: Add reason and detail in `InvalidRequestException` exception

## 0.1.0

First version
