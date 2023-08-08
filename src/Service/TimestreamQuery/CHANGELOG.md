# Change Log

## NOT RELEASED

### Changed

- Allow passing explicit null values for optional fields of input objects

## 2.0.0

### BC-BREAK

- The return type for the methods `getCumulativeBytesMetered` and `getCumulativeBytesScanned` of `\AsyncAws\TimestreamQuery\ValueObject\QueryStatus` uses `int` instead of `string` to reflect the AWS type.

### Added

- Avoid overriding the exception message with the raw message
- Use int as the PHP representation of long fields in generated code

### Changed

- Improve parameter type and return type in phpdoc

## 1.0.0

### Added

- Nothing changed. Let's release a First stable version

## 0.1.1

### Added

- Added support for endpoint discovery

## 0.1.0

First version
