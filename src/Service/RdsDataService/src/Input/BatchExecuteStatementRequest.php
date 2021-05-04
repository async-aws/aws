<?php

namespace AsyncAws\RdsDataService\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;
use AsyncAws\RdsDataService\ValueObject\SqlParameter;

/**
 * The request parameters represent the input of a SQL statement over an array of data.
 */
final class BatchExecuteStatementRequest extends Input
{
    /**
     * The name of the database.
     *
     * @var string|null
     */
    private $database;

    /**
     * The parameter set for the batch operation.
     *
     * @var SqlParameter[][]|null
     */
    private $parameterSets;

    /**
     * The Amazon Resource Name (ARN) of the Aurora Serverless DB cluster.
     *
     * @required
     *
     * @var string|null
     */
    private $resourceArn;

    /**
     * The name of the database schema.
     *
     * @var string|null
     */
    private $schema;

    /**
     * The name or ARN of the secret that enables access to the DB cluster.
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
     *   database?: string,
     *   parameterSets?: array[],
     *   resourceArn?: string,
     *   schema?: string,
     *   secretArn?: string,
     *   sql?: string,
     *   transactionId?: string,
     *   @region?: string,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->database = $input['database'] ?? null;
        $this->parameterSets = isset($input['parameterSets']) ? array_map(static function (array $array) {
            return array_map([SqlParameter::class, 'create'], $array);
        }, $input['parameterSets']) : null;
        $this->resourceArn = $input['resourceArn'] ?? null;
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

    public function getDatabase(): ?string
    {
        return $this->database;
    }

    /**
     * @return SqlParameter[][]
     */
    public function getParameterSets(): array
    {
        return $this->parameterSets ?? [];
    }

    public function getResourceArn(): ?string
    {
        return $this->resourceArn;
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
        $uriString = '/BatchExecute';

        // Prepare Body
        $bodyPayload = $this->requestBody();
        $body = empty($bodyPayload) ? '{}' : json_encode($bodyPayload, 4194304);

        // Return the Request
        return new Request('POST', $uriString, $query, $headers, StreamFactory::create($body));
    }

    public function setDatabase(?string $value): self
    {
        $this->database = $value;

        return $this;
    }

    /**
     * @param SqlParameter[][] $value
     */
    public function setParameterSets(array $value): self
    {
        $this->parameterSets = $value;

        return $this;
    }

    public function setResourceArn(?string $value): self
    {
        $this->resourceArn = $value;

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
        if (null !== $v = $this->database) {
            $payload['database'] = $v;
        }
        if (null !== $v = $this->parameterSets) {
            $index = -1;
            $payload['parameterSets'] = [];
            foreach ($v as $listValue) {
                ++$index;

                $index1 = -1;
                $payload['parameterSets'][$index] = [];
                foreach ($listValue as $listValue1) {
                    ++$index1;
                    $payload['parameterSets'][$index][$index1] = $listValue1->requestBody();
                }
            }
        }
        if (null === $v = $this->resourceArn) {
            throw new InvalidArgument(sprintf('Missing parameter "resourceArn" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['resourceArn'] = $v;
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
