<?php

namespace AsyncAws\Sqs\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;
use AsyncAws\Sqs\Enum\QueueAttributeName;

class GetQueueAttributesRequest implements Input
{
    /**
     * The URL of the Amazon SQS queue whose attribute information is retrieved.
     *
     * @required
     *
     * @var string|null
     */
    private $QueueUrl;

    /**
     * A list of attributes for which to retrieve information.
     *
     * @var list<QueueAttributeName::*>
     */
    private $AttributeNames;

    /**
     * @param array{
     *   QueueUrl?: string,
     *   AttributeNames?: list<\AsyncAws\Sqs\Enum\QueueAttributeName::*>,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->QueueUrl = $input['QueueUrl'] ?? null;
        $this->AttributeNames = $input['AttributeNames'] ?? [];
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return list<QueueAttributeName::*>
     */
    public function getAttributeNames(): array
    {
        return $this->AttributeNames;
    }

    public function getQueueUrl(): ?string
    {
        return $this->QueueUrl;
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

        // Prepare Body
        $body = http_build_query(['Action' => 'GetQueueAttributes', 'Version' => '2012-11-05'] + $this->requestBody(), '', '&', \PHP_QUERY_RFC1738);

        // Return the Request
        return new Request('POST', $uriString, $query, $headers, StreamFactory::create($body));
    }

    /**
     * @param list<QueueAttributeName::*> $value
     */
    public function setAttributeNames(array $value): self
    {
        $this->AttributeNames = $value;

        return $this;
    }

    public function setQueueUrl(?string $value): self
    {
        $this->QueueUrl = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->QueueUrl) {
            throw new InvalidArgument(sprintf('Missing parameter "QueueUrl" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['QueueUrl'] = $v;

        $index = 0;
        foreach ($this->AttributeNames as $mapValue) {
            ++$index;
            if (!QueueAttributeName::exists($mapValue)) {
                throw new InvalidArgument(sprintf('Invalid parameter "AttributeName" for "%s". The value "%s" is not a valid "QueueAttributeName".', __CLASS__, $mapValue));
            }
            $payload["AttributeName.$index"] = $mapValue;
        }

        return $payload;
    }
}
