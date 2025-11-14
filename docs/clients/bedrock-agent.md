---
layout: client
category: clients
name: BedrockAgent
package: async-aws/bedrock-agent
---

## Usage

### Delete knowledge base documents

```php
use AsyncAws\BedrockAgent\BedrockAgentClient;
use AsyncAws\BedrockAgent\Enum\ContentDataSourceType;
use AsyncAws\BedrockAgent\Input\DeleteKnowledgeBaseDocumentsRequest;
use AsyncAws\BedrockAgent\ValueObject\DocumentIdentifier;
use AsyncAws\BedrockAgent\ValueObject\S3Location;

$bedrockAgent = new BedrockAgentClient();

$request = new DeleteKnowledgeBaseDocumentsRequest([
    'knowledgeBaseId' => 'kb-1234567890abcdef',
    'dataSourceId' => 'ds-abcdef1234567890',
    'clientToken' => 'token-1234',
    'documentIdentifiers' => [new DocumentIdentifier([
        'dataSourceType' => ContentDataSourceType::S3,
        's3' => new S3Location([
            'uri' => 's3://my-bucket/documents/',
        ]),
    ])],
]);

$result = $bedrockAgent->deleteKnowledgeBaseDocuments($request);

foreach ($result->getDocumentDetails() as $documentDetail) {
    echo $documentDetail->getStatus() . PHP_EOL;
}

```
See [`DeleteKnowledgeBaseDocuments`](https://docs.aws.amazon.com/bedrock/latest/APIReference/API_agent_DeleteKnowledgeBaseDocuments.html) for more information.

### Get knowledge base documents

```php
use AsyncAws\BedrockAgent\BedrockAgentClient;
use AsyncAws\BedrockAgent\Enum\ContentDataSourceType;
use AsyncAws\BedrockAgent\Input\GetKnowledgeBaseDocumentsRequest;
use AsyncAws\BedrockAgent\ValueObject\DocumentIdentifier;
use AsyncAws\BedrockAgent\ValueObject\S3Location;

$bedrockAgent = new BedrockAgentClient();

$request = new GetKnowledgeBaseDocumentsRequest([
    'knowledgeBaseId' => 'kb-1234567890abcdef',
    'dataSourceId' => 'ds-abcdef1234567890',
    'documentIdentifiers' => [new DocumentIdentifier([
        'dataSourceType' => ContentDataSourceType::S3,
        's3' => new S3Location([
            'uri' => 's3://my-bucket/documents/',
        ]),
    ])],
]);

$result = $bedrockAgent->getKnowledgeBaseDocuments($request);

foreach ($result->getDocumentDetails() as $documentDetail) {
    echo $documentDetail->getIdentifier()->getS3()->getUri() . PHP_EOL;
}

```
See [`GetKnowledgeBaseDocuments`](https://docs.aws.amazon.com/bedrock/latest/APIReference/API_agent_GetKnowledgeBaseDocuments.html) for more information.

### Ingest knowledge base documents

```php
use AsyncAws\BedrockAgent\Enum\ContentDataSourceType;
use AsyncAws\BedrockAgent\Enum\MetadataSourceType;
use AsyncAws\BedrockAgent\Input\IngestKnowledgeBaseDocumentsRequest;
use AsyncAws\BedrockAgent\ValueObject\CustomS3Location;
use AsyncAws\BedrockAgent\ValueObject\DocumentContent;
use AsyncAws\BedrockAgent\ValueObject\DocumentMetadata;
use AsyncAws\BedrockAgent\ValueObject\KnowledgeBaseDocument;
use AsyncAws\BedrockAgent\ValueObject\S3Content;
use AsyncAws\BedrockAgent\ValueObject\S3Location;

$bedrockAgent = new BedrockAgentClient();

$request = new IngestKnowledgeBaseDocumentsRequest([
    'knowledgeBaseId' => 'kb-1234567890abcdef',
    'dataSourceId' => 'ds-abcdef1234567890',
    'clientToken' => 'token-1234',
    'documents' => [new KnowledgeBaseDocument([
        'metadata' => new DocumentMetadata([
            'type' => MetadataSourceType::S3_LOCATION,
            's3Location' => new CustomS3Location([
                'uri' => 's3://my-bucket/documents/',
                'bucketOwnerAccountId' => 'account-1234',
            ]),
        ]),
        'content' => new DocumentContent([
            'dataSourceType' => ContentDataSourceType::S3,
            's3' => new S3Content([
                's3Location' => new S3Location([
                    'uri' => 's3://my-bucket/documents/',
                ]),
            ]),
        ]),
    ])],
]);

$result = $bedrockAgent->ingestKnowledgeBaseDocuments($request);

foreach ($result->getDocumentDetails() as $documentDetail) {
    echo $documentDetail->getStatus() . PHP_EOL;
    echo $documentDetail->getIdentifier()->getS3()->getUri() . PHP_EOL;
}

```
See [`IngestKnowledgeBaseDocuments`](https://docs.aws.amazon.com/bedrock/latest/APIReference/API_agent_IngestKnowledgeBaseDocuments.html) for more information.

### List knowledge base documents

```php
use AsyncAws\BedrockAgent\BedrockAgentClient;
use AsyncAws\BedrockAgent\Input\ListKnowledgeBaseDocumentsRequest;

$bedrockAgent = new BedrockAgentClient();

$request = new ListKnowledgeBaseDocumentsRequest([
    'knowledgeBaseId' => 'kb-1234567890abcdef',
    'dataSourceId' => 'ds-abcdef1234567890',
    'maxResults' => 50,
]);

$result = $bedrockAgent->listKnowledgeBaseDocuments($request);

foreach ($result->getDocumentDetails() as $documentDetail) {
    echo $documentDetail->getIdentifier()->getS3()->getUri() . PHP_EOL;
}

```
See [`ListKnowledgeBaseDocuments`](https://docs.aws.amazon.com/bedrock/latest/APIReference/API_agent_ListKnowledgeBaseDocuments.html) for more information.
