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
     * @param array{
     *   CatalogName?: string,
     *   DatabaseName?: string,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->catalogName = $input['CatalogName'] ?? null;
        $this->databaseName = $input['DatabaseName'] ?? null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   CatalogName?: string,
     *   DatabaseName?: string,
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

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = [
            'Content-Type' => 'application/x-amz-json-1.1',
            'Accept' => 'application/json',
            'X-Amz-Target' => 'AmazonAthena.GetDatabase',
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

    private function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->catalogName) {
            throw new InvalidArgument(sprintf('Missing parameter "CatalogName" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['CatalogName'] = $v;
        if (null === $v = $this->databaseName) {
            throw new InvalidArgument(sprintf('Missing parameter "DatabaseName" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['DatabaseName'] = $v;

        return $payload;
    }
}
