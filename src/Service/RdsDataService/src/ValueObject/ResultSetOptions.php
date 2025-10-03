<?php

namespace AsyncAws\RdsDataService\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\RdsDataService\Enum\DecimalReturnType;
use AsyncAws\RdsDataService\Enum\LongReturnType;

/**
 * Options that control how the result set is returned.
 */
final class ResultSetOptions
{
    /**
     * A value that indicates how a field of `DECIMAL` type is represented in the response. The value of `STRING`, the
     * default, specifies that it is converted to a String value. The value of `DOUBLE_OR_LONG` specifies that it is
     * converted to a Long value if its scale is 0, or to a Double value otherwise.
     *
     * > Conversion to Double or Long can result in roundoff errors due to precision loss. We recommend converting to
     * > String, especially when working with currency values.
     *
     * @var DecimalReturnType::*|null
     */
    private $decimalReturnType;

    /**
     * A value that indicates how a field of `LONG` type is represented. Allowed values are `LONG` and `STRING`. The default
     * is `LONG`. Specify `STRING` if the length or precision of numeric values might cause truncation or rounding errors.
     *
     * @var LongReturnType::*|null
     */
    private $longReturnType;

    /**
     * @param array{
     *   decimalReturnType?: DecimalReturnType::*|null,
     *   longReturnType?: LongReturnType::*|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->decimalReturnType = $input['decimalReturnType'] ?? null;
        $this->longReturnType = $input['longReturnType'] ?? null;
    }

    /**
     * @param array{
     *   decimalReturnType?: DecimalReturnType::*|null,
     *   longReturnType?: LongReturnType::*|null,
     * }|ResultSetOptions $input
     */
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
     * @return LongReturnType::*|null
     */
    public function getLongReturnType(): ?string
    {
        return $this->longReturnType;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->decimalReturnType) {
            if (!DecimalReturnType::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "decimalReturnType" for "%s". The value "%s" is not a valid "DecimalReturnType".', __CLASS__, $v));
            }
            $payload['decimalReturnType'] = $v;
        }
        if (null !== $v = $this->longReturnType) {
            if (!LongReturnType::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "longReturnType" for "%s". The value "%s" is not a valid "LongReturnType".', __CLASS__, $v));
            }
            $payload['longReturnType'] = $v;
        }

        return $payload;
    }
}
