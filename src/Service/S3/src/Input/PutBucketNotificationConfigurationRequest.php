<?php

namespace AsyncAws\S3\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;
use AsyncAws\S3\ValueObject\NotificationConfiguration;

final class PutBucketNotificationConfigurationRequest extends Input
{
    /**
     * The name of the bucket.
     *
     * @required
     *
     * @var string|null
     */
    private $Bucket;

    /**
     * @required
     *
     * @var NotificationConfiguration|null
     */
    private $NotificationConfiguration;

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
     *   NotificationConfiguration?: NotificationConfiguration|array,
     *   ExpectedBucketOwner?: string,
     *   @region?: string,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->Bucket = $input['Bucket'] ?? null;
        $this->NotificationConfiguration = isset($input['NotificationConfiguration']) ? NotificationConfiguration::create($input['NotificationConfiguration']) : null;
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

    public function getExpectedBucketOwner(): ?string
    {
        return $this->ExpectedBucketOwner;
    }

    public function getNotificationConfiguration(): ?NotificationConfiguration
    {
        return $this->NotificationConfiguration;
    }

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = ['content-type' => 'application/xml'];
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
        $uriString = '/' . rawurlencode($uri['Bucket']) . '?notification';

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

    public function setExpectedBucketOwner(?string $value): self
    {
        $this->ExpectedBucketOwner = $value;

        return $this;
    }

    public function setNotificationConfiguration(?NotificationConfiguration $value): self
    {
        $this->NotificationConfiguration = $value;

        return $this;
    }

    private function requestBody(\DomNode $node, \DomDocument $document): void
    {
        if (null === $v = $this->NotificationConfiguration) {
            throw new InvalidArgument(sprintf('Missing parameter "NotificationConfiguration" for "%s". The value cannot be null.', __CLASS__));
        }

        $node->appendChild($child = $document->createElement('NotificationConfiguration'));
        $child->setAttribute('xmlns', 'http://s3.amazonaws.com/doc/2006-03-01/');
        $v->requestBody($child, $document);
    }
}
