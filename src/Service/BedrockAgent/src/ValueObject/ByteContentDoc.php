<?php

namespace AsyncAws\BedrockAgent\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * Contains information about content defined inline in bytes.
 */
final class ByteContentDoc
{
    /**
     * The MIME type of the content. For a list of MIME types, see Media Types [^1]. The following MIME types are supported:
     *
     * - text/plain
     * - text/html
     * - text/csv
     * - text/vtt
     * - message/rfc822
     * - application/xhtml+xml
     * - application/pdf
     * - application/msword
     * - application/vnd.ms-word.document.macroenabled.12
     * - application/vnd.ms-word.template.macroenabled.12
     * - application/vnd.ms-excel
     * - application/vnd.ms-excel.addin.macroenabled.12
     * - application/vnd.ms-excel.sheet.macroenabled.12
     * - application/vnd.ms-excel.template.macroenabled.12
     * - application/vnd.ms-excel.sheet.binary.macroenabled.12
     * - application/vnd.ms-spreadsheetml
     * - application/vnd.openxmlformats-officedocument.spreadsheetml.sheet
     * - application/vnd.openxmlformats-officedocument.spreadsheetml.template
     * - application/vnd.openxmlformats-officedocument.wordprocessingml.document
     * - application/vnd.openxmlformats-officedocument.wordprocessingml.template
     *
     * [^1]: https://www.iana.org/assignments/media-types/media-types.xhtml
     *
     * @var string
     */
    private $mimeType;

    /**
     * The base64-encoded string of the content.
     *
     * @var string
     */
    private $data;

    /**
     * @param array{
     *   mimeType: string,
     *   data: string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->mimeType = $input['mimeType'] ?? $this->throwException(new InvalidArgument('Missing required field "mimeType".'));
        $this->data = $input['data'] ?? $this->throwException(new InvalidArgument('Missing required field "data".'));
    }

    /**
     * @param array{
     *   mimeType: string,
     *   data: string,
     * }|ByteContentDoc $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getData(): string
    {
        return $this->data;
    }

    public function getMimeType(): string
    {
        return $this->mimeType;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        $v = $this->mimeType;
        $payload['mimeType'] = $v;
        $v = $this->data;
        $payload['data'] = base64_encode($v);

        return $payload;
    }

    /**
     * @return never
     */
    private function throwException(\Throwable $exception)
    {
        throw $exception;
    }
}
