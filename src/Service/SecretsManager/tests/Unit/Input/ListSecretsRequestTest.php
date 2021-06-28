<?php

namespace AsyncAws\SecretsManager\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\SecretsManager\Enum\FilterNameStringType;
use AsyncAws\SecretsManager\Enum\SortOrderType;
use AsyncAws\SecretsManager\Input\ListSecretsRequest;
use AsyncAws\SecretsManager\ValueObject\Filter;

class ListSecretsRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new ListSecretsRequest([
            'MaxResults' => 10,
            'NextToken' => 'next',
            'Filters' => [new Filter([
                'Key' => FilterNameStringType::DESCRIPTION,
                'Values' => ['description value'],
            ])],
            'SortOrder' => SortOrderType::ASC,
        ]);

        // see https://docs.aws.amazon.com/secretsmanager/latest/apireference/API_ListSecrets.html
        $expected = '
            POST / HTTP/1.0
            Content-Type: application/x-amz-json-1.1
            x-amz-target: secretsmanager.ListSecrets

            {
                "Filters": [
                    {
                        "Key": "description",
                        "Values": [
                            "description value"
                        ]
                    }
                ],
                "MaxResults": 10,
                "NextToken": "next",
                "SortOrder": "asc"
            }
        ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
