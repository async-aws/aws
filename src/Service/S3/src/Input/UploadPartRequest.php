<?php

namespace AsyncAws\S3\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;
use AsyncAws\S3\Enum\RequestPayer;

final class UploadPartRequest extends Input
{
    /**
     * Object data.
     *
     * @var string|resource|callable|iterable|null
     */
    private $Body;

    /**
     * Name of the bucket to which the multipart upload was initiated.
     *
     * @required
     *
     * @var string|null
     */
    private $Bucket;

    /**
     * Size of the body in bytes. This parameter is useful when the size of the body cannot be determined automatically.
     *
     * @var string|null
     */
    private $ContentLength;

    /**
     * The base64-encoded 128-bit MD5 digest of the part data. This parameter is auto-populated when using the command from
     * the CLI. This parameter is required if object lock parameters are specified.
     *
     * @var string|null
     */
    private $ContentMD5;

    /**
     * Object key for which the multipart upload was initiated.
     *
     * @required
     *
     * @var string|null
     */
    private $Key;

    /**
     * Part number of part being uploaded. This is a positive integer between 1 and 10,000.
     *
     * @required
     *
     * @var int|null
     */
    private $PartNumber;

    /**
     * Upload ID identifying the multipart upload whose part is being uploaded.
     *
     * @required
     *
     * @var string|null
     */
    private $UploadId;

    /**
     * Specifies the algorithm to use to when encrypting the object (for example, AES256).
     *
     * @var string|null
     */
    private $SSECustomerAlgorithm;

    /**
     * Specifies the customer-provided encryption key for Amazon S3 to use in encrypting data. This value is used to store
     * the object and then it is discarded; Amazon S3 does not store the encryption key. The key must be appropriate for use
     * with the algorithm specified in the `x-amz-server-side​-encryption​-customer-algorithm header`. This must be the
     * same encryption key specified in the initiate multipart upload request.
     *
     * @var string|null
     */
    private $SSECustomerKey;

    /**
     * Specifies the 128-bit MD5 digest of the encryption key according to RFC 1321. Amazon S3 uses this header for a
     * message integrity check to ensure that the encryption key was transmitted without error.
     *
     * @var string|null
     */
    private $SSECustomerKeyMD5;

    /**
     * @var null|RequestPayer::*
     */
    private $RequestPayer;

    /**
     * @see http://docs.amazonwebservices.com/AmazonS3/latest/API/mpUploadUploadPart.html
     *
     * @param array{
     *   Body?: string|resource|callable|iterable,
     *   Bucket?: string,
     *   ContentLength?: string,
     *   ContentMD5?: string,
     *   Key?: string,
     *   PartNumber?: int,
     *   UploadId?: string,
     *   SSECustomerAlgorithm?: string,
     *   SSECustomerKey?: string,
     *   SSECustomerKeyMD5?: string,
     *   RequestPayer?: \AsyncAws\S3\Enum\RequestPayer::*,
     *   @region?: string,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->Body = $input['Body'] ?? null;
        $this->Bucket = $input['Bucket'] ?? null;
        $this->ContentLength = $input['ContentLength'] ?? null;
        $this->ContentMD5 = $input['ContentMD5'] ?? null;
        $this->Key = $input['Key'] ?? null;
        $this->PartNumber = $input['PartNumber'] ?? null;
        $this->UploadId = $input['UploadId'] ?? null;
        $this->SSECustomerAlgorithm = $input['SSECustomerAlgorithm'] ?? null;
        $this->SSECustomerKey = $input['SSECustomerKey'] ?? null;
        $this->SSECustomerKeyMD5 = $input['SSECustomerKeyMD5'] ?? null;
        $this->RequestPayer = $input['RequestPayer'] ?? null;
        parent::__construct($input);
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return string|resource|callable|iterable|null
     */
    public function getBody()
    {
        return $this->Body;
    }

    public function getBucket(): ?string
    {
        return $this->Bucket;
    }

    public function getContentLength(): ?string
    {
        return $this->ContentLength;
    }

    public function getContentMD5(): ?string
    {
        return $this->ContentMD5;
    }

    public function getKey(): ?string
    {
        return $this->Key;
    }

    public function getPartNumber(): ?int
    {
        return $this->PartNumber;
    }

    /**
     * @return RequestPayer::*|null
     */
    public function getRequestPayer(): ?string
    {
        return $this->RequestPayer;
    }

    public function getSSECustomerAlgorithm(): ?string
    {
        return $this->SSECustomerAlgorithm;
    }

    public function getSSECustomerKey(): ?string
    {
        return $this->SSECustomerKey;
    }

    public function getSSECustomerKeyMD5(): ?string
    {
        return $this->SSECustomerKeyMD5;
    }

    public function getUploadId(): ?string
    {
        return $this->UploadId;
    }

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = [];
        if (null !== $this->ContentLength) {
            $headers['Content-Length'] = $this->ContentLength;
        }
        if (null !== $this->ContentMD5) {
            $headers['Content-MD5'] = $this->ContentMD5;
        }
        if (null !== $this->SSECustomerAlgorithm) {
            $headers['x-amz-server-side-encryption-customer-algorithm'] = $this->SSECustomerAlgorithm;
        }
        if (null !== $this->SSECustomerKey) {
            $headers['x-amz-server-side-encryption-customer-key'] = $this->SSECustomerKey;
        }
        if (null !== $this->SSECustomerKeyMD5) {
            $headers['x-amz-server-side-encryption-customer-key-MD5'] = $this->SSECustomerKeyMD5;
        }
        if (null !== $this->RequestPayer) {
            if (!RequestPayer::exists($this->RequestPayer)) {
                throw new InvalidArgument(sprintf('Invalid parameter "RequestPayer" for "%s". The value "%s" is not a valid "RequestPayer".', __CLASS__, $this->RequestPayer));
            }
            $headers['x-amz-request-payer'] = $this->RequestPayer;
        }

        // Prepare query
        $query = [];
        if (null === $v = $this->PartNumber) {
            throw new InvalidArgument(sprintf('Missing parameter "PartNumber" for "%s". The value cannot be null.', __CLASS__));
        }
        $query['partNumber'] = (string) $v;
        if (null === $v = $this->UploadId) {
            throw new InvalidArgument(sprintf('Missing parameter "UploadId" for "%s". The value cannot be null.', __CLASS__));
        }
        $query['uploadId'] = $v;

        // Prepare URI
        $uri = [];
        if (null === $v = $this->Bucket) {
            throw new InvalidArgument(sprintf('Missing parameter "Bucket" for "%s". The value cannot be null.', __CLASS__));
        }
        $uri['Bucket'] = $v;
        if (null === $v = $this->Key) {
            throw new InvalidArgument(sprintf('Missing parameter "Key" for "%s". The value cannot be null.', __CLASS__));
        }
        $uri['Key'] = $v;
        $uriString = "/{$uri['Bucket']}/{$uri['Key']}";

        // Prepare Body
        $body = $this->Body ?? '';

        // Return the Request
        return new Request('PUT', $uriString, $query, $headers, StreamFactory::create($body));
    }

    /**
     * @param string|resource|callable|iterable|null $value
     */
    public function setBody($value): self
    {
        $this->Body = $value;

        return $this;
    }

    public function setBucket(?string $value): self
    {
        $this->Bucket = $value;

        return $this;
    }

    public function setContentLength(?string $value): self
    {
        $this->ContentLength = $value;

        return $this;
    }

    public function setContentMD5(?string $value): self
    {
        $this->ContentMD5 = $value;

        return $this;
    }

    public function setKey(?string $value): self
    {
        $this->Key = $value;

        return $this;
    }

    public function setPartNumber(?int $value): self
    {
        $this->PartNumber = $value;

        return $this;
    }

    /**
     * @param RequestPayer::*|null $value
     */
    public function setRequestPayer(?string $value): self
    {
        $this->RequestPayer = $value;

        return $this;
    }

    public function setSSECustomerAlgorithm(?string $value): self
    {
        $this->SSECustomerAlgorithm = $value;

        return $this;
    }

    public function setSSECustomerKey(?string $value): self
    {
        $this->SSECustomerKey = $value;

        return $this;
    }

    public function setSSECustomerKeyMD5(?string $value): self
    {
        $this->SSECustomerKeyMD5 = $value;

        return $this;
    }

    public function setUploadId(?string $value): self
    {
        $this->UploadId = $value;

        return $this;
    }
}
