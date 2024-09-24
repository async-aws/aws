<?php

namespace AsyncAws\Athena\Enum;

final class ConnectionType
{
    public const BIGQUERY = 'BIGQUERY';
    public const CLOUDERAHIVE = 'CLOUDERAHIVE';
    public const CLOUDERAIMPALA = 'CLOUDERAIMPALA';
    public const CLOUDWATCH = 'CLOUDWATCH';
    public const CLOUDWATCHMETRICS = 'CLOUDWATCHMETRICS';
    public const CMDB = 'CMDB';
    public const DATALAKEGEN2 = 'DATALAKEGEN2';
    public const DB2 = 'DB2';
    public const DB2AS400 = 'DB2AS400';
    public const DOCUMENTDB = 'DOCUMENTDB';
    public const DYNAMODB = 'DYNAMODB';
    public const GOOGLECLOUDSTORAGE = 'GOOGLECLOUDSTORAGE';
    public const HBASE = 'HBASE';
    public const HORTONWORKSHIVE = 'HORTONWORKSHIVE';
    public const MSK = 'MSK';
    public const MYSQL = 'MYSQL';
    public const NEPTUNE = 'NEPTUNE';
    public const OPENSEARCH = 'OPENSEARCH';
    public const ORACLE = 'ORACLE';
    public const POSTGRESQL = 'POSTGRESQL';
    public const REDIS = 'REDIS';
    public const REDSHIFT = 'REDSHIFT';
    public const SAPHANA = 'SAPHANA';
    public const SNOWFLAKE = 'SNOWFLAKE';
    public const SQLSERVER = 'SQLSERVER';
    public const SYNAPSE = 'SYNAPSE';
    public const TERADATA = 'TERADATA';
    public const TIMESTREAM = 'TIMESTREAM';
    public const TPCDS = 'TPCDS';
    public const VERTICA = 'VERTICA';

    public static function exists(string $value): bool
    {
        return isset([
            self::BIGQUERY => true,
            self::CLOUDERAHIVE => true,
            self::CLOUDERAIMPALA => true,
            self::CLOUDWATCH => true,
            self::CLOUDWATCHMETRICS => true,
            self::CMDB => true,
            self::DATALAKEGEN2 => true,
            self::DB2 => true,
            self::DB2AS400 => true,
            self::DOCUMENTDB => true,
            self::DYNAMODB => true,
            self::GOOGLECLOUDSTORAGE => true,
            self::HBASE => true,
            self::HORTONWORKSHIVE => true,
            self::MSK => true,
            self::MYSQL => true,
            self::NEPTUNE => true,
            self::OPENSEARCH => true,
            self::ORACLE => true,
            self::POSTGRESQL => true,
            self::REDIS => true,
            self::REDSHIFT => true,
            self::SAPHANA => true,
            self::SNOWFLAKE => true,
            self::SQLSERVER => true,
            self::SYNAPSE => true,
            self::TERADATA => true,
            self::TIMESTREAM => true,
            self::TPCDS => true,
            self::VERTICA => true,
        ][$value]);
    }
}
