<?php

namespace AsyncAws\MediaConvert\ValueObject;

/**
 * Settings for integer-second duration normalization. When this preprocessor is present, the output duration will be
 * adjusted to an exact integer-second boundary. If the input is within the trim threshold of an integer second,
 * trailing frames are dropped. If within the compression threshold and less than 500ms over the previous integer
 * second, the output is sped up slightly. Otherwise, black frames are padded to the next integer second.
 */
final class DurationControl
{
    /**
     * Required. Denominator of the maximum allowed compression ratio.
     *
     * @var int|null
     */
    private $integerDurationMaximumCompressionDenominator;

    /**
     * Required. Numerator of the maximum allowed compression ratio, defined as overrun divided by target duration. For
     * example, numerator 5 with denominator 100 means max 5% compression. Set to 0 to disable compression entirely (only
     * trim or pad will be used).
     *
     * @var int|null
     */
    private $integerDurationMaximumCompressionNumerator;

    /**
     * Maximum number of fractional milliseconds past an integer second that qualify for the trim path (frame dropping).
     * Default is 0 (trimming disabled).
     *
     * @var int|null
     */
    private $integerDurationTrimThresholdMilliseconds;

    /**
     * @param array{
     *   IntegerDurationMaximumCompressionDenominator?: int|null,
     *   IntegerDurationMaximumCompressionNumerator?: int|null,
     *   IntegerDurationTrimThresholdMilliseconds?: int|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->integerDurationMaximumCompressionDenominator = $input['IntegerDurationMaximumCompressionDenominator'] ?? null;
        $this->integerDurationMaximumCompressionNumerator = $input['IntegerDurationMaximumCompressionNumerator'] ?? null;
        $this->integerDurationTrimThresholdMilliseconds = $input['IntegerDurationTrimThresholdMilliseconds'] ?? null;
    }

    /**
     * @param array{
     *   IntegerDurationMaximumCompressionDenominator?: int|null,
     *   IntegerDurationMaximumCompressionNumerator?: int|null,
     *   IntegerDurationTrimThresholdMilliseconds?: int|null,
     * }|DurationControl $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getIntegerDurationMaximumCompressionDenominator(): ?int
    {
        return $this->integerDurationMaximumCompressionDenominator;
    }

    public function getIntegerDurationMaximumCompressionNumerator(): ?int
    {
        return $this->integerDurationMaximumCompressionNumerator;
    }

    public function getIntegerDurationTrimThresholdMilliseconds(): ?int
    {
        return $this->integerDurationTrimThresholdMilliseconds;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->integerDurationMaximumCompressionDenominator) {
            $payload['integerDurationMaximumCompressionDenominator'] = $v;
        }
        if (null !== $v = $this->integerDurationMaximumCompressionNumerator) {
            $payload['integerDurationMaximumCompressionNumerator'] = $v;
        }
        if (null !== $v = $this->integerDurationTrimThresholdMilliseconds) {
            $payload['integerDurationTrimThresholdMilliseconds'] = $v;
        }

        return $payload;
    }
}
