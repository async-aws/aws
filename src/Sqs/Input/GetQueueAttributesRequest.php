<?php

namespace AsyncAws\Sqs\Input;

use AsyncAws\Core\Exception\InvalidArgument;

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
     * @var string[]
     */
    private $AttributeNames;

    /**
     * @param array{
     *   QueueUrl?: string,
     *   AttributeNames?: string[],
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
        foreach (['QueueUrl'] as $name) {
            if (null === $this->$name) {
                throw new InvalidArgument(sprintf('Missing parameter "%s" when validating the "%s". The value cannot be null.', $name, __CLASS__));
            }
        }
    }
}
