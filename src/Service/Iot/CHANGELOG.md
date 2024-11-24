# Change Log

## NOT RELEASED

## 2.1.0

### Added

- AWS api-change: This release allows AWS IoT Core users to enrich MQTT messages with propagating attributes, to associate a thing to a connection, and to enable Online Certificate Status Protocol (OCSP) stapling for TLS X.509 server certificates through private endpoints.

### Changed

- use strict comparison `null !==` instead of `!`

## 2.0.4

### Changed

- Enable compiler optimization for the `sprintf` function.
- AWS enhancement: Documentation updates.

## 2.0.3

### Changed

- Add `Accept: application/json` header in request to fix incompatibility with 3rd party providers

## 2.0.2

### Changed

- AWS enhancement: Documentation updates.

## 2.0.1

### Changed

- Allow passing explicit null values for optional fields of input objects
- AWS enhancement: Documentation updates.

## 2.0.0

### BC-BREAK

- The type for `\AsyncAws\Iot\Input\DeleteThingGroupRequest::getExpectedVersion` and `\AsyncAws\Iot\Input\DeleteThingGroupRequest::setExpectedVersion` uses `int` instead of `string` to reflect the AWS type.
- The type for `\AsyncAws\Iot\Input\DeleteThingRequest::getExpectedVersion` and `\AsyncAws\Iot\Input\DeleteThingGroupRequest::setExpectedVersion` uses `int` instead of `string` to reflect the AWS type.
- The return type for `\AsyncAws\Iot\ValueObject\ThingAttribute::getVersion` uses `int` instead of `string` to reflect the AWS type.

### Added

- Avoid overriding the exception message with the raw message
- Use int as the PHP representation of long fields in generated code

### Changed

- Improve parameter type and return type in phpdoc

## 1.0.0

### Added

- AWS enhancement: Documentation updates.
- AWS api-change: Change endpoint for some regions

## 0.1.0

First version
