<?php

namespace AsyncAws\CloudFront\Input;

use AsyncAws\CloudFront\ValueObject\InvalidationBatch;
use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

/**
 * The request to create an invalidation.
 */
final class CreateInvalidationRequest extends Input
{
    /**
     * The distribution's id.
     *
     * @required
     *
     * @var string|null
     */
    private $distributionId;

    /**
     * The batch information for the invalidation.
     *
     * @required
     *
     * @var InvalidationBatch|null
     */
    private $invalidationBatch;

    /**
     * @param array{
     *   DistributionId?: string,
     *   InvalidationBatch?: InvalidationBatch|array,
     *   @region?: string,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->distributionId = $input['DistributionId'] ?? null;
        $this->invalidationBatch = isset($input['InvalidationBatch']) ? InvalidationBatch::create($input['InvalidationBatch']) : null;
        parent::__construct($input);
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getDistributionId(): ?string
    {
        return $this->distributionId;
    }

    public function getInvalidationBatch(): ?InvalidationBatch
    {
        return $this->invalidationBatch;
    }

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = ['content-type' => 'application/xml'];

        // Prepare query
        $query = [];

        // Prepare URI
        $uri = [];
        if (null === $v = $this->distributionId) {
            throw new InvalidArgument(sprintf('Missing parameter "DistributionId" for "%s". The value cannot be null.', __CLASS__));
        }
        $uri['DistributionId'] = $v;
        $uriString = '/2019-03-26/distribution/' . rawurlencode($uri['DistributionId']) . '/invalidation';

        // Prepare Body

        $document = new \DOMDocument('1.0', 'UTF-8');
        $document->formatOutput = false;
        $this->requestBody($document, $document);
        $body = $document->hasChildNodes() ? $document->saveXML() : '';

        // Return the Request
        return new Request('POST', $uriString, $query, $headers, StreamFactory::create($body));
    }

    public function setDistributionId(?string $value): self
    {
        $this->distributionId = $value;

        return $this;
    }

    public function setInvalidationBatch(?InvalidationBatch $value): self
    {
        $this->invalidationBatch = $value;

        return $this;
    }

    private function requestBody(\DOMNode $node, \DOMDocument $document): void
    {
        if (null === $v = $this->invalidationBatch) {
            throw new InvalidArgument(sprintf('Missing parameter "InvalidationBatch" for "%s". The value cannot be null.', __CLASS__));
        }

        $node->appendChild($child = $document->createElement('InvalidationBatch'));
        $child->setAttribute('xmlns', 'http://cloudfront.amazonaws.com/doc/2019-03-26/');
        $v->requestBody($child, $document);
    }
}
