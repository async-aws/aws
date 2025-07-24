<?php

namespace AsyncAws\MediaConvert\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\MediaConvert\Enum\TeletextPageType;

/**
 * Settings related to teletext captions. Set up teletext captions in the same output as your video. For more
 * information, see https://docs.aws.amazon.com/mediaconvert/latest/ug/teletext-output-captions.html.
 */
final class TeletextDestinationSettings
{
    /**
     * Set pageNumber to the Teletext page number for the destination captions for this output. This value must be a
     * three-digit hexadecimal string; strings ending in -FF are invalid. If you are passing through the entire set of
     * Teletext data, do not use this field.
     *
     * @var string|null
     */
    private $pageNumber;

    /**
     * Specify the page types for this Teletext page. If you don't specify a value here, the service sets the page type to
     * the default value Subtitle. If you pass through the entire set of Teletext data, don't use this field. When you pass
     * through a set of Teletext pages, your output has the same page types as your input.
     *
     * @var list<TeletextPageType::*|string>|null
     */
    private $pageTypes;

    /**
     * @param array{
     *   PageNumber?: null|string,
     *   PageTypes?: null|array<TeletextPageType::*|string>,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->pageNumber = $input['PageNumber'] ?? null;
        $this->pageTypes = $input['PageTypes'] ?? null;
    }

    /**
     * @param array{
     *   PageNumber?: null|string,
     *   PageTypes?: null|array<TeletextPageType::*|string>,
     * }|TeletextDestinationSettings $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getPageNumber(): ?string
    {
        return $this->pageNumber;
    }

    /**
     * @return list<TeletextPageType::*|string>
     */
    public function getPageTypes(): array
    {
        return $this->pageTypes ?? [];
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->pageNumber) {
            $payload['pageNumber'] = $v;
        }
        if (null !== $v = $this->pageTypes) {
            $index = -1;
            $payload['pageTypes'] = [];
            foreach ($v as $listValue) {
                ++$index;
                if (!TeletextPageType::exists($listValue)) {
                    throw new InvalidArgument(\sprintf('Invalid parameter "pageTypes" for "%s". The value "%s" is not a valid "TeletextPageType".', __CLASS__, $listValue));
                }
                $payload['pageTypes'][$index] = $listValue;
            }
        }

        return $payload;
    }
}
