<?php

namespace AsyncAws\Athena\Input;

use AsyncAws\Athena\ValueObject\EngineConfiguration;
use AsyncAws\Athena\ValueObject\QueryExecutionContext;
use AsyncAws\Athena\ValueObject\ResultConfiguration;
use AsyncAws\Athena\ValueObject\ResultReuseConfiguration;
use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

final class StartQueryExecutionInput extends Input
{
    /**
     * The SQL query statements to be executed.
     *
     * @required
     *
     * @var string|null
     */
    private $queryString;

    /**
     * A unique case-sensitive string used to ensure the request to create the query is idempotent (executes only once). If
     * another `StartQueryExecution` request is received, the same response is returned and another query is not created. An
     * error is returned if a parameter, such as `QueryString`, has changed. A call to `StartQueryExecution` that uses a
     * previous client request token returns the same `QueryExecutionId` even if the requester doesn't have permission on
     * the tables specified in `QueryString`.
     *
     * ! This token is listed as not required because Amazon Web Services SDKs (for example the Amazon Web Services SDK for
     * ! Java) auto-generate the token for users. If you are not using the Amazon Web Services SDK or the Amazon Web
     * ! Services CLI, you must provide this token or the action will fail.
     *
     * @var string|null
     */
    private $clientRequestToken;

    /**
     * The database within which the query executes.
     *
     * @var QueryExecutionContext|null
     */
    private $queryExecutionContext;

    /**
     * Specifies information about where and how to save the results of the query execution. If the query runs in a
     * workgroup, then workgroup's settings may override query settings. This affects the query results location. The
     * workgroup settings override is specified in EnforceWorkGroupConfiguration (true/false) in the WorkGroupConfiguration.
     * See WorkGroupConfiguration$EnforceWorkGroupConfiguration.
     *
     * @var ResultConfiguration|null
     */
    private $resultConfiguration;

    /**
     * The name of the workgroup in which the query is being started.
     *
     * @var string|null
     */
    private $workGroup;

    /**
     * A list of values for the parameters in a query. The values are applied sequentially to the parameters in the query in
     * the order in which the parameters occur.
     *
     * @var string[]|null
     */
    private $executionParameters;

    /**
     * Specifies the query result reuse behavior for the query.
     *
     * @var ResultReuseConfiguration|null
     */
    private $resultReuseConfiguration;

    /**
     * The engine configuration for the workgroup, which includes the minimum/maximum number of Data Processing Units (DPU)
     * that queries should use when running in provisioned capacity. If not specified, Athena uses default values (Default
     * value for min is 4 and for max is Minimum of 124 and allocated DPUs).
     *
     * To specify minimum and maximum DPU values for Capacity Reservations queries, the workgroup containing
     * `EngineConfiguration` should have the following values: The name of the `Classifications` should be
     * `athena-query-engine-properties`, with the only allowed properties as `max-dpu-count` and `min-dpu-count`.
     *
     * @var EngineConfiguration|null
     */
    private $engineConfiguration;

    /**
     * @param array{
     *   QueryString?: string,
     *   ClientRequestToken?: string|null,
     *   QueryExecutionContext?: QueryExecutionContext|array|null,
     *   ResultConfiguration?: ResultConfiguration|array|null,
     *   WorkGroup?: string|null,
     *   ExecutionParameters?: string[]|null,
     *   ResultReuseConfiguration?: ResultReuseConfiguration|array|null,
     *   EngineConfiguration?: EngineConfiguration|array|null,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->queryString = $input['QueryString'] ?? null;
        $this->clientRequestToken = $input['ClientRequestToken'] ?? null;
        $this->queryExecutionContext = isset($input['QueryExecutionContext']) ? QueryExecutionContext::create($input['QueryExecutionContext']) : null;
        $this->resultConfiguration = isset($input['ResultConfiguration']) ? ResultConfiguration::create($input['ResultConfiguration']) : null;
        $this->workGroup = $input['WorkGroup'] ?? null;
        $this->executionParameters = $input['ExecutionParameters'] ?? null;
        $this->resultReuseConfiguration = isset($input['ResultReuseConfiguration']) ? ResultReuseConfiguration::create($input['ResultReuseConfiguration']) : null;
        $this->engineConfiguration = isset($input['EngineConfiguration']) ? EngineConfiguration::create($input['EngineConfiguration']) : null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   QueryString?: string,
     *   ClientRequestToken?: string|null,
     *   QueryExecutionContext?: QueryExecutionContext|array|null,
     *   ResultConfiguration?: ResultConfiguration|array|null,
     *   WorkGroup?: string|null,
     *   ExecutionParameters?: string[]|null,
     *   ResultReuseConfiguration?: ResultReuseConfiguration|array|null,
     *   EngineConfiguration?: EngineConfiguration|array|null,
     *   '@region'?: string|null,
     * }|StartQueryExecutionInput $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getClientRequestToken(): ?string
    {
        return $this->clientRequestToken;
    }

    public function getEngineConfiguration(): ?EngineConfiguration
    {
        return $this->engineConfiguration;
    }

    /**
     * @return string[]
     */
    public function getExecutionParameters(): array
    {
        return $this->executionParameters ?? [];
    }

    public function getQueryExecutionContext(): ?QueryExecutionContext
    {
        return $this->queryExecutionContext;
    }

    public function getQueryString(): ?string
    {
        return $this->queryString;
    }

    public function getResultConfiguration(): ?ResultConfiguration
    {
        return $this->resultConfiguration;
    }

    public function getResultReuseConfiguration(): ?ResultReuseConfiguration
    {
        return $this->resultReuseConfiguration;
    }

    public function getWorkGroup(): ?string
    {
        return $this->workGroup;
    }

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = [
            'Content-Type' => 'application/x-amz-json-1.1',
            'X-Amz-Target' => 'AmazonAthena.StartQueryExecution',
            'Accept' => 'application/json',
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

    public function setClientRequestToken(?string $value): self
    {
        $this->clientRequestToken = $value;

        return $this;
    }

    public function setEngineConfiguration(?EngineConfiguration $value): self
    {
        $this->engineConfiguration = $value;

        return $this;
    }

    /**
     * @param string[] $value
     */
    public function setExecutionParameters(array $value): self
    {
        $this->executionParameters = $value;

        return $this;
    }

    public function setQueryExecutionContext(?QueryExecutionContext $value): self
    {
        $this->queryExecutionContext = $value;

        return $this;
    }

    public function setQueryString(?string $value): self
    {
        $this->queryString = $value;

        return $this;
    }

    public function setResultConfiguration(?ResultConfiguration $value): self
    {
        $this->resultConfiguration = $value;

        return $this;
    }

    public function setResultReuseConfiguration(?ResultReuseConfiguration $value): self
    {
        $this->resultReuseConfiguration = $value;

        return $this;
    }

    public function setWorkGroup(?string $value): self
    {
        $this->workGroup = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->queryString) {
            throw new InvalidArgument(\sprintf('Missing parameter "QueryString" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['QueryString'] = $v;
        if (null === $v = $this->clientRequestToken) {
            $v = uuid_create(\UUID_TYPE_RANDOM);
        }
        $payload['ClientRequestToken'] = $v;
        if (null !== $v = $this->queryExecutionContext) {
            $payload['QueryExecutionContext'] = $v->requestBody();
        }
        if (null !== $v = $this->resultConfiguration) {
            $payload['ResultConfiguration'] = $v->requestBody();
        }
        if (null !== $v = $this->workGroup) {
            $payload['WorkGroup'] = $v;
        }
        if (null !== $v = $this->executionParameters) {
            $index = -1;
            $payload['ExecutionParameters'] = [];
            foreach ($v as $listValue) {
                ++$index;
                $payload['ExecutionParameters'][$index] = $listValue;
            }
        }
        if (null !== $v = $this->resultReuseConfiguration) {
            $payload['ResultReuseConfiguration'] = $v->requestBody();
        }
        if (null !== $v = $this->engineConfiguration) {
            $payload['EngineConfiguration'] = $v->requestBody();
        }

        return $payload;
    }
}
