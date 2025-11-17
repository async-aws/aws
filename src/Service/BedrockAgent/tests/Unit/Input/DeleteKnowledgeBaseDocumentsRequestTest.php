<?php

namespace AsyncAws\BedrockAgent\Tests\Unit\Input;

use AsyncAws\BedrockAgent\Enum\ContentDataSourceType;
use AsyncAws\BedrockAgent\Input\DeleteKnowledgeBaseDocumentsRequest;
use AsyncAws\BedrockAgent\ValueObject\CustomDocumentIdentifier;
use AsyncAws\BedrockAgent\ValueObject\DocumentIdentifier;
use AsyncAws\BedrockAgent\ValueObject\S3Location;
use AsyncAws\Core\Test\TestCase;

class DeleteKnowledgeBaseDocumentsRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new DeleteKnowledgeBaseDocumentsRequest([
            'knowledgeBaseId' => 'kb-1234567890abcdef',
            'dataSourceId' => 'ds-abcdef1234567890',
            'clientToken' => 'token-1234',
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

        // see https://docs.aws.amazon.com/bedrock/latest/APIReference/API_agent_DeleteKnowledgeBaseDocuments.html
        $expected = '
            POST /knowledgebases/kb-1234567890abcdef/datasources/ds-abcdef1234567890/documents/deleteDocuments HTTP/1.1
            Content-Type: application/json
            Accept: application/json

            {
                "clientToken": "token-1234",
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
