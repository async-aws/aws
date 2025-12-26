<?php

namespace AsyncAws\MediaConvert\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\MediaConvert\Enum\XavcHdIntraCbgProfileClass;

/**
 * Required when you set Profile to the value XAVC_HD_INTRA_CBG.
 */
final class XavcHdIntraCbgProfileSettings
{
    /**
     * Specify the XAVC Intra HD (CBG) Class to set the bitrate of your output. Outputs of the same class have similar image
     * quality over the operating points that are valid for that class.
     *
     * @var XavcHdIntraCbgProfileClass::*|null
     */
    private $xavcClass;

    /**
     * @param array{
     *   XavcClass?: XavcHdIntraCbgProfileClass::*|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->xavcClass = $input['XavcClass'] ?? null;
    }

    /**
     * @param array{
     *   XavcClass?: XavcHdIntraCbgProfileClass::*|null,
     * }|XavcHdIntraCbgProfileSettings $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return XavcHdIntraCbgProfileClass::*|null
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
            if (!XavcHdIntraCbgProfileClass::exists($v)) {
                /** @psalm-suppress NoValue */
                throw new InvalidArgument(\sprintf('Invalid parameter "xavcClass" for "%s". The value "%s" is not a valid "XavcHdIntraCbgProfileClass".', __CLASS__, $v));
            }
            $payload['xavcClass'] = $v;
        }

        return $payload;
    }
}
