<?php

namespace AsyncAws\RdsDataService\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\RdsDataService\Enum\DecimalReturnType;

final class ResultSetOptions
{
    /**
     * A value that indicates how a field of `DECIMAL` type is represented in the response. The value of `STRING`, the
     * default, specifies that it is converted to a String value. The value of `DOUBLE_OR_LONG` specifies that it is
     * converted to a Long value if its scale is 0, or to a Double value otherwise.
     */
    private $decimalReturnType;

    /**
     * @param array{
     *   decimalReturnType?: null|\AsyncAws\RdsDataService\Enum\DecimalReturnType::*,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->decimalReturnType = $input['decimalReturnType'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return DecimalReturnType::*|null
     */
    public function getDecimalReturnType(): ?string
    {
        return $this->decimalReturnType;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->decimalReturnType) {
            if (!DecimalReturnType::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "decimalReturnType" for "%s". The value "%s" is not a valid "DecimalReturnType".', __CLASS__, $v));
            }
            $payload['decimalReturnType'] = $v;
        }

        return $payload;
    }
}
