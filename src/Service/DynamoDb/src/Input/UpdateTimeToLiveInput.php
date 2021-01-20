<?php

namespace AsyncAws\DynamoDb\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;
use AsyncAws\DynamoDb\ValueObject\TimeToLiveSpecification;

/**
 * Represents the input of an `UpdateTimeToLive` operation.
 */
final class UpdateTimeToLiveInput extends Input
{
    /**
     * The name of the table to be configured.
     *
     * @required
     *
     * @var string|null
     */
    private $tableName;

    /**
     * Represents the settings used to enable or disable Time to Live for the specified table.
     *
     * @required
     *
     * @var TimeToLiveSpecification|null
     */
    private $timeToLiveSpecification;

    /**
     * @param array{
     *   TableName?: string,
     *   TimeToLiveSpecification?: TimeToLiveSpecification|array,
     *   @region?: string,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->tableName = $input['TableName'] ?? null;
        $this->timeToLiveSpecification = isset($input['TimeToLiveSpecification']) ? TimeToLiveSpecification::create($input['TimeToLiveSpecification']) : null;
        parent::__construct($input);
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getTableName(): ?string
    {
        return $this->tableName;
    }

    public function getTimeToLiveSpecification(): ?TimeToLiveSpecification
    {
        return $this->timeToLiveSpecification;
    }

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = [
            'Content-Type' => 'application/x-amz-json-1.0',
            'X-Amz-Target' => 'DynamoDB_20120810.UpdateTimeToLive',
        ];

        // Prepare query
        $query = [];

        // Prepare URI
        $uriString = '/';

        // Prepare Body
        $bodyPayload = $this->requestBody();
        $body = empty($bodyPayload) ? '{}' : json_encode($bodyPayload);

        // Return the Request
        return new Request('POST', $uriString, $query, $headers, StreamFactory::create($body));
    }

    public function setTableName(?string $value): self
    {
        $this->tableName = $value;

        return $this;
    }

    public function setTimeToLiveSpecification(?TimeToLiveSpecification $value): self
    {
        $this->timeToLiveSpecification = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->tableName) {
            throw new InvalidArgument(sprintf('Missing parameter "TableName" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['TableName'] = $v;
        if (null === $v = $this->timeToLiveSpecification) {
            throw new InvalidArgument(sprintf('Missing parameter "TimeToLiveSpecification" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['TimeToLiveSpecification'] = $v->requestBody();

        return $payload;
    }
}
