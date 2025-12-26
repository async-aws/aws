<?php

namespace AsyncAws\MediaConvert\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\MediaConvert\Enum\CopyProtectionAction;
use AsyncAws\MediaConvert\Enum\VchipAction;

/**
 * If your source content has EIA-608 Line 21 Data Services, enable this feature to specify what MediaConvert does with
 * the Extended Data Services (XDS) packets. You can choose to pass through XDS packets, or remove them from the output.
 * For more information about XDS, see EIA-608 Line Data Services, section 9.5.1.5 05h Content Advisory.
 */
final class ExtendedDataServices
{
    /**
     * The action to take on copy and redistribution control XDS packets. If you select PASSTHROUGH, packets will not be
     * changed. If you select STRIP, any packets will be removed in output captions.
     *
     * @var CopyProtectionAction::*|null
     */
    private $copyProtectionAction;

    /**
     * The action to take on content advisory XDS packets. If you select PASSTHROUGH, packets will not be changed. If you
     * select STRIP, any packets will be removed in output captions.
     *
     * @var VchipAction::*|null
     */
    private $vchipAction;

    /**
     * @param array{
     *   CopyProtectionAction?: CopyProtectionAction::*|null,
     *   VchipAction?: VchipAction::*|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->copyProtectionAction = $input['CopyProtectionAction'] ?? null;
        $this->vchipAction = $input['VchipAction'] ?? null;
    }

    /**
     * @param array{
     *   CopyProtectionAction?: CopyProtectionAction::*|null,
     *   VchipAction?: VchipAction::*|null,
     * }|ExtendedDataServices $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return CopyProtectionAction::*|null
     */
    public function getCopyProtectionAction(): ?string
    {
        return $this->copyProtectionAction;
    }

    /**
     * @return VchipAction::*|null
     */
    public function getVchipAction(): ?string
    {
        return $this->vchipAction;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->copyProtectionAction) {
            if (!CopyProtectionAction::exists($v)) {
                /** @psalm-suppress NoValue */
                throw new InvalidArgument(\sprintf('Invalid parameter "copyProtectionAction" for "%s". The value "%s" is not a valid "CopyProtectionAction".', __CLASS__, $v));
            }
            $payload['copyProtectionAction'] = $v;
        }
        if (null !== $v = $this->vchipAction) {
            if (!VchipAction::exists($v)) {
                /** @psalm-suppress NoValue */
                throw new InvalidArgument(\sprintf('Invalid parameter "vchipAction" for "%s". The value "%s" is not a valid "VchipAction".', __CLASS__, $v));
            }
            $payload['vchipAction'] = $v;
        }

        return $payload;
    }
}
