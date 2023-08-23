<?php

namespace AsyncAws\Lambda\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Lambda\Enum\SnapStartApplyOn;

/**
 * The function's Lambda SnapStart [^1] setting. Set `ApplyOn` to `PublishedVersions` to create a snapshot of the
 * initialized execution environment when you publish a function version.
 *
 * [^1]: https://docs.aws.amazon.com/lambda/latest/dg/snapstart.html
 */
final class SnapStart
{
    /**
     * Set to `PublishedVersions` to create a snapshot of the initialized execution environment when you publish a function
     * version.
     *
     * @var SnapStartApplyOn::*|null
     */
    private $applyOn;

    /**
     * @param array{
     *   ApplyOn?: null|SnapStartApplyOn::*,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->applyOn = $input['ApplyOn'] ?? null;
    }

    /**
     * @param array{
     *   ApplyOn?: null|SnapStartApplyOn::*,
     * }|SnapStart $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return SnapStartApplyOn::*|null
     */
    public function getApplyOn(): ?string
    {
        return $this->applyOn;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->applyOn) {
            if (!SnapStartApplyOn::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "ApplyOn" for "%s". The value "%s" is not a valid "SnapStartApplyOn".', __CLASS__, $v));
            }
            $payload['ApplyOn'] = $v;
        }

        return $payload;
    }
}
