<?php

namespace AsyncAws\Route53\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;
use AsyncAws\Route53\ValueObject\ChangeBatch;

/**
 * A complex type that contains change information for the resource record set.
 */
final class ChangeResourceRecordSetsRequest extends Input
{
    /**
     * The ID of the hosted zone that contains the resource record sets that you want to change.
     *
     * @required
     *
     * @var string|null
     */
    private $hostedZoneId;

    /**
     * A complex type that contains an optional comment and the `Changes` element.
     *
     * @required
     *
     * @var ChangeBatch|null
     */
    private $changeBatch;

    /**
     * @param array{
     *   HostedZoneId?: string,
     *   ChangeBatch?: ChangeBatch|array,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->hostedZoneId = $input['HostedZoneId'] ?? null;
        $this->changeBatch = isset($input['ChangeBatch']) ? ChangeBatch::create($input['ChangeBatch']) : null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   HostedZoneId?: string,
     *   ChangeBatch?: ChangeBatch|array,
     *   '@region'?: string|null,
     * }|ChangeResourceRecordSetsRequest $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getChangeBatch(): ?ChangeBatch
    {
        return $this->changeBatch;
    }

    public function getHostedZoneId(): ?string
    {
        return $this->hostedZoneId;
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
        if (null === $v = $this->hostedZoneId) {
            throw new InvalidArgument(\sprintf('Missing parameter "HostedZoneId" for "%s". The value cannot be null.', __CLASS__));
        }
        $v = preg_replace('#^(/hostedzone/|/change/|/delegationset/)#', '', $v);
        $uri['Id'] = $v;
        $uriString = '/2013-04-01/hostedzone/' . rawurlencode($uri['Id']) . '/rrset/';

        // Prepare Body

        $document = new \DOMDocument('1.0', 'UTF-8');
        $document->formatOutput = false;
        $document->appendChild($child = $document->createElement('ChangeResourceRecordSetsRequest'));
        $child->setAttribute('xmlns', 'https://route53.amazonaws.com/doc/2013-04-01/');
        $this->requestBody($child, $document);
        $body = $document->hasChildNodes() ? $document->saveXML() : '';

        // Return the Request
        return new Request('POST', $uriString, $query, $headers, StreamFactory::create($body));
    }

    public function setChangeBatch(?ChangeBatch $value): self
    {
        $this->changeBatch = $value;

        return $this;
    }

    public function setHostedZoneId(?string $value): self
    {
        $this->hostedZoneId = $value;

        return $this;
    }

    private function requestBody(\DOMNode $node, \DOMDocument $document): void
    {
        if (null === $v = $this->changeBatch) {
            throw new InvalidArgument(\sprintf('Missing parameter "ChangeBatch" for "%s". The value cannot be null.', __CLASS__));
        }

        $node->appendChild($child = $document->createElement('ChangeBatch'));

        $v->requestBody($child, $document);
    }
}
