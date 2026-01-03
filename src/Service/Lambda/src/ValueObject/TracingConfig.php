<?php

namespace AsyncAws\Lambda\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Lambda\Enum\TracingMode;

/**
 * The function's X-Ray [^1] tracing configuration. To sample and record incoming requests, set `Mode` to `Active`.
 *
 * [^1]: https://docs.aws.amazon.com/lambda/latest/dg/services-xray.html
 */
final class TracingConfig
{
    /**
     * The tracing mode.
     *
     * @var TracingMode::*|null
     */
    private $mode;

    /**
     * @param array{
     *   Mode?: TracingMode::*|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->mode = $input['Mode'] ?? null;
    }

    /**
     * @param array{
     *   Mode?: TracingMode::*|null,
     * }|TracingConfig $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return TracingMode::*|null
     */
    public function getMode(): ?string
    {
        return $this->mode;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->mode) {
            if (!TracingMode::exists($v)) {
                /** @psalm-suppress NoValue */
                throw new InvalidArgument(\sprintf('Invalid parameter "Mode" for "%s". The value "%s" is not a valid "TracingMode".', __CLASS__, $v));
            }
            $payload['Mode'] = $v;
        }

        return $payload;
    }
}
