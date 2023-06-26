<?php

namespace AsyncAws\TimestreamWrite\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;
use AsyncAws\TimestreamWrite\ValueObject\Dimension;
use AsyncAws\TimestreamWrite\ValueObject\Record;

final class WriteRecordsRequest extends Input
{
    /**
     * The name of the Timestream database.
     *
     * @required
     *
     * @var string|null
     */
    private $databaseName;

    /**
     * The name of the Timestream table.
     *
     * @required
     *
     * @var string|null
     */
    private $tableName;

    /**
     * A record that contains the common measure, dimension, time, and version attributes shared across all the records in
     * the request. The measure and dimension attributes specified will be merged with the measure and dimension attributes
     * in the records object when the data is written into Timestream. Dimensions may not overlap, or a
     * `ValidationException` will be thrown. In other words, a record must contain dimensions with unique names.
     *
     * @var Record|null
     */
    private $commonAttributes;

    /**
     * An array of records that contain the unique measure, dimension, time, and version attributes for each time-series
     * data point.
     *
     * @required
     *
     * @var Record[]|null
     */
    private $records;

    /**
     * @param array{
     *   DatabaseName?: string,
     *   TableName?: string,
     *   CommonAttributes?: Record|array,
     *   Records?: array<Record|array>,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->databaseName = $input['DatabaseName'] ?? null;
        $this->tableName = $input['TableName'] ?? null;
        $this->commonAttributes = isset($input['CommonAttributes']) ? Record::create($input['CommonAttributes']) : null;
        $this->records = isset($input['Records']) ? array_map([Record::class, 'create'], $input['Records']) : null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   DatabaseName?: string,
     *   TableName?: string,
     *   CommonAttributes?: Record|array,
     *   Records?: array<Record|array>,
     *   '@region'?: string|null,
     * }|WriteRecordsRequest $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getCommonAttributes(): ?Record
    {
        return $this->commonAttributes;
    }

    public function getDatabaseName(): ?string
    {
        return $this->databaseName;
    }

    /**
     * @return Record[]
     */
    public function getRecords(): array
    {
        return $this->records ?? [];
    }

    public function getTableName(): ?string
    {
        return $this->tableName;
    }

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = [
            'Content-Type' => 'application/x-amz-json-1.0',
            'X-Amz-Target' => 'Timestream_20181101.WriteRecords',
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

    public function setCommonAttributes(?Record $value): self
    {
        $this->commonAttributes = $value;

        return $this;
    }

    public function setDatabaseName(?string $value): self
    {
        $this->databaseName = $value;

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

    public function setTableName(?string $value): self
    {
        $this->tableName = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->databaseName) {
            throw new InvalidArgument(sprintf('Missing parameter "DatabaseName" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['DatabaseName'] = $v;
        if (null === $v = $this->tableName) {
            throw new InvalidArgument(sprintf('Missing parameter "TableName" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['TableName'] = $v;
        if (null !== $v = $this->commonAttributes) {
            $payload['CommonAttributes'] = $v->requestBody();
        }
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
