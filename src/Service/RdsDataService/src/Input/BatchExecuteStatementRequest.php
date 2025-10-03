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
     * The Amazon Resource Name (ARN) of the Aurora Serverless DB cluster.
     *
     * @required
     *
     * @var string|null
     */
    private $resourceArn;

    /**
     * The ARN of the secret that enables access to the DB cluster. Enter the database user name and password for the
     * credentials in the secret.
     *
     * For information about creating the secret, see Create a database secret [^1].
     *
     * [^1]: https://docs.aws.amazon.com/secretsmanager/latest/userguide/create_database_secret.html
     *
     * @required
     *
     * @var string|null
     */
    private $secretArn;

    /**
     * The SQL statement to run. Don't include a semicolon (;) at the end of the SQL statement.
     *
     * @required
     *
     * @var string|null
     */
    private $sql;

    /**
     * The name of the database.
     *
     * @var string|null
     */
    private $database;

    /**
     * The name of the database schema.
     *
     * > Currently, the `schema` parameter isn't supported.
     *
     * @var string|null
     */
    private $schema;

    /**
     * The parameter set for the batch operation.
     *
     * The SQL statement is executed as many times as the number of parameter sets provided. To execute a SQL statement with
     * no parameters, use one of the following options:
     *
     * - Specify one or more empty parameter sets.
     * - Use the `ExecuteStatement` operation instead of the `BatchExecuteStatement` operation.
     *
     * > Array parameters are not supported.
     *
     * @var SqlParameter[][]|null
     */
    private $parameterSets;

    /**
     * The identifier of a transaction that was started by using the `BeginTransaction` operation. Specify the transaction
     * ID of the transaction that you want to include the SQL statement in.
     *
     * If the SQL statement is not part of a transaction, don't set this parameter.
     *
     * @var string|null
     */
    private $transactionId;

    /**
     * @param array{
     *   resourceArn?: string,
     *   secretArn?: string,
     *   sql?: string,
     *   database?: string|null,
     *   schema?: string|null,
     *   parameterSets?: array[]|null,
     *   transactionId?: string|null,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->resourceArn = $input['resourceArn'] ?? null;
        $this->secretArn = $input['secretArn'] ?? null;
        $this->sql = $input['sql'] ?? null;
        $this->database = $input['database'] ?? null;
        $this->schema = $input['schema'] ?? null;
        $this->parameterSets = isset($input['parameterSets']) ? array_map(static function (array $array) {
            return array_map([SqlParameter::class, 'create'], $array);
        }, $input['parameterSets']) : null;
        $this->transactionId = $input['transactionId'] ?? null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   resourceArn?: string,
     *   secretArn?: string,
     *   sql?: string,
     *   database?: string|null,
     *   schema?: string|null,
     *   parameterSets?: array[]|null,
     *   transactionId?: string|null,
     *   '@region'?: string|null,
     * }|BatchExecuteStatementRequest $input
     */
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
        $headers = [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ];

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
        if (null === $v = $this->resourceArn) {
            throw new InvalidArgument(\sprintf('Missing parameter "resourceArn" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['resourceArn'] = $v;
        if (null === $v = $this->secretArn) {
            throw new InvalidArgument(\sprintf('Missing parameter "secretArn" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['secretArn'] = $v;
        if (null === $v = $this->sql) {
            throw new InvalidArgument(\sprintf('Missing parameter "sql" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['sql'] = $v;
        if (null !== $v = $this->database) {
            $payload['database'] = $v;
        }
        if (null !== $v = $this->schema) {
            $payload['schema'] = $v;
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
        if (null !== $v = $this->transactionId) {
            $payload['transactionId'] = $v;
        }

        return $payload;
    }
}
