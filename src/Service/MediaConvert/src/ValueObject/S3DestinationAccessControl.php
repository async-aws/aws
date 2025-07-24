<?php

namespace AsyncAws\MediaConvert\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\MediaConvert\Enum\S3ObjectCannedAcl;

/**
 * Optional. Have MediaConvert automatically apply Amazon S3 access control for the outputs in this output group. When
 * you don't use this setting, S3 automatically applies the default access control list PRIVATE.
 */
final class S3DestinationAccessControl
{
    /**
     * Choose an Amazon S3 canned ACL for MediaConvert to apply to this output.
     *
     * @var S3ObjectCannedAcl::*|string|null
     */
    private $cannedAcl;

    /**
     * @param array{
     *   CannedAcl?: null|S3ObjectCannedAcl::*|string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->cannedAcl = $input['CannedAcl'] ?? null;
    }

    /**
     * @param array{
     *   CannedAcl?: null|S3ObjectCannedAcl::*|string,
     * }|S3DestinationAccessControl $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return S3ObjectCannedAcl::*|string|null
     */
    public function getCannedAcl(): ?string
    {
        return $this->cannedAcl;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->cannedAcl) {
            if (!S3ObjectCannedAcl::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "cannedAcl" for "%s". The value "%s" is not a valid "S3ObjectCannedAcl".', __CLASS__, $v));
            }
            $payload['cannedAcl'] = $v;
        }

        return $payload;
    }
}
