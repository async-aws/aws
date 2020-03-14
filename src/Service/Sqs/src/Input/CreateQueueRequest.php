<?php

namespace AsyncAws\Sqs\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

class CreateQueueRequest
{
    /**
     * The name of the new queue. The following limits apply to this name:.
     *
     * @required
     *
     * @var string|null
     */
    private $QueueName;

    /**
     * A map of attributes with their corresponding values.
     *
     * @var string[]
     */
    private $Attributes;

    /**
     * Add cost allocation tags to the specified Amazon SQS queue. For an overview, see Tagging Your Amazon SQS Queues in
     * the *Amazon Simple Queue Service Developer Guide*.
     *
     * @see https://docs.aws.amazon.com/AWSSimpleQueueService/latest/SQSDeveloperGuide/sqs-queue-tags.html
     *
     * @var string[]
     */
    private $tags;

    /**
     * @param array{
     *   QueueName?: string,
     *   Attributes?: string[],
     *   tags?: string[],
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->QueueName = $input['QueueName'] ?? null;
        $this->Attributes = $input['Attributes'] ?? [];
        $this->tags = $input['tags'] ?? [];
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return string[]
     */
    public function getAttributes(): array
    {
        return $this->Attributes;
    }

    public function getQueueName(): ?string
    {
        return $this->QueueName;
    }

    /**
     * @return string[]
     */
    public function gettags(): array
    {
        return $this->tags;
    }

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = ['content-type' => 'application/x-www-form-urlencoded'];

        // Prepare query
        $query = [];

        // Prepare URI
        $uriString = '/';

        // Return the Request
        return new Request('POST', $uriString, $query, $headers, StreamFactory::create($this->requestBody()));
    }

    /**
     * @param string[] $value
     */
    public function setAttributes(array $value): self
    {
        $this->Attributes = $value;

        return $this;
    }

    public function setQueueName(?string $value): self
    {
        $this->QueueName = $value;

        return $this;
    }

    /**
     * @param string[] $value
     */
    public function settags(array $value): self
    {
        $this->tags = $value;

        return $this;
    }

    public function validate(): void
    {
        if (null === $this->QueueName) {
            throw new InvalidArgument(sprintf('Missing parameter "QueueName" when validating the "%s". The value cannot be null.', __CLASS__));
        }
    }

    private function requestBody(): string
    {
        $payload = ['Action' => 'CreateQueue', 'Version' => '2012-11-05'];
        $indices = new \stdClass();
        $payload['QueueName'] = $this->QueueName;

        (static function (array $input) use (&$payload, $indices) {
            $indices->ka086d94 = 0;
            foreach ($input as $key => $value) {
                ++$indices->ka086d94;
                $payload["Attribute.{$indices->ka086d94}.Name"] = $key;
                $payload["Attribute.{$indices->ka086d94}.Value"] = $value;
            }
        })($this->Attributes);

        (static function (array $input) use (&$payload, $indices) {
            $indices->k982963c = 0;
            foreach ($input as $key => $value) {
                ++$indices->k982963c;
                $payload["Tag.{$indices->k982963c}.Key"] = $key;
                $payload["Tag.{$indices->k982963c}.Value"] = $value;
            }
        })($this->tags);

        return http_build_query($payload, '', '&', \PHP_QUERY_RFC1738);
    }
}
