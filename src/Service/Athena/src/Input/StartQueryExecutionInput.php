<?php

namespace AsyncAws\Athena\Input;

use AsyncAws\Athena\ValueObject\QueryExecutionContext;
use AsyncAws\Athena\ValueObject\ResultConfiguration;
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
     * another `StartQueryExecution` request is received, the same response is returned and another query is not created. If
     * a parameter has changed, for example, the `QueryString`, an error is returned.
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
     * @param array{
     *   QueryString?: string,
     *   ClientRequestToken?: string,
     *   QueryExecutionContext?: QueryExecutionContext|array,
     *   ResultConfiguration?: ResultConfiguration|array,
     *   WorkGroup?: string,
     *   @region?: string,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->queryString = $input['QueryString'] ?? null;
        $this->clientRequestToken = $input['ClientRequestToken'] ?? null;
        $this->queryExecutionContext = isset($input['QueryExecutionContext']) ? QueryExecutionContext::create($input['QueryExecutionContext']) : null;
        $this->resultConfiguration = isset($input['ResultConfiguration']) ? ResultConfiguration::create($input['ResultConfiguration']) : null;
        $this->workGroup = $input['WorkGroup'] ?? null;
        parent::__construct($input);
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getClientRequestToken(): ?string
    {
        return $this->clientRequestToken;
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

    public function setClientRequestToken(?string $value): self
    {
        $this->clientRequestToken = $value;

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

    public function setWorkGroup(?string $value): self
    {
        $this->workGroup = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->queryString) {
            throw new InvalidArgument(sprintf('Missing parameter "QueryString" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['QueryString'] = $v;
        if (null !== $v = $this->clientRequestToken) {
            $payload['ClientRequestToken'] = $v;
        }
        if (null !== $v = $this->queryExecutionContext) {
            $payload['QueryExecutionContext'] = $v->requestBody();
        }
        if (null !== $v = $this->resultConfiguration) {
            $payload['ResultConfiguration'] = $v->requestBody();
        }
        if (null !== $v = $this->workGroup) {
            $payload['WorkGroup'] = $v;
        }

        return $payload;
    }
}
