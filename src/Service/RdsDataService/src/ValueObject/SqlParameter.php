<?php

namespace AsyncAws\RdsDataService\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\RdsDataService\Enum\TypeHint;

final class SqlParameter
{
    /**
     * The name of the parameter.
     */
    private $name;

    /**
     * A hint that specifies the correct object type for data type mapping.
     */
    private $typeHint;

    /**
     * The value of the parameter.
     */
    private $value;

    /**
     * @param array{
     *   name?: null|string,
     *   typeHint?: null|\AsyncAws\RdsDataService\Enum\TypeHint::*,
     *   value?: null|\AsyncAws\RdsDataService\ValueObject\Field|array,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->name = $input['name'] ?? null;
        $this->typeHint = $input['typeHint'] ?? null;
        $this->value = isset($input['value']) ? Field::create($input['value']) : null;
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
        if (null !== $v = $this->typeHint) {
            if (!TypeHint::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "typeHint" for "%s". The value "%s" is not a valid "TypeHint".', __CLASS__, $v));
            }
            $payload['typeHint'] = $v;
        }
        if (null !== $v = $this->value) {
            $payload['value'] = $v->requestBody();
        }

        return $payload;
    }
}
