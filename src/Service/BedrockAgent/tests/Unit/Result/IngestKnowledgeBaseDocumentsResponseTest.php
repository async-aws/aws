<?php

namespace AsyncAws\BedrockAgent\Tests\Unit\Result;

use AsyncAws\BedrockAgent\Enum\DocumentStatus;
use AsyncAws\BedrockAgent\Result\IngestKnowledgeBaseDocumentsResponse;
use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class IngestKnowledgeBaseDocumentsResponseTest extends TestCase
{
    public function testIngestKnowledgeBaseDocumentsResponse(): void
    {
        // see https://docs.aws.amazon.com/bedrock/latest/APIReference/API_agent_IngestKnowledgeBaseDocuments.html
        $response = new SimpleMockedResponse('{
            "documentDetails": [
                {
                    "knowledgeBaseId": "kb-1234567890abcdef",
                    "dataSourceId": "ds-abcdef1234567890",
                    "status": "INDEXED",
                    "identifier": {
                        "dataSourceType": "S3",
                        "s3": { "uri": "s3://my-bucket/documents/doc1.pdf" },
                        "custom": { "id": "custom-doc-001" }
                    },
                    "statusReason": "Document indexed successfully",
                    "updatedAt": "2024-06-01T12:00:00Z"
                }
            ]
        }');

        $client = new MockHttpClient($response);
        $result = new IngestKnowledgeBaseDocumentsResponse(
            new Response($client->request('PUT', '/knowledgebases/kb-1234567890abcdef/datasources/ds-abcdef1234567890/documents'), $client, new NullLogger())
        );

        self::assertCount(1, $result->getDocumentDetails());

        $detail = $result->getDocumentDetails()[0];
        self::assertSame('kb-1234567890abcdef', $detail->getKnowledgeBaseId());
        self::assertSame('ds-abcdef1234567890', $detail->getDataSourceId());
        self::assertSame(DocumentStatus::INDEXED, $detail->getStatus());
        self::assertSame('S3', $detail->getIdentifier()->getDataSourceType());
        self::assertSame('s3://my-bucket/documents/doc1.pdf', $detail->getIdentifier()->getS3()->getUri());
        self::assertSame('custom-doc-001', $detail->getIdentifier()->getCustom()->getId());
        self::assertSame('Document indexed successfully', $detail->getStatusReason());
        self::assertSame('2024-06-01T12:00:00Z', $detail->getUpdatedAt()->format('Y-m-d\TH:i:s\Z'));
    }
}
