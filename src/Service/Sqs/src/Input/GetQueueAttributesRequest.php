<?php

namespace AsyncAws\Sqs\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Sqs\Enum\QueueAttributeName;

class GetQueueAttributesRequest
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

    public function requestBody(): string
    {
        $payload = ['Action' => 'GetQueueAttributes', 'Version' => '2012-11-05'];
        $indices = new \stdClass();
        $payload['QueueUrl'] = $this->QueueUrl;

        (static function (array $input) use (&$payload, $indices) {
            $indices->kbedee52 = 0;
            foreach ($input as $value) {
                ++$indices->kbedee52;
                $payload["AttributeName.{$indices->kbedee52}"] = $value;
            }
        })($this->AttributeNames);

        return http_build_query($payload, '', '&', \PHP_QUERY_RFC1738);
    }

    public function requestHeaders(): array
    {
        $headers = ['content-type' => 'application/x-www-form-urlencoded'];

        return $headers;
    }

    public function requestQuery(): array
    {
        $query = [];

        return $query;
    }

    public function requestUri(): string
    {
        return '/';
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

    public function validate(): void
    {
        if (null === $this->QueueUrl) {
            throw new InvalidArgument(sprintf('Missing parameter "QueueUrl" when validating the "%s". The value cannot be null.', __CLASS__));
        }

        foreach ($this->AttributeNames as $item) {
            if (!QueueAttributeName::exists($item)) {
                throw new InvalidArgument(sprintf('Invalid parameter "AttributeNames" when validating the "%s". The value "%s" is not a valid "QueueAttributeName".', __CLASS__, $item));
            }
        }
    }
}
