<?php

namespace AsyncAws\BedrockAgent\Tests\Unit\Input;

use AsyncAws\BedrockAgent\Enum\ContentDataSourceType;
use AsyncAws\BedrockAgent\Enum\CustomSourceType;
use AsyncAws\BedrockAgent\Enum\InlineContentType;
use AsyncAws\BedrockAgent\Enum\MetadataSourceType;
use AsyncAws\BedrockAgent\Enum\MetadataValueType;
use AsyncAws\BedrockAgent\Input\IngestKnowledgeBaseDocumentsRequest;
use AsyncAws\BedrockAgent\ValueObject\ByteContentDoc;
use AsyncAws\BedrockAgent\ValueObject\CustomContent;
use AsyncAws\BedrockAgent\ValueObject\CustomDocumentIdentifier;
use AsyncAws\BedrockAgent\ValueObject\CustomS3Location;
use AsyncAws\BedrockAgent\ValueObject\DocumentContent;
use AsyncAws\BedrockAgent\ValueObject\DocumentMetadata;
use AsyncAws\BedrockAgent\ValueObject\InlineContent;
use AsyncAws\BedrockAgent\ValueObject\KnowledgeBaseDocument;
use AsyncAws\BedrockAgent\ValueObject\MetadataAttribute;
use AsyncAws\BedrockAgent\ValueObject\MetadataAttributeValue;
use AsyncAws\BedrockAgent\ValueObject\S3Content;
use AsyncAws\BedrockAgent\ValueObject\S3Location;
use AsyncAws\BedrockAgent\ValueObject\TextContentDoc;
use AsyncAws\Core\Test\TestCase;

class IngestKnowledgeBaseDocumentsRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new IngestKnowledgeBaseDocumentsRequest([
            'knowledgeBaseId' => 'kb-1234567890abcdef',
            'dataSourceId' => 'ds-abcdef1234567890',
            'clientToken' => 'token-1234',
            'documents' => [new KnowledgeBaseDocument([
                'metadata' => new DocumentMetadata([
                    'type' => MetadataSourceType::IN_LINE_ATTRIBUTE,
                    'inlineAttributes' => [new MetadataAttribute([
                        'key' => 'author',
                        'value' => new MetadataAttributeValue([
                            'type' => MetadataValueType::STRING,
                            'stringValue' => 'John Doe',
                        ]),
                    ])],
                    's3Location' => new CustomS3Location([
                        'uri' => 's3://my-bucket/documents/',
                        'bucketOwnerAccountId' => 'account-1234',
                    ]),
                ]),
                'content' => new DocumentContent([
                    'dataSourceType' => ContentDataSourceType::S3,
                    'custom' => new CustomContent([
                        'customDocumentIdentifier' => new CustomDocumentIdentifier([
                            'id' => 'custom-doc-001',
                        ]),
                        'sourceType' => CustomSourceType::IN_LINE,
                        's3Location' => new CustomS3Location([
                            'uri' => 's3://my-bucket/documents/',
                            'bucketOwnerAccountId' => 'account-1234',
                        ]),
                        'inlineContent' => new InlineContent([
                            'type' => InlineContentType::TEXT,
                            'byteContent' => new ByteContentDoc([
                                'mimeType' => 'application/pdf',
                                'data' => 'base64-pdf-data',
                            ]),
                            'textContent' => new TextContentDoc([
                                'data' => 'Document text content',
                            ]),
                        ]),
                    ]),
                    's3' => new S3Content([
                        's3Location' => new S3Location([
                            'uri' => 's3://my-bucket/documents/',
                        ]),
                    ]),
                ]),
            ])],
        ]);

        // see https://docs.aws.amazon.com/bedrock/latest/APIReference/API_agent_IngestKnowledgeBaseDocuments.html
        $expected = '
            PUT /knowledgebases/kb-1234567890abcdef/datasources/ds-abcdef1234567890/documents HTTP/1.1
            Content-Type: application/json
            Accept: application/json

            {
                "clientToken": "token-1234",
                "documents": [
                    {
                        "metadata": {
                            "type": "IN_LINE_ATTRIBUTE",
                            "inlineAttributes": [
                                {
                                    "key": "author",
                                    "value": {
                                        "type": "STRING",
                                        "stringValue": "John Doe"
                                    }
                                }
                            ],
                            "s3Location": {
                                "uri": "s3://my-bucket/documents/",
                                "bucketOwnerAccountId": "account-1234"
                            }
                        },
                        "content": {
                            "dataSourceType": "S3",
                            "custom": {
                                "customDocumentIdentifier": {
                                    "id": "custom-doc-001"
                                },
                                "sourceType": "IN_LINE",
                                "s3Location": {
                                    "uri": "s3://my-bucket/documents/",
                                    "bucketOwnerAccountId": "account-1234"
                                },
                                "inlineContent": {
                                    "type": "TEXT",
                                    "byteContent": {
                                        "mimeType": "application/pdf",
                                        "data": "YmFzZTY0LXBkZi1kYXRh"
                                    },
                                    "textContent": {
                                        "data": "Document text content"
                                    }
                                }
                            },
                            "s3": {
                                "s3Location": {
                                    "uri": "s3://my-bucket/documents/"
                                }
                            }
                        }
                    }
                ]
            }
        ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
