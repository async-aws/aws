<?php

namespace AsyncAws\Firehose\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;
use AsyncAws\Firehose\ValueObject\Record;

final class PutRecordInput extends Input
{
    /**
     * The name of the delivery stream.
     *
     * @required
     *
     * @var string|null
     */
    private $deliveryStreamName;

    /**
     * The record.
     *
     * @required
     *
     * @var Record|null
     */
    private $record;

    /**
     * @param array{
     *   DeliveryStreamName?: string,
     *   Record?: Record|array,
     *   @region?: string,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->deliveryStreamName = $input['DeliveryStreamName'] ?? null;
        $this->record = isset($input['Record']) ? Record::create($input['Record']) : null;
        parent::__construct($input);
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getDeliveryStreamName(): ?string
    {
        return $this->deliveryStreamName;
    }

    public function getRecord(): ?Record
    {
        return $this->record;
    }

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = [
            'Content-Type' => 'application/x-amz-json-1.1',
            'X-Amz-Target' => 'Firehose_20150804.PutRecord',
        ];

        // Prepare query
        $query = [];

        // Prepare URI
        $uriString = '/';

        // Prepare Body
        $bodyPayload = $this->requestBody();
        $body = empty($bodyPayload) ? '{}' : json_encode($bodyPayload, 4194304);

        // Return the Request
        return new Request('POST', $uriString, $query, $headers, StreamFactory::create($body));
    }

    public function setDeliveryStreamName(?string $value): self
    {
        $this->deliveryStreamName = $value;

        return $this;
    }

    public function setRecord(?Record $value): self
    {
        $this->record = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->deliveryStreamName) {
            throw new InvalidArgument(sprintf('Missing parameter "DeliveryStreamName" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['DeliveryStreamName'] = $v;
        if (null === $v = $this->record) {
            throw new InvalidArgument(sprintf('Missing parameter "Record" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['Record'] = $v->requestBody();

        return $payload;
    }
}
