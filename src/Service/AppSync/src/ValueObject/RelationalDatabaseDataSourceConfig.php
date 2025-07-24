<?php

namespace AsyncAws\AppSync\ValueObject;

use AsyncAws\AppSync\Enum\RelationalDatabaseSourceType;
use AsyncAws\Core\Exception\InvalidArgument;

/**
 * Describes a relational database data source configuration.
 */
final class RelationalDatabaseDataSourceConfig
{
    /**
     * Source type for the relational database.
     *
     * - **RDS_HTTP_ENDPOINT**: The relational database source type is an Amazon Relational Database Service (Amazon RDS)
     *   HTTP endpoint.
     *
     * @var RelationalDatabaseSourceType::*|string|null
     */
    private $relationalDatabaseSourceType;

    /**
     * Amazon RDS HTTP endpoint settings.
     *
     * @var RdsHttpEndpointConfig|null
     */
    private $rdsHttpEndpointConfig;

    /**
     * @param array{
     *   relationalDatabaseSourceType?: null|RelationalDatabaseSourceType::*|string,
     *   rdsHttpEndpointConfig?: null|RdsHttpEndpointConfig|array,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->relationalDatabaseSourceType = $input['relationalDatabaseSourceType'] ?? null;
        $this->rdsHttpEndpointConfig = isset($input['rdsHttpEndpointConfig']) ? RdsHttpEndpointConfig::create($input['rdsHttpEndpointConfig']) : null;
    }

    /**
     * @param array{
     *   relationalDatabaseSourceType?: null|RelationalDatabaseSourceType::*|string,
     *   rdsHttpEndpointConfig?: null|RdsHttpEndpointConfig|array,
     * }|RelationalDatabaseDataSourceConfig $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getRdsHttpEndpointConfig(): ?RdsHttpEndpointConfig
    {
        return $this->rdsHttpEndpointConfig;
    }

    /**
     * @return RelationalDatabaseSourceType::*|string|null
     */
    public function getRelationalDatabaseSourceType(): ?string
    {
        return $this->relationalDatabaseSourceType;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->relationalDatabaseSourceType) {
            if (!RelationalDatabaseSourceType::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "relationalDatabaseSourceType" for "%s". The value "%s" is not a valid "RelationalDatabaseSourceType".', __CLASS__, $v));
            }
            $payload['relationalDatabaseSourceType'] = $v;
        }
        if (null !== $v = $this->rdsHttpEndpointConfig) {
            $payload['rdsHttpEndpointConfig'] = $v->requestBody();
        }

        return $payload;
    }
}
