<?php

namespace AsyncAws\Athena\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

final class GetDatabaseInput extends Input
{
    /**
     * The name of the data catalog that contains the database to return.
     *
     * @required
     *
     * @var string|null
     */
    private $catalogName;

    /**
     * The name of the database to return.
     *
     * @required
     *
     * @var string|null
     */
    private $databaseName;

    /**
     * The name of the workgroup for which the metadata is being fetched. Required if requesting an IAM Identity Center
     * enabled Glue Data Catalog.
     *
     * @var string|null
     */
    private $workGroup;

    /**
     * @param array{
     *   CatalogName?: string,
     *   DatabaseName?: string,
     *   WorkGroup?: string|null,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->catalogName = $input['CatalogName'] ?? null;
        $this->databaseName = $input['DatabaseName'] ?? null;
        $this->workGroup = $input['WorkGroup'] ?? null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   CatalogName?: string,
     *   DatabaseName?: string,
     *   WorkGroup?: string|null,
     *   '@region'?: string|null,
     * }|GetDatabaseInput $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getCatalogName(): ?string
    {
        return $this->catalogName;
    }

    public function getDatabaseName(): ?string
    {
        return $this->databaseName;
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
            'X-Amz-Target' => 'AmazonAthena.GetDatabase',
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

    public function setCatalogName(?string $value): self
    {
        $this->catalogName = $value;

        return $this;
    }

    public function setDatabaseName(?string $value): self
    {
        $this->databaseName = $value;

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
        if (null === $v = $this->catalogName) {
            throw new InvalidArgument(\sprintf('Missing parameter "CatalogName" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['CatalogName'] = $v;
        if (null === $v = $this->databaseName) {
            throw new InvalidArgument(\sprintf('Missing parameter "DatabaseName" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['DatabaseName'] = $v;
        if (null !== $v = $this->workGroup) {
            $payload['WorkGroup'] = $v;
        }

        return $payload;
    }
}
