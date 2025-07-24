<?php

namespace AsyncAws\MediaConvert\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\MediaConvert\Enum\Xavc4kIntraCbgProfileClass;

/**
 * Required when you set Profile to the value XAVC_4K_INTRA_CBG.
 */
final class Xavc4kIntraCbgProfileSettings
{
    /**
     * Specify the XAVC Intra 4k (CBG) Class to set the bitrate of your output. Outputs of the same class have similar image
     * quality over the operating points that are valid for that class.
     *
     * @var Xavc4kIntraCbgProfileClass::*|string|null
     */
    private $xavcClass;

    /**
     * @param array{
     *   XavcClass?: null|Xavc4kIntraCbgProfileClass::*|string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->xavcClass = $input['XavcClass'] ?? null;
    }

    /**
     * @param array{
     *   XavcClass?: null|Xavc4kIntraCbgProfileClass::*|string,
     * }|Xavc4kIntraCbgProfileSettings $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return Xavc4kIntraCbgProfileClass::*|string|null
     */
    public function getXavcClass(): ?string
    {
        return $this->xavcClass;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->xavcClass) {
            if (!Xavc4kIntraCbgProfileClass::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "xavcClass" for "%s". The value "%s" is not a valid "Xavc4kIntraCbgProfileClass".', __CLASS__, $v));
            }
            $payload['xavcClass'] = $v;
        }

        return $payload;
    }
}
