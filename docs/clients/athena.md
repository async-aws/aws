---
layout: client
category: clients
name: Athena
package: async-aws/athena
---

## Usage

### List Databases

```php
use AsyncAws\Athena\AthenaClient;
use AsyncAws\Athena\Input\ListDatabasesInput;

$athena = new AthenaClient();

$result = $athena->listDatabases(new ListDatabasesInput([
    'CatalogName' => 'my_catalog'
]));

foreach ($result->getDatabaseList() as $database) {
    echo 'Database name : ' . $database->getName();
    echo 'Database description : ' . $database->getDescription();
    echo 'Database parameter : '.PHP_EOL;
    print_r($database->getParameters());
}

```
more information [listDatabases](https://docs.aws.amazon.com/athena/latest/APIReference/API_ListDatabases.html)

### Query to Amazon Athena

```php
use AsyncAws\Athena\AthenaClient;
use AsyncAws\Athena\Input\StartQueryExecutionInput;
use AsyncAws\Athena\Input\DescribeTableInput;
use AsyncAws\Athena\ValueObject\QueryExecutionContext;
use AsyncAws\Athena\ValueObject\ResultConfiguration;
use AsyncAws\Athena\ValueObject\EncryptionConfiguration;
use AsyncAws\Athena\ValueObject\AclConfiguration;
use AsyncAws\Athena\ValueObject\ResultReuseByAgeConfiguration;
use AsyncAws\Athena\Input\GetQueryExecutionInput;
use AsyncAws\Athena\Input\GetQueryResultsInput;
use AsyncAws\Athena\ValueObject\Row;
use AsyncAws\Athena\ValueObject\Datum;
use AsyncAws\Athena\Enum\QueryExecutionState;

$athena = new AthenaClient();

// Submits a sample query to Amazon Athena and returns the execution ID of the query.
$startQueryResult = $athena->startQueryExecution(new StartQueryExecutionInput([
        'QueryString' => 'select * from product limit 30',
        'QueryExecutionContext' => new QueryExecutionContext([
             'Database' => 'production_db', // REQUIRED
        ]),
        'ResultConfiguration' => new ResultConfiguration([
            'OutputLocation' => 's3://output_bucket_Location', // REQUIRED
            'EncryptionConfiguration' => new EncryptionConfiguration([
                'EncryptionOption' => 'SSE_S3', // REQUIRED SSE_S3|SSE_KMS|CSE_KMS
            ])
        ]),
]));


// Wait for an Amazon Athena query to complete, fail or to be cancelled.
$isQueryStillRunning = true;
while ($isQueryStillRunning) {
    $queryExecutionResult = $athena->getQueryExecution( new GetQueryExecutionInput([
        'QueryExecutionId' => $startQueryResult->getQueryExecutionId(), // REQUIRED
    ]));
    $queryState=$queryExecutionResult->getQueryExecution()->getStatus()->getState();
    if($queryState === QueryExecutionState::FAILED) {
        throw new \Exception(
        'Athena query failed to run with error message: '.$queryExecutionResult->getQueryExecution()->getStatus()->getStateChangeReason()
        )
    } elseif ($queryState === QueryExecutionState::CANCELLED) {
        throw  new \Exception('Athena query was cancelled.')
    } elseif ($queryState === QueryExecutionState::SUCCEEDED) {
        $isQueryStillRunning = false;
    }
    echo 'The current status is: : ' . $queryState;
}


// retrieves the results of a query
$results = $athena->getQueryResults(new GetQueryResultsInput([
    'QueryExecutionId' => $startQueryResult->getQueryExecutionId(),
    'MaxResults' => 10000
]));

/** @var Row $row */
foreach ($results => $row) {
    if ($index === 0) {
        $columnLabels = array_column($row->getData(), 'VarCharValue'); // $row->getData() return [ 'VarCharValue' => value]
    }
    $columnValues[] = array_column($row->getData(), 'VarCharValue');
}

// retrieves the results column structure details
$columnsDetail = $result->getResultSet()->getResultSetMetadata()->getColumnInfo();

print_r($columnsDetail);
```
