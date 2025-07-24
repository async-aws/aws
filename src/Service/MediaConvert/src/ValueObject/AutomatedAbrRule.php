<?php

namespace AsyncAws\MediaConvert\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\MediaConvert\Enum\RuleType;

/**
 * Specify one or more Automated ABR rule types. Note: Force include and Allowed renditions are mutually exclusive.
 */
final class AutomatedAbrRule
{
    /**
     * When customer adds the allowed renditions rule for auto ABR ladder, they are required to add at leat one rendition to
     * allowedRenditions list.
     *
     * @var AllowedRenditionSize[]|null
     */
    private $allowedRenditions;

    /**
     * When customer adds the force include renditions rule for auto ABR ladder, they are required to add at leat one
     * rendition to forceIncludeRenditions list.
     *
     * @var ForceIncludeRenditionSize[]|null
     */
    private $forceIncludeRenditions;

    /**
     * Use Min bottom rendition size to specify a minimum size for the lowest resolution in your ABR stack. * The lowest
     * resolution in your ABR stack will be equal to or greater than the value that you enter. For example: If you specify
     * 640x360 the lowest resolution in your ABR stack will be equal to or greater than to 640x360. * If you specify a Min
     * top rendition size rule, the value that you specify for Min bottom rendition size must be less than, or equal to, Min
     * top rendition size.
     *
     * @var MinBottomRenditionSize|null
     */
    private $minBottomRenditionSize;

    /**
     * Use Min top rendition size to specify a minimum size for the highest resolution in your ABR stack. * The highest
     * resolution in your ABR stack will be equal to or greater than the value that you enter. For example: If you specify
     * 1280x720 the highest resolution in your ABR stack will be equal to or greater than 1280x720. * If you specify a value
     * for Max resolution, the value that you specify for Min top rendition size must be less than, or equal to, Max
     * resolution.
     *
     * @var MinTopRenditionSize|null
     */
    private $minTopRenditionSize;

    /**
     * Use Min top rendition size to specify a minimum size for the highest resolution in your ABR stack. * The highest
     * resolution in your ABR stack will be equal to or greater than the value that you enter. For example: If you specify
     * 1280x720 the highest resolution in your ABR stack will be equal to or greater than 1280x720. * If you specify a value
     * for Max resolution, the value that you specify for Min top rendition size must be less than, or equal to, Max
     * resolution. Use Min bottom rendition size to specify a minimum size for the lowest resolution in your ABR stack. *
     * The lowest resolution in your ABR stack will be equal to or greater than the value that you enter. For example: If
     * you specify 640x360 the lowest resolution in your ABR stack will be equal to or greater than to 640x360. * If you
     * specify a Min top rendition size rule, the value that you specify for Min bottom rendition size must be less than, or
     * equal to, Min top rendition size. Use Force include renditions to specify one or more resolutions to include your ABR
     * stack. * (Recommended) To optimize automated ABR, specify as few resolutions as possible. * (Required) The number of
     * resolutions that you specify must be equal to, or less than, the Max renditions setting. * If you specify a Min top
     * rendition size rule, specify at least one resolution that is equal to, or greater than, Min top rendition size. * If
     * you specify a Min bottom rendition size rule, only specify resolutions that are equal to, or greater than, Min bottom
     * rendition size. * If you specify a Force include renditions rule, do not specify a separate rule for Allowed
     * renditions. * Note: The ABR stack may include other resolutions that you do not specify here, depending on the Max
     * renditions setting. Use Allowed renditions to specify a list of possible resolutions in your ABR stack. * (Required)
     * The number of resolutions that you specify must be equal to, or greater than, the Max renditions setting. *
     * MediaConvert will create an ABR stack exclusively from the list of resolutions that you specify. * Some resolutions
     * in the Allowed renditions list may not be included, however you can force a resolution to be included by setting
     * Required to ENABLED. * You must specify at least one resolution that is greater than or equal to any resolutions that
     * you specify in Min top rendition size or Min bottom rendition size. * If you specify Allowed renditions, you must not
     * specify a separate rule for Force include renditions.
     *
     * @var RuleType::*|string|null
     */
    private $type;

    /**
     * @param array{
     *   AllowedRenditions?: null|array<AllowedRenditionSize|array>,
     *   ForceIncludeRenditions?: null|array<ForceIncludeRenditionSize|array>,
     *   MinBottomRenditionSize?: null|MinBottomRenditionSize|array,
     *   MinTopRenditionSize?: null|MinTopRenditionSize|array,
     *   Type?: null|RuleType::*|string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->allowedRenditions = isset($input['AllowedRenditions']) ? array_map([AllowedRenditionSize::class, 'create'], $input['AllowedRenditions']) : null;
        $this->forceIncludeRenditions = isset($input['ForceIncludeRenditions']) ? array_map([ForceIncludeRenditionSize::class, 'create'], $input['ForceIncludeRenditions']) : null;
        $this->minBottomRenditionSize = isset($input['MinBottomRenditionSize']) ? MinBottomRenditionSize::create($input['MinBottomRenditionSize']) : null;
        $this->minTopRenditionSize = isset($input['MinTopRenditionSize']) ? MinTopRenditionSize::create($input['MinTopRenditionSize']) : null;
        $this->type = $input['Type'] ?? null;
    }

    /**
     * @param array{
     *   AllowedRenditions?: null|array<AllowedRenditionSize|array>,
     *   ForceIncludeRenditions?: null|array<ForceIncludeRenditionSize|array>,
     *   MinBottomRenditionSize?: null|MinBottomRenditionSize|array,
     *   MinTopRenditionSize?: null|MinTopRenditionSize|array,
     *   Type?: null|RuleType::*|string,
     * }|AutomatedAbrRule $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return AllowedRenditionSize[]
     */
    public function getAllowedRenditions(): array
    {
        return $this->allowedRenditions ?? [];
    }

    /**
     * @return ForceIncludeRenditionSize[]
     */
    public function getForceIncludeRenditions(): array
    {
        return $this->forceIncludeRenditions ?? [];
    }

    public function getMinBottomRenditionSize(): ?MinBottomRenditionSize
    {
        return $this->minBottomRenditionSize;
    }

    public function getMinTopRenditionSize(): ?MinTopRenditionSize
    {
        return $this->minTopRenditionSize;
    }

    /**
     * @return RuleType::*|string|null
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->allowedRenditions) {
            $index = -1;
            $payload['allowedRenditions'] = [];
            foreach ($v as $listValue) {
                ++$index;
                $payload['allowedRenditions'][$index] = $listValue->requestBody();
            }
        }
        if (null !== $v = $this->forceIncludeRenditions) {
            $index = -1;
            $payload['forceIncludeRenditions'] = [];
            foreach ($v as $listValue) {
                ++$index;
                $payload['forceIncludeRenditions'][$index] = $listValue->requestBody();
            }
        }
        if (null !== $v = $this->minBottomRenditionSize) {
            $payload['minBottomRenditionSize'] = $v->requestBody();
        }
        if (null !== $v = $this->minTopRenditionSize) {
            $payload['minTopRenditionSize'] = $v->requestBody();
        }
        if (null !== $v = $this->type) {
            if (!RuleType::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "type" for "%s". The value "%s" is not a valid "RuleType".', __CLASS__, $v));
            }
            $payload['type'] = $v;
        }

        return $payload;
    }
}
