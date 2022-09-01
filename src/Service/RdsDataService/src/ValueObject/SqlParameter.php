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
     */
    private $name;

    /**
     * The value of the parameter.
     */
    private $value;

    /**
     * A hint that specifies the correct object type for data type mapping. Possible values are as follows:.
     */
    private $typeHint;

    /**
     * @param array{
     *   name?: null|string,
     *   value?: null|Field|array,
     *   typeHint?: null|TypeHint::*,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->name = $input['name'] ?? null;
        $this->value = isset($input['value']) ? Field::create($input['value']) : null;
        $this->typeHint = $input['typeHint'] ?? null;
    }

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
                throw new InvalidArgument(sprintf('Invalid parameter "typeHint" for "%s". The value "%s" is not a valid "TypeHint".', __CLASS__, $v));
            }
            $payload['typeHint'] = $v;
        }

        return $payload;
    }
}
