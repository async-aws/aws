<?php

namespace AsyncAws\Lambda\Input;

use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

final class ListEventSourceMappingsRequest extends Input
{
    /**
     * The Amazon Resource Name (ARN) of the event source.
     *
     * - **Amazon Kinesis** – The ARN of the data stream or a stream consumer.
     * - **Amazon DynamoDB Streams** – The ARN of the stream.
     * - **Amazon Simple Queue Service** – The ARN of the queue.
     * - **Amazon Managed Streaming for Apache Kafka** – The ARN of the cluster or the ARN of the VPC connection (for
     *   cross-account event source mappings [^1]).
     * - **Amazon MQ** – The ARN of the broker.
     * - **Amazon DocumentDB** – The ARN of the DocumentDB change stream.
     *
     * [^1]: https://docs.aws.amazon.com/lambda/latest/dg/with-msk.html#msk-multi-vpc
     *
     * @var string|null
     */
    private $eventSourceArn;

    /**
     * The name or ARN of the Lambda function.
     *
     * **Name formats**
     *
     * - **Function name** – `MyFunction`.
     * - **Function ARN** – `arn:aws:lambda:us-west-2:123456789012:function:MyFunction`.
     * - **Version or Alias ARN** – `arn:aws:lambda:us-west-2:123456789012:function:MyFunction:PROD`.
     * - **Partial ARN** – `123456789012:function:MyFunction`.
     *
     * The length constraint applies only to the full ARN. If you specify only the function name, it's limited to 64
     * characters in length.
     *
     * @var string|null
     */
    private $functionName;

    /**
     * A pagination token returned by a previous call.
     *
     * @var string|null
     */
    private $marker;

    /**
     * The maximum number of event source mappings to return. Note that ListEventSourceMappings returns a maximum of 100
     * items in each response, even if you set the number higher.
     *
     * @var int|null
     */
    private $maxItems;

    /**
     * @param array{
     *   EventSourceArn?: string|null,
     *   FunctionName?: string|null,
     *   Marker?: string|null,
     *   MaxItems?: int|null,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->eventSourceArn = $input['EventSourceArn'] ?? null;
        $this->functionName = $input['FunctionName'] ?? null;
        $this->marker = $input['Marker'] ?? null;
        $this->maxItems = $input['MaxItems'] ?? null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   EventSourceArn?: string|null,
     *   FunctionName?: string|null,
     *   Marker?: string|null,
     *   MaxItems?: int|null,
     *   '@region'?: string|null,
     * }|ListEventSourceMappingsRequest $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getEventSourceArn(): ?string
    {
        return $this->eventSourceArn;
    }

    public function getFunctionName(): ?string
    {
        return $this->functionName;
    }

    public function getMarker(): ?string
    {
        return $this->marker;
    }

    public function getMaxItems(): ?int
    {
        return $this->maxItems;
    }

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ];

        // Prepare query
        $query = [];
        if (null !== $this->eventSourceArn) {
            $query['EventSourceArn'] = $this->eventSourceArn;
        }
        if (null !== $this->functionName) {
            $query['FunctionName'] = $this->functionName;
        }
        if (null !== $this->marker) {
            $query['Marker'] = $this->marker;
        }
        if (null !== $this->maxItems) {
            $query['MaxItems'] = (string) $this->maxItems;
        }

        // Prepare URI
        $uriString = '/2015-03-31/event-source-mappings';

        // Prepare Body
        $body = '';

        // Return the Request
        return new Request('GET', $uriString, $query, $headers, StreamFactory::create($body));
    }

    public function setEventSourceArn(?string $value): self
    {
        $this->eventSourceArn = $value;

        return $this;
    }

    public function setFunctionName(?string $value): self
    {
        $this->functionName = $value;

        return $this;
    }

    public function setMarker(?string $value): self
    {
        $this->marker = $value;

        return $this;
    }

    public function setMaxItems(?int $value): self
    {
        $this->maxItems = $value;

        return $this;
    }
}
