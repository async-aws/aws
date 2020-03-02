<?php

namespace AsyncAws\S3\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\S3\Enum\EncodingType;
use AsyncAws\S3\Enum\RequestPayer;

class ListObjectsV2Request
{
    /**
     * Bucket name to list.
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
     * Encoding type used by Amazon S3 to encode object keys in the response.
     *
     * @var EncodingType::URL|null
     */
    private $EncodingType;

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
     * ContinuationToken indicates Amazon S3 that the list is being continued on this bucket with a token. ContinuationToken
     * is obfuscated and is not a real key.
     *
     * @var string|null
     */
    private $ContinuationToken;

    /**
     * The owner field is not present in listV2 by default, if you want to return owner field with each key in the result
     * then set the fetch owner field to true.
     *
     * @var bool|null
     */
    private $FetchOwner;

    /**
     * StartAfter is where you want Amazon S3 to start listing from. Amazon S3 starts listing after this specified key.
     * StartAfter can be any key in the bucket.
     *
     * @var string|null
     */
    private $StartAfter;

    /**
     * Confirms that the requester knows that she or he will be charged for the list objects request in V2 style. Bucket
     * owners need not specify this parameter in their requests.
     *
     * @var RequestPayer::REQUESTER|null
     */
    private $RequestPayer;

    /**
     * @param array{
     *   Bucket?: string,
     *   Delimiter?: string,
     *   EncodingType?: \AsyncAws\S3\Enum\EncodingType::URL,
     *   MaxKeys?: int,
     *   Prefix?: string,
     *   ContinuationToken?: string,
     *   FetchOwner?: bool,
     *   StartAfter?: string,
     *   RequestPayer?: \AsyncAws\S3\Enum\RequestPayer::REQUESTER,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->Bucket = $input['Bucket'] ?? null;
        $this->Delimiter = $input['Delimiter'] ?? null;
        $this->EncodingType = $input['EncodingType'] ?? null;
        $this->MaxKeys = $input['MaxKeys'] ?? null;
        $this->Prefix = $input['Prefix'] ?? null;
        $this->ContinuationToken = $input['ContinuationToken'] ?? null;
        $this->FetchOwner = $input['FetchOwner'] ?? null;
        $this->StartAfter = $input['StartAfter'] ?? null;
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

    public function getContinuationToken(): ?string
    {
        return $this->ContinuationToken;
    }

    public function getDelimiter(): ?string
    {
        return $this->Delimiter;
    }

    /**
     * @return EncodingType::URL|null
     */
    public function getEncodingType(): ?string
    {
        return $this->EncodingType;
    }

    public function getFetchOwner(): ?bool
    {
        return $this->FetchOwner;
    }

    public function getMaxKeys(): ?int
    {
        return $this->MaxKeys;
    }

    public function getPrefix(): ?string
    {
        return $this->Prefix;
    }

    /**
     * @return RequestPayer::REQUESTER|null
     */
    public function getRequestPayer(): ?string
    {
        return $this->RequestPayer;
    }

    public function getStartAfter(): ?string
    {
        return $this->StartAfter;
    }

    public function requestBody(): string
    {
        return '';
    }

    public function requestHeaders(): array
    {
        $headers = ['content-type' => 'application/xml'];
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
        if (null !== $this->MaxKeys) {
            $query['max-keys'] = $this->MaxKeys;
        }
        if (null !== $this->Prefix) {
            $query['prefix'] = $this->Prefix;
        }
        if (null !== $this->ContinuationToken) {
            $query['continuation-token'] = $this->ContinuationToken;
        }
        if (null !== $this->FetchOwner) {
            $query['fetch-owner'] = $this->FetchOwner;
        }
        if (null !== $this->StartAfter) {
            $query['start-after'] = $this->StartAfter;
        }

        return $query;
    }

    public function requestUri(): string
    {
        $uri = [];
        $uri['Bucket'] = $this->Bucket ?? '';

        return "/{$uri['Bucket']}?list-type=2";
    }

    public function setBucket(?string $value): self
    {
        $this->Bucket = $value;

        return $this;
    }

    public function setContinuationToken(?string $value): self
    {
        $this->ContinuationToken = $value;

        return $this;
    }

    public function setDelimiter(?string $value): self
    {
        $this->Delimiter = $value;

        return $this;
    }

    /**
     * @param EncodingType::URL|null $value
     */
    public function setEncodingType(?string $value): self
    {
        $this->EncodingType = $value;

        return $this;
    }

    public function setFetchOwner(?bool $value): self
    {
        $this->FetchOwner = $value;

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

    /**
     * @param RequestPayer::REQUESTER|null $value
     */
    public function setRequestPayer(?string $value): self
    {
        $this->RequestPayer = $value;

        return $this;
    }

    public function setStartAfter(?string $value): self
    {
        $this->StartAfter = $value;

        return $this;
    }

    public function validate(): void
    {
        if (null === $this->Bucket) {
            throw new InvalidArgument(sprintf('Missing parameter "Bucket" when validating the "%s". The value cannot be null.', __CLASS__));
        }

        if (null !== $this->EncodingType) {
            if (!isset(EncodingType::AVAILABLE_ENCODINGTYPE[$this->EncodingType])) {
                throw new InvalidArgument(sprintf('Invalid parameter "EncodingType" when validating the "%s". The value "%s" is not a valid "EncodingType". Available values are %s.', __CLASS__, $this->EncodingType, implode(', ', array_keys(EncodingType::AVAILABLE_ENCODINGTYPE))));
            }
        }

        if (null !== $this->RequestPayer) {
            if (!isset(RequestPayer::AVAILABLE_REQUESTPAYER[$this->RequestPayer])) {
                throw new InvalidArgument(sprintf('Invalid parameter "RequestPayer" when validating the "%s". The value "%s" is not a valid "RequestPayer". Available values are %s.', __CLASS__, $this->RequestPayer, implode(', ', array_keys(RequestPayer::AVAILABLE_REQUESTPAYER))));
            }
        }
    }
}
