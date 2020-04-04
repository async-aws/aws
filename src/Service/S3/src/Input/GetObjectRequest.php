<?php

namespace AsyncAws\S3\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;
use AsyncAws\S3\Enum\RequestPayer;

final class GetObjectRequest implements Input
{
    /**
     * The bucket name containing the object.
     *
     * @required
     *
     * @var string|null
     */
    private $Bucket;

    /**
     * Return the object only if its entity tag (ETag) is the same as the one specified, otherwise return a 412
     * (precondition failed).
     *
     * @var string|null
     */
    private $IfMatch;

    /**
     * Return the object only if it has been modified since the specified time, otherwise return a 304 (not modified).
     *
     * @var \DateTimeImmutable|null
     */
    private $IfModifiedSince;

    /**
     * Return the object only if its entity tag (ETag) is different from the one specified, otherwise return a 304 (not
     * modified).
     *
     * @var string|null
     */
    private $IfNoneMatch;

    /**
     * Return the object only if it has not been modified since the specified time, otherwise return a 412 (precondition
     * failed).
     *
     * @var \DateTimeImmutable|null
     */
    private $IfUnmodifiedSince;

    /**
     * Key of the object to get.
     *
     * @required
     *
     * @var string|null
     */
    private $Key;

    /**
     * Downloads the specified range bytes of an object. For more information about the HTTP Range header, see
     * http://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.35.
     *
     * @see
     *
     * @var string|null
     */
    private $Range;

    /**
     * Sets the `Cache-Control` header of the response.
     *
     * @var string|null
     */
    private $ResponseCacheControl;

    /**
     * Sets the `Content-Disposition` header of the response.
     *
     * @var string|null
     */
    private $ResponseContentDisposition;

    /**
     * Sets the `Content-Encoding` header of the response.
     *
     * @var string|null
     */
    private $ResponseContentEncoding;

    /**
     * Sets the `Content-Language` header of the response.
     *
     * @var string|null
     */
    private $ResponseContentLanguage;

    /**
     * Sets the `Content-Type` header of the response.
     *
     * @var string|null
     */
    private $ResponseContentType;

    /**
     * Sets the `Expires` header of the response.
     *
     * @var \DateTimeImmutable|null
     */
    private $ResponseExpires;

    /**
     * VersionId used to reference a specific version of the object.
     *
     * @var string|null
     */
    private $VersionId;

    /**
     * Specifies the algorithm to use to when encrypting the object (for example, AES256).
     *
     * @var string|null
     */
    private $SSECustomerAlgorithm;

    /**
     * Specifies the customer-provided encryption key for Amazon S3 to use in encrypting data. This value is used to store
     * the object and then it is discarded; Amazon S3 does not store the encryption key. The key must be appropriate for use
     * with the algorithm specified in the `x-amz-server-side​-encryption​-customer-algorithm` header.
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
     * Part number of the object being read. This is a positive integer between 1 and 10,000. Effectively performs a
     * 'ranged' GET request for the part specified. Useful for downloading just a part of an object.
     *
     * @var int|null
     */
    private $PartNumber;

    /**
     * @see http://docs.amazonwebservices.com/AmazonS3/latest/API/RESTObjectGET.html
     *
     * @param array{
     *   Bucket?: string,
     *   IfMatch?: string,
     *   IfModifiedSince?: \DateTimeImmutable|string,
     *   IfNoneMatch?: string,
     *   IfUnmodifiedSince?: \DateTimeImmutable|string,
     *   Key?: string,
     *   Range?: string,
     *   ResponseCacheControl?: string,
     *   ResponseContentDisposition?: string,
     *   ResponseContentEncoding?: string,
     *   ResponseContentLanguage?: string,
     *   ResponseContentType?: string,
     *   ResponseExpires?: \DateTimeImmutable|string,
     *   VersionId?: string,
     *   SSECustomerAlgorithm?: string,
     *   SSECustomerKey?: string,
     *   SSECustomerKeyMD5?: string,
     *   RequestPayer?: \AsyncAws\S3\Enum\RequestPayer::*,
     *   PartNumber?: int,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->Bucket = $input['Bucket'] ?? null;
        $this->IfMatch = $input['IfMatch'] ?? null;
        $this->IfModifiedSince = !isset($input['IfModifiedSince']) ? null : ($input['IfModifiedSince'] instanceof \DateTimeImmutable ? $input['IfModifiedSince'] : new \DateTimeImmutable($input['IfModifiedSince']));
        $this->IfNoneMatch = $input['IfNoneMatch'] ?? null;
        $this->IfUnmodifiedSince = !isset($input['IfUnmodifiedSince']) ? null : ($input['IfUnmodifiedSince'] instanceof \DateTimeImmutable ? $input['IfUnmodifiedSince'] : new \DateTimeImmutable($input['IfUnmodifiedSince']));
        $this->Key = $input['Key'] ?? null;
        $this->Range = $input['Range'] ?? null;
        $this->ResponseCacheControl = $input['ResponseCacheControl'] ?? null;
        $this->ResponseContentDisposition = $input['ResponseContentDisposition'] ?? null;
        $this->ResponseContentEncoding = $input['ResponseContentEncoding'] ?? null;
        $this->ResponseContentLanguage = $input['ResponseContentLanguage'] ?? null;
        $this->ResponseContentType = $input['ResponseContentType'] ?? null;
        $this->ResponseExpires = !isset($input['ResponseExpires']) ? null : ($input['ResponseExpires'] instanceof \DateTimeImmutable ? $input['ResponseExpires'] : new \DateTimeImmutable($input['ResponseExpires']));
        $this->VersionId = $input['VersionId'] ?? null;
        $this->SSECustomerAlgorithm = $input['SSECustomerAlgorithm'] ?? null;
        $this->SSECustomerKey = $input['SSECustomerKey'] ?? null;
        $this->SSECustomerKeyMD5 = $input['SSECustomerKeyMD5'] ?? null;
        $this->RequestPayer = $input['RequestPayer'] ?? null;
        $this->PartNumber = $input['PartNumber'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getBucket(): ?string
    {
        return $this->Bucket;
    }

    public function getIfMatch(): ?string
    {
        return $this->IfMatch;
    }

    public function getIfModifiedSince(): ?\DateTimeImmutable
    {
        return $this->IfModifiedSince;
    }

    public function getIfNoneMatch(): ?string
    {
        return $this->IfNoneMatch;
    }

    public function getIfUnmodifiedSince(): ?\DateTimeImmutable
    {
        return $this->IfUnmodifiedSince;
    }

    public function getKey(): ?string
    {
        return $this->Key;
    }

    public function getPartNumber(): ?int
    {
        return $this->PartNumber;
    }

    public function getRange(): ?string
    {
        return $this->Range;
    }

    /**
     * @return RequestPayer::*|null
     */
    public function getRequestPayer(): ?string
    {
        return $this->RequestPayer;
    }

    public function getResponseCacheControl(): ?string
    {
        return $this->ResponseCacheControl;
    }

    public function getResponseContentDisposition(): ?string
    {
        return $this->ResponseContentDisposition;
    }

    public function getResponseContentEncoding(): ?string
    {
        return $this->ResponseContentEncoding;
    }

    public function getResponseContentLanguage(): ?string
    {
        return $this->ResponseContentLanguage;
    }

    public function getResponseContentType(): ?string
    {
        return $this->ResponseContentType;
    }

    public function getResponseExpires(): ?\DateTimeImmutable
    {
        return $this->ResponseExpires;
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

    public function getVersionId(): ?string
    {
        return $this->VersionId;
    }

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = ['content-type' => 'application/xml'];
        if (null !== $this->IfMatch) {
            $headers['If-Match'] = $this->IfMatch;
        }
        if (null !== $this->IfModifiedSince) {
            $headers['If-Modified-Since'] = $this->IfModifiedSince->format(\DateTimeInterface::RFC822);
        }
        if (null !== $this->IfNoneMatch) {
            $headers['If-None-Match'] = $this->IfNoneMatch;
        }
        if (null !== $this->IfUnmodifiedSince) {
            $headers['If-Unmodified-Since'] = $this->IfUnmodifiedSince->format(\DateTimeInterface::RFC822);
        }
        if (null !== $this->Range) {
            $headers['Range'] = $this->Range;
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
        if (null !== $this->ResponseCacheControl) {
            $query['response-cache-control'] = $this->ResponseCacheControl;
        }
        if (null !== $this->ResponseContentDisposition) {
            $query['response-content-disposition'] = $this->ResponseContentDisposition;
        }
        if (null !== $this->ResponseContentEncoding) {
            $query['response-content-encoding'] = $this->ResponseContentEncoding;
        }
        if (null !== $this->ResponseContentLanguage) {
            $query['response-content-language'] = $this->ResponseContentLanguage;
        }
        if (null !== $this->ResponseContentType) {
            $query['response-content-type'] = $this->ResponseContentType;
        }
        if (null !== $this->ResponseExpires) {
            $query['response-expires'] = $this->ResponseExpires->format(\DateTimeInterface::ISO8601);
        }
        if (null !== $this->VersionId) {
            $query['versionId'] = $this->VersionId;
        }
        if (null !== $this->PartNumber) {
            $query['partNumber'] = (string) $this->PartNumber;
        }

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
        $body = '';

        // Return the Request
        return new Request('GET', $uriString, $query, $headers, StreamFactory::create($body));
    }

    public function setBucket(?string $value): self
    {
        $this->Bucket = $value;

        return $this;
    }

    public function setIfMatch(?string $value): self
    {
        $this->IfMatch = $value;

        return $this;
    }

    public function setIfModifiedSince(?\DateTimeImmutable $value): self
    {
        $this->IfModifiedSince = $value;

        return $this;
    }

    public function setIfNoneMatch(?string $value): self
    {
        $this->IfNoneMatch = $value;

        return $this;
    }

    public function setIfUnmodifiedSince(?\DateTimeImmutable $value): self
    {
        $this->IfUnmodifiedSince = $value;

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

    public function setRange(?string $value): self
    {
        $this->Range = $value;

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

    public function setResponseCacheControl(?string $value): self
    {
        $this->ResponseCacheControl = $value;

        return $this;
    }

    public function setResponseContentDisposition(?string $value): self
    {
        $this->ResponseContentDisposition = $value;

        return $this;
    }

    public function setResponseContentEncoding(?string $value): self
    {
        $this->ResponseContentEncoding = $value;

        return $this;
    }

    public function setResponseContentLanguage(?string $value): self
    {
        $this->ResponseContentLanguage = $value;

        return $this;
    }

    public function setResponseContentType(?string $value): self
    {
        $this->ResponseContentType = $value;

        return $this;
    }

    public function setResponseExpires(?\DateTimeImmutable $value): self
    {
        $this->ResponseExpires = $value;

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

    public function setVersionId(?string $value): self
    {
        $this->VersionId = $value;

        return $this;
    }
}
