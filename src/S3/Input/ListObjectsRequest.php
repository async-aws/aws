<?php

namespace AsyncAws\S3\Input;

use AsyncAws\Core\Exception\InvalidArgument;

class ListObjectsRequest
{
    /**
     * The name of the bucket containing the objects.
     *
     * @required
     *
     * @var string|null
     */
    private $Bucket;

    /**
     * A delimiter is a character you use to group keys.
     *
     * @var string|null
     */
    private $Delimiter;

    /**
     * @var string|null
     */
    private $EncodingType;

    /**
     * Specifies the key to start with when listing objects in a bucket.
     *
     * @var string|null
     */
    private $Marker;

    /**
     * Sets the maximum number of keys returned in the response. The response might contain fewer keys but will never
     * contain more.
     *
     * @var int|null
     */
    private $MaxKeys;

    /**
     * Limits the response to keys that begin with the specified prefix.
     *
     * @var string|null
     */
    private $Prefix;

    /**
     * Confirms that the requester knows that she or he will be charged for the list objects request. Bucket owners need not
     * specify this parameter in their requests.
     *
     * @var string|null
     */
    private $RequestPayer;

    /**
     * @see http://docs.amazonwebservices.com/AmazonS3/latest/API/RESTBucketGET.html
     *
     * @param array{
     *   Bucket?: string,
     *   Delimiter?: string,
     *   EncodingType?: string,
     *   Marker?: string,
     *   MaxKeys?: int,
     *   Prefix?: string,
     *   RequestPayer?: string,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->Bucket = $input['Bucket'] ?? null;
        $this->Delimiter = $input['Delimiter'] ?? null;
        $this->EncodingType = $input['EncodingType'] ?? null;
        $this->Marker = $input['Marker'] ?? null;
        $this->MaxKeys = $input['MaxKeys'] ?? null;
        $this->Prefix = $input['Prefix'] ?? null;
        $this->RequestPayer = $input['RequestPayer'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getBucket(): ?string
    {
        return $this->Bucket;
    }

    public function getDelimiter(): ?string
    {
        return $this->Delimiter;
    }

    public function getEncodingType(): ?string
    {
        return $this->EncodingType;
    }

    public function getMarker(): ?string
    {
        return $this->Marker;
    }

    public function getMaxKeys(): ?int
    {
        return $this->MaxKeys;
    }

    public function getPrefix(): ?string
    {
        return $this->Prefix;
    }

    public function getRequestPayer(): ?string
    {
        return $this->RequestPayer;
    }

    public function requestBody(): array
    {
        $payload = ['Action' => 'ListObjects', 'Version' => '2006-03-01'];

        return $payload;
    }

    public function requestHeaders(): array
    {
        $headers = [];
        if (null !== $this->RequestPayer) {
            $headers['x-amz-request-payer'] = $this->RequestPayer;
        }

        return $headers;
    }

    public function requestQuery(): array
    {
        $query = [];
        if (null !== $this->Delimiter) {
            $query['delimiter'] = $this->Delimiter;
        }
        if (null !== $this->EncodingType) {
            $query['encoding-type'] = $this->EncodingType;
        }
        if (null !== $this->Marker) {
            $query['marker'] = $this->Marker;
        }
        if (null !== $this->MaxKeys) {
            $query['max-keys'] = $this->MaxKeys;
        }
        if (null !== $this->Prefix) {
            $query['prefix'] = $this->Prefix;
        }

        return $query;
    }

    public function requestUri(): string
    {
        $uri = [];
        $uri['Bucket'] = $this->Bucket ?? '';

        return "/{$uri['Bucket']}";
    }

    public function setBucket(?string $value): self
    {
        $this->Bucket = $value;

        return $this;
    }

    public function setDelimiter(?string $value): self
    {
        $this->Delimiter = $value;

        return $this;
    }

    public function setEncodingType(?string $value): self
    {
        $this->EncodingType = $value;

        return $this;
    }

    public function setMarker(?string $value): self
    {
        $this->Marker = $value;

        return $this;
    }

    public function setMaxKeys(?int $value): self
    {
        $this->MaxKeys = $value;

        return $this;
    }

    public function setPrefix(?string $value): self
    {
        $this->Prefix = $value;

        return $this;
    }

    public function setRequestPayer(?string $value): self
    {
        $this->RequestPayer = $value;

        return $this;
    }

    public function validate(): void
    {
        foreach (['Bucket'] as $name) {
            if (null === $this->$name) {
                throw new InvalidArgument(sprintf('Missing parameter "%s" when validating the "%s". The value cannot be null.', $name, __CLASS__));
            }
        }
    }
}
