<?php

namespace AsyncAws\Athena\Enum;

final class ConnectionType
{
    public const BIGQUERY = 'BIGQUERY';
    public const CMDB = 'CMDB';
    public const DATALAKEGEN2 = 'DATALAKEGEN2';
    public const DB2 = 'DB2';
    public const DB2AS400 = 'DB2AS400';
    public const DOCUMENTDB = 'DOCUMENTDB';
    public const DYNAMODB = 'DYNAMODB';
    public const GOOGLECLOUDSTORAGE = 'GOOGLECLOUDSTORAGE';
    public const HBASE = 'HBASE';
    public const MYSQL = 'MYSQL';
    public const OPENSEARCH = 'OPENSEARCH';
    public const ORACLE = 'ORACLE';
    public const POSTGRESQL = 'POSTGRESQL';
    public const REDSHIFT = 'REDSHIFT';
    public const SAPHANA = 'SAPHANA';
    public const SNOWFLAKE = 'SNOWFLAKE';
    public const SQLSERVER = 'SQLSERVER';
    public const SYNAPSE = 'SYNAPSE';
    public const TIMESTREAM = 'TIMESTREAM';
    public const TPCDS = 'TPCDS';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::BIGQUERY => true,
            self::CMDB => true,
            self::DATALAKEGEN2 => true,
            self::DB2 => true,
            self::DB2AS400 => true,
            self::DOCUMENTDB => true,
            self::DYNAMODB => true,
            self::GOOGLECLOUDSTORAGE => true,
            self::HBASE => true,
            self::MYSQL => true,
            self::OPENSEARCH => true,
            self::ORACLE => true,
            self::POSTGRESQL => true,
            self::REDSHIFT => true,
            self::SAPHANA => true,
            self::SNOWFLAKE => true,
            self::SQLSERVER => true,
            self::SYNAPSE => true,
            self::TIMESTREAM => true,
            self::TPCDS => true,
        ][$value]);
    }
}
