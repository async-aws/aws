<?php

namespace AsyncAws\Firehose\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;
use AsyncAws\Firehose\ValueObject\Record;

final class PutRecordBatchInput extends Input
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
     * One or more records.
     *
     * @required
     *
     * @var Record[]|null
     */
    private $records;

    /**
     * @param array{
     *   DeliveryStreamName?: string,
     *   Records?: Record[],
     *   @region?: string,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->deliveryStreamName = $input['DeliveryStreamName'] ?? null;
        $this->records = isset($input['Records']) ? array_map([Record::class, 'create'], $input['Records']) : null;
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

    /**
     * @return Record[]
     */
    public function getRecords(): array
    {
        return $this->records ?? [];
    }

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = [
            'Content-Type' => 'application/x-amz-json-1.1',
            'X-Amz-Target' => 'Firehose_20150804.PutRecordBatch',
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

    /**
     * @param Record[] $value
     */
    public function setRecords(array $value): self
    {
        $this->records = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->deliveryStreamName) {
            throw new InvalidArgument(sprintf('Missing parameter "DeliveryStreamName" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['DeliveryStreamName'] = $v;
        if (null === $v = $this->records) {
            throw new InvalidArgument(sprintf('Missing parameter "Records" for "%s". The value cannot be null.', __CLASS__));
        }

        $index = -1;
        $payload['Records'] = [];
        foreach ($v as $listValue) {
            ++$index;
            $payload['Records'][$index] = $listValue->requestBody();
        }

        return $payload;
    }
}
