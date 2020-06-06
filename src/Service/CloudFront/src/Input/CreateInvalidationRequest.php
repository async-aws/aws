<?php

namespace AsyncAws\CloudFront\Input;

use AsyncAws\CloudFront\ValueObject\InvalidationBatch;
use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

final class CreateInvalidationRequest extends Input
{
    /**
     * The distribution's id.
     *
     * @required
     *
     * @var string|null
     */
    private $DistributionId;

    /**
     * The batch information for the invalidation.
     *
     * @required
     *
     * @var InvalidationBatch|null
     */
    private $InvalidationBatch;

    /**
     * @param array{
     *   DistributionId?: string,
     *   InvalidationBatch?: \AsyncAws\CloudFront\ValueObject\InvalidationBatch|array,
     *   @region?: string,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->DistributionId = $input['DistributionId'] ?? null;
        $this->InvalidationBatch = isset($input['InvalidationBatch']) ? InvalidationBatch::create($input['InvalidationBatch']) : null;
        parent::__construct($input);
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getDistributionId(): ?string
    {
        return $this->DistributionId;
    }

    public function getInvalidationBatch(): ?InvalidationBatch
    {
        return $this->InvalidationBatch;
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
        if (null === $v = $this->DistributionId) {
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
        $this->DistributionId = $value;

        return $this;
    }

    public function setInvalidationBatch(?InvalidationBatch $value): self
    {
        $this->InvalidationBatch = $value;

        return $this;
    }

    private function requestBody(\DomNode $node, \DomDocument $document): void
    {
        if (null === $v = $this->InvalidationBatch) {
            throw new InvalidArgument(sprintf('Missing parameter "InvalidationBatch" for "%s". The value cannot be null.', __CLASS__));
        }

        $node->appendChild($child = $document->createElement('InvalidationBatch'));
        $child->setAttribute('xmlns', 'http://cloudfront.amazonaws.com/doc/2019-03-26/');
        $v->requestBody($child, $document);
    }
}
