<?php

namespace AsyncAws\BedrockAgent\Tests\Unit;

use AsyncAws\BedrockAgent\BedrockAgentClient;
use AsyncAws\BedrockAgent\Enum\ContentDataSourceType;
use AsyncAws\BedrockAgent\Input\DeleteKnowledgeBaseDocumentsRequest;
use AsyncAws\BedrockAgent\Input\GetKnowledgeBaseDocumentsRequest;
use AsyncAws\BedrockAgent\Input\IngestKnowledgeBaseDocumentsRequest;
use AsyncAws\BedrockAgent\Input\ListKnowledgeBaseDocumentsRequest;
use AsyncAws\BedrockAgent\Result\DeleteKnowledgeBaseDocumentsResponse;
use AsyncAws\BedrockAgent\Result\GetKnowledgeBaseDocumentsResponse;
use AsyncAws\BedrockAgent\Result\IngestKnowledgeBaseDocumentsResponse;
use AsyncAws\BedrockAgent\Result\ListKnowledgeBaseDocumentsResponse;
use AsyncAws\BedrockAgent\ValueObject\DocumentContent;
use AsyncAws\BedrockAgent\ValueObject\DocumentIdentifier;
use AsyncAws\BedrockAgent\ValueObject\KnowledgeBaseDocument;
use AsyncAws\Core\Credentials\NullProvider;
use AsyncAws\Core\Test\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;

class BedrockAgentClientTest extends TestCase
{
    public function testDeleteKnowledgeBaseDocuments(): void
    {
        $client = new BedrockAgentClient([], new NullProvider(), new MockHttpClient());

        $input = new DeleteKnowledgeBaseDocumentsRequest([
            'knowledgeBaseId' => 'kb-1234567890abcdef',
            'dataSourceId' => 'ds-abcdef1234567890',
            'documentIdentifiers' => [new DocumentIdentifier([
                'dataSourceType' => ContentDataSourceType::S3,
            ])],
        ]);
        $result = $client->deleteKnowledgeBaseDocuments($input);

        self::assertInstanceOf(DeleteKnowledgeBaseDocumentsResponse::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testGetKnowledgeBaseDocuments(): void
    {
        $client = new BedrockAgentClient([], new NullProvider(), new MockHttpClient());

        $input = new GetKnowledgeBaseDocumentsRequest([
            'knowledgeBaseId' => 'kb-1234567890abcdef',
            'dataSourceId' => 'ds-abcdef1234567890',
            'documentIdentifiers' => [new DocumentIdentifier([
                'dataSourceType' => ContentDataSourceType::S3,
            ])],
        ]);
        $result = $client->getKnowledgeBaseDocuments($input);

        self::assertInstanceOf(GetKnowledgeBaseDocumentsResponse::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testIngestKnowledgeBaseDocuments(): void
    {
        $client = new BedrockAgentClient([], new NullProvider(), new MockHttpClient());

        $input = new IngestKnowledgeBaseDocumentsRequest([
            'knowledgeBaseId' => 'kb-1234567890abcdef',
            'dataSourceId' => 'ds-abcdef1234567890',
            'documents' => [new KnowledgeBaseDocument([
                'content' => new DocumentContent([
                    'dataSourceType' => ContentDataSourceType::S3,
                ]),
            ])],
        ]);
        $result = $client->ingestKnowledgeBaseDocuments($input);

        self::assertInstanceOf(IngestKnowledgeBaseDocumentsResponse::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testListKnowledgeBaseDocuments(): void
    {
        $client = new BedrockAgentClient([], new NullProvider(), new MockHttpClient());

        $input = new ListKnowledgeBaseDocumentsRequest([
            'knowledgeBaseId' => 'kb-1234567890abcdef',
            'dataSourceId' => 'ds-abcdef1234567890',
            'documentIdentifiers' => [new DocumentIdentifier([
                'dataSourceType' => ContentDataSourceType::S3,
            ])],
        ]);
        $result = $client->listKnowledgeBaseDocuments($input);

        self::assertInstanceOf(ListKnowledgeBaseDocumentsResponse::class, $result);
        self::assertFalse($result->info()['resolved']);
    }
}
