<?php

namespace AsyncAws\Athena\Tests\Unit\Input;

use AsyncAws\Athena\Input\StartQueryExecutionInput;
use AsyncAws\Athena\ValueObject\AclConfiguration;
use AsyncAws\Athena\ValueObject\EncryptionConfiguration;
use AsyncAws\Athena\ValueObject\QueryExecutionContext;
use AsyncAws\Athena\ValueObject\ResultConfiguration;
use AsyncAws\Athena\ValueObject\ResultReuseByAgeConfiguration;
use AsyncAws\Athena\ValueObject\ResultReuseConfiguration;
use AsyncAws\Core\Test\TestCase;

class StartQueryExecutionInputTest extends TestCase
{
    public function testRequest(): void
    {
        // self::fail('Not implemented');

        $input = new StartQueryExecutionInput([
            'QueryString' => 'select * from my_table limit 3',
            'ClientRequestToken' => 'Unique-caSe-sensitive-0011',
            'QueryExecutionContext' => new QueryExecutionContext([
                'Database' => 'my_dbname',
                'Catalog' => 'my_catalog_name',
            ]),
            'ResultConfiguration' => new ResultConfiguration([
                'OutputLocation' => 's3://my_bucket/',
                'EncryptionConfiguration' => new EncryptionConfiguration([
                    'EncryptionOption' => 'SSE_S3',
                    'KmsKey' => 'mykey4452263',
                ]),
                'ExpectedBucketOwner' => 's3_bucket_owner',
                'AclConfiguration' => new AclConfiguration([
                    'S3AclOption' => 'BUCKET_OWNER_FULL_CONTROL',
                ]),
            ]),
            'WorkGroup' => 'iadinternational',
            'ExecutionParameters' => [],
            'ResultReuseConfiguration' => new ResultReuseConfiguration([
                'ResultReuseByAgeConfiguration' => new ResultReuseByAgeConfiguration([
                    'Enabled' => false,
                    'MaxAgeInMinutes' => 1337,
                ]),
            ]),
        ]);

        // see https://docs.aws.amazon.com/athena/latest/APIReference/API_StartQueryExecution.html
        $expected = '
POST / HTTP/1.0
Content-Type: application/x-amz-json-1.1
x-amz-target: AmazonAthena.StartQueryExecution
Accept: application/json

{
	"QueryString": "select * from my_table limit 3",
	"ClientRequestToken": "Unique-caSe-sensitive-0011",
	"QueryExecutionContext":
	{
		"Database": "my_dbname",
		"Catalog": "my_catalog_name"
	},
	"ResultConfiguration":
	{
		"OutputLocation": "s3://my_bucket/",
		"EncryptionConfiguration":
		{
			"EncryptionOption": "SSE_S3",
			"KmsKey": "mykey4452263"
		},
		"ExpectedBucketOwner": "s3_bucket_owner",
		"AclConfiguration":
		{
			"S3AclOption": "BUCKET_OWNER_FULL_CONTROL"
		}
	},
	"WorkGroup": "iadinternational",
	"ExecutionParameters": [],
	"ResultReuseConfiguration":
	{
		"ResultReuseByAgeConfiguration":
		{
			"Enabled": false,
			"MaxAgeInMinutes": 1337
		}
	}
}
';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
