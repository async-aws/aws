<?php

namespace AsyncAws\BedrockAgent\Tests\Unit\Input;

use AsyncAws\BedrockAgent\Enum\ContentDataSourceType;
use AsyncAws\BedrockAgent\Input\GetKnowledgeBaseDocumentsRequest;
use AsyncAws\BedrockAgent\ValueObject\CustomDocumentIdentifier;
use AsyncAws\BedrockAgent\ValueObject\DocumentIdentifier;
use AsyncAws\BedrockAgent\ValueObject\S3Location;
use AsyncAws\Core\Test\TestCase;

class GetKnowledgeBaseDocumentsRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new GetKnowledgeBaseDocumentsRequest([
            'knowledgeBaseId' => 'kb-1234567890abcdef',
            'dataSourceId' => 'ds-abcdef1234567890',
            'documentIdentifiers' => [new DocumentIdentifier([
                'dataSourceType' => ContentDataSourceType::S3,
                's3' => new S3Location([
                    'uri' => 's3://my-bucket/documents/',
                ]),
                'custom' => new CustomDocumentIdentifier([
                    'id' => 'custom-doc-001',
                ]),
            ])],
        ]);

        // see https://docs.aws.amazon.com/bedrock/latest/APIReference/API_agent_GetKnowledgeBaseDocuments.html
        $expected = '
            POST /knowledgebases/kb-1234567890abcdef/datasources/ds-abcdef1234567890/documents/getDocuments HTTP/1.1
            Content-Type: application/json
            Accept: application/json

            {
                "documentIdentifiers": [
                    {
                        "dataSourceType": "S3",
                        "s3": {
                            "uri": "s3://my-bucket/documents/"
                        },
                        "custom": {
                            "id": "custom-doc-001"
                        }
                    }
                ]
            }
        ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
