<?php

namespace AsyncAws\S3\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;
use AsyncAws\S3\ValueObject\CORSConfiguration;

final class PutBucketCorsRequest extends Input
{
    /**
     * Specifies the bucket impacted by the `cors`configuration.
     *
     * @required
     *
     * @var string|null
     */
    private $Bucket;

    /**
     * Describes the cross-origin access configuration for objects in an Amazon S3 bucket. For more information, see
     * Enabling Cross-Origin Resource Sharing in the *Amazon Simple Storage Service Developer Guide*.
     *
     * @see https://docs.aws.amazon.com/AmazonS3/latest/dev/cors.html
     * @required
     *
     * @var CORSConfiguration|null
     */
    private $CORSConfiguration;

    /**
     * The base64-encoded 128-bit MD5 digest of the data. This header must be used as a message integrity check to verify
     * that the request body was not corrupted in transit. For more information, go to RFC 1864.
     *
     * @see http://www.ietf.org/rfc/rfc1864.txt
     *
     * @var string|null
     */
    private $ContentMD5;

    /**
     * The account id of the expected bucket owner. If the bucket is owned by a different account, the request will fail
     * with an HTTP `403 (Access Denied)` error.
     *
     * @var string|null
     */
    private $ExpectedBucketOwner;

    /**
     * @param array{
     *   Bucket?: string,
     *   CORSConfiguration?: CORSConfiguration|array,
     *   ContentMD5?: string,
     *   ExpectedBucketOwner?: string,
     *   @region?: string,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->Bucket = $input['Bucket'] ?? null;
        $this->CORSConfiguration = isset($input['CORSConfiguration']) ? CORSConfiguration::create($input['CORSConfiguration']) : null;
        $this->ContentMD5 = $input['ContentMD5'] ?? null;
        $this->ExpectedBucketOwner = $input['ExpectedBucketOwner'] ?? null;
        parent::__construct($input);
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getBucket(): ?string
    {
        return $this->Bucket;
    }

    public function getCORSConfiguration(): ?CORSConfiguration
    {
        return $this->CORSConfiguration;
    }

    public function getContentMD5(): ?string
    {
        return $this->ContentMD5;
    }

    public function getExpectedBucketOwner(): ?string
    {
        return $this->ExpectedBucketOwner;
    }

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = ['content-type' => 'application/xml'];
        if (null !== $this->ContentMD5) {
            $headers['Content-MD5'] = $this->ContentMD5;
        }
        if (null !== $this->ExpectedBucketOwner) {
            $headers['x-amz-expected-bucket-owner'] = $this->ExpectedBucketOwner;
        }

        // Prepare query
        $query = [];

        // Prepare URI
        $uri = [];
        if (null === $v = $this->Bucket) {
            throw new InvalidArgument(sprintf('Missing parameter "Bucket" for "%s". The value cannot be null.', __CLASS__));
        }
        $uri['Bucket'] = $v;
        $uriString = '/' . rawurlencode($uri['Bucket']) . '?cors';

        // Prepare Body

        $document = new \DOMDocument('1.0', 'UTF-8');
        $document->formatOutput = false;
        $this->requestBody($document, $document);
        $body = $document->hasChildNodes() ? $document->saveXML() : '';

        // Return the Request
        return new Request('PUT', $uriString, $query, $headers, StreamFactory::create($body));
    }

    public function setBucket(?string $value): self
    {
        $this->Bucket = $value;

        return $this;
    }

    public function setCORSConfiguration(?CORSConfiguration $value): self
    {
        $this->CORSConfiguration = $value;

        return $this;
    }

    public function setContentMD5(?string $value): self
    {
        $this->ContentMD5 = $value;

        return $this;
    }

    public function setExpectedBucketOwner(?string $value): self
    {
        $this->ExpectedBucketOwner = $value;

        return $this;
    }

    private function requestBody(\DomNode $node, \DomDocument $document): void
    {
        if (null === $v = $this->CORSConfiguration) {
            throw new InvalidArgument(sprintf('Missing parameter "CORSConfiguration" for "%s". The value cannot be null.', __CLASS__));
        }

        $node->appendChild($child = $document->createElement('CORSConfiguration'));
        $child->setAttribute('xmlns', 'http://s3.amazonaws.com/doc/2006-03-01/');
        $v->requestBody($child, $document);
    }
}
