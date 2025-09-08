<?php

namespace AsyncAws\Ses\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Ses\Enum\AttachmentContentDisposition;
use AsyncAws\Ses\Enum\AttachmentContentTransferEncoding;

/**
 * Contains metadata and attachment raw content.
 */
final class Attachment
{
    /**
     * The raw data of the attachment. It needs to be base64-encoded if you are accessing Amazon SES directly through the
     * HTTPS interface. If you are accessing Amazon SES using an Amazon Web Services SDK, the SDK takes care of the base
     * 64-encoding for you.
     *
     * @var string
     */
    private $rawContent;

    /**
     * A standard descriptor indicating how the attachment should be rendered in the email. Supported values: `ATTACHMENT`
     * or `INLINE`.
     *
     * @var AttachmentContentDisposition::*|null
     */
    private $contentDisposition;

    /**
     * The file name for the attachment as it will appear in the email. Amazon SES restricts certain file extensions. To
     * ensure attachments are accepted, check the Unsupported attachment types [^1] in the Amazon SES Developer Guide.
     *
     * [^1]: https://docs.aws.amazon.com/ses/latest/dg/mime-types.html
     *
     * @var string
     */
    private $fileName;

    /**
     * A brief description of the attachment content.
     *
     * @var string|null
     */
    private $contentDescription;

    /**
     * Unique identifier for the attachment, used for referencing attachments with INLINE disposition in HTML content.
     *
     * @var string|null
     */
    private $contentId;

    /**
     * Specifies how the attachment is encoded. Supported values: `BASE64`, `QUOTED_PRINTABLE`, `SEVEN_BIT`.
     *
     * @var AttachmentContentTransferEncoding::*|null
     */
    private $contentTransferEncoding;

    /**
     * The MIME type of the attachment.
     *
     * > Example: `application/pdf`, `image/jpeg`
     *
     * @var string|null
     */
    private $contentType;

    /**
     * @param array{
     *   RawContent: string,
     *   ContentDisposition?: AttachmentContentDisposition::*|null,
     *   FileName: string,
     *   ContentDescription?: string|null,
     *   ContentId?: string|null,
     *   ContentTransferEncoding?: AttachmentContentTransferEncoding::*|null,
     *   ContentType?: string|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->rawContent = $input['RawContent'] ?? $this->throwException(new InvalidArgument('Missing required field "RawContent".'));
        $this->contentDisposition = $input['ContentDisposition'] ?? null;
        $this->fileName = $input['FileName'] ?? $this->throwException(new InvalidArgument('Missing required field "FileName".'));
        $this->contentDescription = $input['ContentDescription'] ?? null;
        $this->contentId = $input['ContentId'] ?? null;
        $this->contentTransferEncoding = $input['ContentTransferEncoding'] ?? null;
        $this->contentType = $input['ContentType'] ?? null;
    }

    /**
     * @param array{
     *   RawContent: string,
     *   ContentDisposition?: AttachmentContentDisposition::*|null,
     *   FileName: string,
     *   ContentDescription?: string|null,
     *   ContentId?: string|null,
     *   ContentTransferEncoding?: AttachmentContentTransferEncoding::*|null,
     *   ContentType?: string|null,
     * }|Attachment $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getContentDescription(): ?string
    {
        return $this->contentDescription;
    }

    /**
     * @return AttachmentContentDisposition::*|null
     */
    public function getContentDisposition(): ?string
    {
        return $this->contentDisposition;
    }

    public function getContentId(): ?string
    {
        return $this->contentId;
    }

    /**
     * @return AttachmentContentTransferEncoding::*|null
     */
    public function getContentTransferEncoding(): ?string
    {
        return $this->contentTransferEncoding;
    }

    public function getContentType(): ?string
    {
        return $this->contentType;
    }

    public function getFileName(): string
    {
        return $this->fileName;
    }

    public function getRawContent(): string
    {
        return $this->rawContent;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        $v = $this->rawContent;
        $payload['RawContent'] = base64_encode($v);
        if (null !== $v = $this->contentDisposition) {
            if (!AttachmentContentDisposition::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "ContentDisposition" for "%s". The value "%s" is not a valid "AttachmentContentDisposition".', __CLASS__, $v));
            }
            $payload['ContentDisposition'] = $v;
        }
        $v = $this->fileName;
        $payload['FileName'] = $v;
        if (null !== $v = $this->contentDescription) {
            $payload['ContentDescription'] = $v;
        }
        if (null !== $v = $this->contentId) {
            $payload['ContentId'] = $v;
        }
        if (null !== $v = $this->contentTransferEncoding) {
            if (!AttachmentContentTransferEncoding::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "ContentTransferEncoding" for "%s". The value "%s" is not a valid "AttachmentContentTransferEncoding".', __CLASS__, $v));
            }
            $payload['ContentTransferEncoding'] = $v;
        }
        if (null !== $v = $this->contentType) {
            $payload['ContentType'] = $v;
        }

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
