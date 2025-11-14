<?php

namespace AsyncAws\BedrockAgent\Tests\Unit\Input;

use AsyncAws\BedrockAgent\Input\ListKnowledgeBaseDocumentsRequest;
use AsyncAws\Core\Test\TestCase;

class ListKnowledgeBaseDocumentsRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new ListKnowledgeBaseDocumentsRequest([
            'knowledgeBaseId' => 'kb-1234567890abcdef',
            'dataSourceId' => 'ds-abcdef1234567890',
            'maxResults' => 50,
            'nextToken' => 'token-1234',
        ]);

        // see https://docs.aws.amazon.com/bedrock/latest/APIReference/API_agent_ListKnowledgeBaseDocuments.html
        $expected = '
            POST /knowledgebases/kb-1234567890abcdef/datasources/ds-abcdef1234567890/documents HTTP/1.1
            Content-Type: application/json
            Accept: application/json

            {
                "maxResults": 50,
                "nextToken": "token-1234"
            }
        ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
