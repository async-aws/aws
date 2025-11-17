<?php

namespace AsyncAws\BedrockAgent\Tests\Integration;

use AsyncAws\BedrockAgent\BedrockAgentClient;
use AsyncAws\BedrockAgent\Input\DeleteKnowledgeBaseDocumentsRequest;
use AsyncAws\BedrockAgent\Input\GetKnowledgeBaseDocumentsRequest;
use AsyncAws\BedrockAgent\Input\IngestKnowledgeBaseDocumentsRequest;
use AsyncAws\BedrockAgent\Input\ListKnowledgeBaseDocumentsRequest;
use AsyncAws\BedrockAgent\ValueObject\ByteContentDoc;
use AsyncAws\BedrockAgent\ValueObject\CustomContent;
use AsyncAws\BedrockAgent\ValueObject\CustomDocumentIdentifier;
use AsyncAws\BedrockAgent\ValueObject\CustomS3Location;
use AsyncAws\BedrockAgent\ValueObject\DocumentContent;
use AsyncAws\BedrockAgent\ValueObject\DocumentIdentifier;
use AsyncAws\BedrockAgent\ValueObject\DocumentMetadata;
use AsyncAws\BedrockAgent\ValueObject\InlineContent;
use AsyncAws\BedrockAgent\ValueObject\KnowledgeBaseDocument;
use AsyncAws\BedrockAgent\ValueObject\MetadataAttribute;
use AsyncAws\BedrockAgent\ValueObject\MetadataAttributeValue;
use AsyncAws\BedrockAgent\ValueObject\S3Content;
use AsyncAws\BedrockAgent\ValueObject\S3Location;
use AsyncAws\BedrockAgent\ValueObject\TextContentDoc;
use AsyncAws\Core\Credentials\NullProvider;
use AsyncAws\Core\Test\TestCase;

class BedrockAgentClientTest extends TestCase
{
    public function testDeleteKnowledgeBaseDocuments(): void
    {
        $client = $this->getClient();

        $input = new DeleteKnowledgeBaseDocumentsRequest([
            'knowledgeBaseId' => 'change me',
            'dataSourceId' => 'change me',
            'clientToken' => 'change me',
            'documentIdentifiers' => [new DocumentIdentifier([
                'dataSourceType' => 'change me',
                's3' => new S3Location([
                    'uri' => 'change me',
                ]),
                'custom' => new CustomDocumentIdentifier([
                    'id' => 'change me',
                ]),
            ])],
        ]);
        $result = $client->deleteKnowledgeBaseDocuments($input);

        $result->resolve();

        // self::assertTODO(expected, $result->getDocumentDetails());
    }

    public function testGetKnowledgeBaseDocuments(): void
    {
        $client = $this->getClient();

        $input = new GetKnowledgeBaseDocumentsRequest([
            'knowledgeBaseId' => 'change me',
            'dataSourceId' => 'change me',
            'documentIdentifiers' => [new DocumentIdentifier([
                'dataSourceType' => 'change me',
                's3' => new S3Location([
                    'uri' => 'change me',
                ]),
                'custom' => new CustomDocumentIdentifier([
                    'id' => 'change me',
                ]),
            ])],
        ]);
        $result = $client->getKnowledgeBaseDocuments($input);

        $result->resolve();

        // self::assertTODO(expected, $result->getDocumentDetails());
    }

    public function testIngestKnowledgeBaseDocuments(): void
    {
        $client = $this->getClient();

        $input = new IngestKnowledgeBaseDocumentsRequest([
            'knowledgeBaseId' => 'change me',
            'dataSourceId' => 'change me',
            'clientToken' => 'change me',
            'documents' => [new KnowledgeBaseDocument([
                'metadata' => new DocumentMetadata([
                    'type' => 'change me',
                    'inlineAttributes' => [new MetadataAttribute([
                        'key' => 'change me',
                        'value' => new MetadataAttributeValue([
                            'type' => 'change me',
                            'numberValue' => 1337,
                            'booleanValue' => false,
                            'stringValue' => 'change me',
                            'stringListValue' => ['change me'],
                        ]),
                    ])],
                    's3Location' => new CustomS3Location([
                        'uri' => 'change me',
                        'bucketOwnerAccountId' => 'change me',
                    ]),
                ]),
                'content' => new DocumentContent([
                    'dataSourceType' => 'change me',
                    'custom' => new CustomContent([
                        'customDocumentIdentifier' => new CustomDocumentIdentifier([
                            'id' => 'change me',
                        ]),
                        'sourceType' => 'change me',
                        's3Location' => new CustomS3Location([
                            'uri' => 'change me',
                            'bucketOwnerAccountId' => 'change me',
                        ]),
                        'inlineContent' => new InlineContent([
                            'type' => 'change me',
                            'byteContent' => new ByteContentDoc([
                                'mimeType' => 'change me',
                                'data' => 'change me',
                            ]),
                            'textContent' => new TextContentDoc([
                                'data' => 'change me',
                            ]),
                        ]),
                    ]),
                    's3' => new S3Content([
                        's3Location' => new S3Location([
                            'uri' => 'change me',
                        ]),
                    ]),
                ]),
            ])],
        ]);
        $result = $client->ingestKnowledgeBaseDocuments($input);

        $result->resolve();

        // self::assertTODO(expected, $result->getDocumentDetails());
    }

    public function testListKnowledgeBaseDocuments(): void
    {
        $client = $this->getClient();

        $input = new ListKnowledgeBaseDocumentsRequest([
            'knowledgeBaseId' => 'change me',
            'dataSourceId' => 'change me',
            'maxResults' => 1337,
            'nextToken' => 'change me',
        ]);
        $result = $client->listKnowledgeBaseDocuments($input);

        $result->resolve();

        // self::assertTODO(expected, $result->getDocumentDetails());
        self::assertSame('changeIt', $result->getNextToken());
    }

    private function getClient(): BedrockAgentClient
    {
        self::markTestSkipped('There is no docker image available for BedrockAgent.');

        return new BedrockAgentClient([
            'endpoint' => 'http://localhost',
        ], new NullProvider());
    }
}
