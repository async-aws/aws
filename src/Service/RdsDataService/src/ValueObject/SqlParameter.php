<?php

namespace AsyncAws\RdsDataService\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\RdsDataService\Enum\TypeHint;

/**
 * A parameter used in a SQL statement.
 */
final class SqlParameter
{
    /**
     * The name of the parameter.
     *
     * @var string|null
     */
    private $name;

    /**
     * The value of the parameter.
     *
     * @var Field|null
     */
    private $value;

    /**
     * A hint that specifies the correct object type for data type mapping. Possible values are as follows:
     *
     * - `DATE` - The corresponding `String` parameter value is sent as an object of `DATE` type to the database. The
     *   accepted format is `YYYY-MM-DD`.
     * - `DECIMAL` - The corresponding `String` parameter value is sent as an object of `DECIMAL` type to the database.
     * - `JSON` - The corresponding `String` parameter value is sent as an object of `JSON` type to the database.
     * - `TIME` - The corresponding `String` parameter value is sent as an object of `TIME` type to the database. The
     *   accepted format is `HH:MM:SS[.FFF]`.
     * - `TIMESTAMP` - The corresponding `String` parameter value is sent as an object of `TIMESTAMP` type to the database.
     *   The accepted format is `YYYY-MM-DD HH:MM:SS[.FFF]`.
     * - `UUID` - The corresponding `String` parameter value is sent as an object of `UUID` type to the database.
     *
     * @var TypeHint::*|null
     */
    private $typeHint;

    /**
     * @param array{
     *   name?: string|null,
     *   value?: Field|array|null,
     *   typeHint?: TypeHint::*|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->name = $input['name'] ?? null;
        $this->value = isset($input['value']) ? Field::create($input['value']) : null;
        $this->typeHint = $input['typeHint'] ?? null;
    }

    /**
     * @param array{
     *   name?: string|null,
     *   value?: Field|array|null,
     *   typeHint?: TypeHint::*|null,
     * }|SqlParameter $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @return TypeHint::*|null
     */
    public function getTypeHint(): ?string
    {
        return $this->typeHint;
    }

    public function getValue(): ?Field
    {
        return $this->value;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->name) {
            $payload['name'] = $v;
        }
        if (null !== $v = $this->value) {
            $payload['value'] = $v->requestBody();
        }
        if (null !== $v = $this->typeHint) {
            if (!TypeHint::exists($v)) {
                /** @psalm-suppress NoValue */
                throw new InvalidArgument(\sprintf('Invalid parameter "typeHint" for "%s". The value "%s" is not a valid "TypeHint".', __CLASS__, $v));
            }
            $payload['typeHint'] = $v;
        }

        return $payload;
    }
}
