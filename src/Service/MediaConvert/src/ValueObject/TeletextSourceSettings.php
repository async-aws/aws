<?php

namespace AsyncAws\MediaConvert\ValueObject;

/**
 * Settings specific to Teletext caption sources, including Page number.
 */
final class TeletextSourceSettings
{
    /**
     * Use Page Number to specify the three-digit hexadecimal page number that will be used for Teletext captions. Do not
     * use this setting if you are passing through teletext from the input source to output.
     *
     * @var string|null
     */
    private $pageNumber;

    /**
     * @param array{
     *   PageNumber?: string|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->pageNumber = $input['PageNumber'] ?? null;
    }

    /**
     * @param array{
     *   PageNumber?: string|null,
     * }|TeletextSourceSettings $input
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
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->pageNumber) {
            $payload['pageNumber'] = $v;
        }

        return $payload;
    }
}
