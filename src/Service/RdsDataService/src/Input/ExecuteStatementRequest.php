<?php

namespace AsyncAws\RdsDataService\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;
use AsyncAws\RdsDataService\Enum\RecordsFormatType;
use AsyncAws\RdsDataService\ValueObject\Field;
use AsyncAws\RdsDataService\ValueObject\ResultSetOptions;
use AsyncAws\RdsDataService\ValueObject\SqlParameter;

/**
 * The request parameters represent the input of a request to run a SQL statement against a database.
 */
final class ExecuteStatementRequest extends Input
{
    /**
     * A value that indicates whether to continue running the statement after the call times out. By default, the statement
     * stops running when the call times out.
     *
     * @var bool|null
     */
    private $continueAfterTimeout;

    /**
     * The name of the database.
     *
     * @var string|null
     */
    private $database;

    /**
     * A value that indicates whether to format the result set as a single JSON string. This parameter only applies to
     * `SELECT` statements and is ignored for other types of statements. Allowed values are `NONE` and `JSON`. The default
     * value is `NONE`. The result is returned in the `formattedRecords` field.
     *
     * @var RecordsFormatType::*|null
     */
    private $formatRecordsAs;

    /**
     * A value that indicates whether to include metadata in the results.
     *
     * @var bool|null
     */
    private $includeResultMetadata;

    /**
     * The parameters for the SQL statement.
     *
     * @var SqlParameter[]|null
     */
    private $parameters;

    /**
     * The Amazon Resource Name (ARN) of the Aurora Serverless DB cluster.
     *
     * @required
     *
     * @var string|null
     */
    private $resourceArn;

    /**
     * Options that control how the result set is returned.
     *
     * @var ResultSetOptions|null
     */
    private $resultSetOptions;

    /**
     * The name of the database schema.
     *
     * @var string|null
     */
    private $schema;

    /**
     * The ARN of the secret that enables access to the DB cluster. Enter the database user name and password for the
     * credentials in the secret.
     *
     * @required
     *
     * @var string|null
     */
    private $secretArn;

    /**
     * The SQL statement to run.
     *
     * @required
     *
     * @var string|null
     */
    private $sql;

    /**
     * The identifier of a transaction that was started by using the `BeginTransaction` operation. Specify the transaction
     * ID of the transaction that you want to include the SQL statement in.
     *
     * @var string|null
     */
    private $transactionId;

    /**
     * @param array{
     *   continueAfterTimeout?: bool,
     *   database?: string,
     *   formatRecordsAs?: RecordsFormatType::*,
     *   includeResultMetadata?: bool,
     *   parameters?: SqlParameter[],
     *   resourceArn?: string,
     *   resultSetOptions?: ResultSetOptions|array,
     *   schema?: string,
     *   secretArn?: string,
     *   sql?: string,
     *   transactionId?: string,
     *   @region?: string,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->continueAfterTimeout = $input['continueAfterTimeout'] ?? null;
        $this->database = $input['database'] ?? null;
        $this->formatRecordsAs = $input['formatRecordsAs'] ?? null;
        $this->includeResultMetadata = $input['includeResultMetadata'] ?? null;
        $this->parameters = isset($input['parameters']) ? array_map([SqlParameter::class, 'create'], $input['parameters']) : null;
        $this->resourceArn = $input['resourceArn'] ?? null;
        $this->resultSetOptions = isset($input['resultSetOptions']) ? ResultSetOptions::create($input['resultSetOptions']) : null;
        $this->schema = $input['schema'] ?? null;
        $this->secretArn = $input['secretArn'] ?? null;
        $this->sql = $input['sql'] ?? null;
        $this->transactionId = $input['transactionId'] ?? null;
        parent::__construct($input);
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getContinueAfterTimeout(): ?bool
    {
        return $this->continueAfterTimeout;
    }

    public function getDatabase(): ?string
    {
        return $this->database;
    }

    /**
     * @return RecordsFormatType::*|null
     */
    public function getFormatRecordsAs(): ?string
    {
        return $this->formatRecordsAs;
    }

    public function getIncludeResultMetadata(): ?bool
    {
        return $this->includeResultMetadata;
    }

    /**
     * @return SqlParameter[]
     */
    public function getParameters(): array
    {
        return $this->parameters ?? [];
    }

    public function getResourceArn(): ?string
    {
        return $this->resourceArn;
    }

    public function getResultSetOptions(): ?ResultSetOptions
    {
        return $this->resultSetOptions;
    }

    public function getSchema(): ?string
    {
        return $this->schema;
    }

    public function getSecretArn(): ?string
    {
        return $this->secretArn;
    }

    public function getSql(): ?string
    {
        return $this->sql;
    }

    public function getTransactionId(): ?string
    {
        return $this->transactionId;
    }

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = ['content-type' => 'application/json'];

        // Prepare query
        $query = [];

        // Prepare URI
        $uriString = '/Execute';

        // Prepare Body
        $bodyPayload = $this->requestBody();
        $body = empty($bodyPayload) ? '{}' : json_encode($bodyPayload, 4194304);

        // Return the Request
        return new Request('POST', $uriString, $query, $headers, StreamFactory::create($body));
    }

    public function setContinueAfterTimeout(?bool $value): self
    {
        $this->continueAfterTimeout = $value;

        return $this;
    }

    public function setDatabase(?string $value): self
    {
        $this->database = $value;

        return $this;
    }

    /**
     * @param RecordsFormatType::*|null $value
     */
    public function setFormatRecordsAs(?string $value): self
    {
        $this->formatRecordsAs = $value;

        return $this;
    }

    public function setIncludeResultMetadata(?bool $value): self
    {
        $this->includeResultMetadata = $value;

        return $this;
    }

    /**
     * @param SqlParameter[] $value
     */
    public function setParameters(array $value): self
    {
        $this->parameters = $value;

        return $this;
    }

    public function setResourceArn(?string $value): self
    {
        $this->resourceArn = $value;

        return $this;
    }

    public function setResultSetOptions(?ResultSetOptions $value): self
    {
        $this->resultSetOptions = $value;

        return $this;
    }

    public function setSchema(?string $value): self
    {
        $this->schema = $value;

        return $this;
    }

    public function setSecretArn(?string $value): self
    {
        $this->secretArn = $value;

        return $this;
    }

    public function setSql(?string $value): self
    {
        $this->sql = $value;

        return $this;
    }

    public function setTransactionId(?string $value): self
    {
        $this->transactionId = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->continueAfterTimeout) {
            $payload['continueAfterTimeout'] = (bool) $v;
        }
        if (null !== $v = $this->database) {
            $payload['database'] = $v;
        }
        if (null !== $v = $this->formatRecordsAs) {
            if (!RecordsFormatType::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "formatRecordsAs" for "%s". The value "%s" is not a valid "RecordsFormatType".', __CLASS__, $v));
            }
            $payload['formatRecordsAs'] = $v;
        }
        if (null !== $v = $this->includeResultMetadata) {
            $payload['includeResultMetadata'] = (bool) $v;
        }
        if (null !== $v = $this->parameters) {
            $index = -1;
            $payload['parameters'] = [];
            foreach ($v as $listValue) {
                ++$index;
                $payload['parameters'][$index] = $listValue->requestBody();
            }
        }
        if (null === $v = $this->resourceArn) {
            throw new InvalidArgument(sprintf('Missing parameter "resourceArn" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['resourceArn'] = $v;
        if (null !== $v = $this->resultSetOptions) {
            $payload['resultSetOptions'] = $v->requestBody();
        }
        if (null !== $v = $this->schema) {
            $payload['schema'] = $v;
        }
        if (null === $v = $this->secretArn) {
            throw new InvalidArgument(sprintf('Missing parameter "secretArn" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['secretArn'] = $v;
        if (null === $v = $this->sql) {
            throw new InvalidArgument(sprintf('Missing parameter "sql" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['sql'] = $v;
        if (null !== $v = $this->transactionId) {
            $payload['transactionId'] = $v;
        }

        return $payload;
    }
}
