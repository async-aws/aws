<?php

namespace AsyncAws\BedrockAgent;

use AsyncAws\BedrockAgent\Exception\AccessDeniedException;
use AsyncAws\BedrockAgent\Exception\InternalServerException;
use AsyncAws\BedrockAgent\Exception\ResourceNotFoundException;
use AsyncAws\BedrockAgent\Exception\ServiceQuotaExceededException;
use AsyncAws\BedrockAgent\Exception\ThrottlingException;
use AsyncAws\BedrockAgent\Exception\ValidationException;
use AsyncAws\BedrockAgent\Input\DeleteKnowledgeBaseDocumentsRequest;
use AsyncAws\BedrockAgent\Input\GetKnowledgeBaseDocumentsRequest;
use AsyncAws\BedrockAgent\Input\IngestKnowledgeBaseDocumentsRequest;
use AsyncAws\BedrockAgent\Input\ListKnowledgeBaseDocumentsRequest;
use AsyncAws\BedrockAgent\Result\DeleteKnowledgeBaseDocumentsResponse;
use AsyncAws\BedrockAgent\Result\GetKnowledgeBaseDocumentsResponse;
use AsyncAws\BedrockAgent\Result\IngestKnowledgeBaseDocumentsResponse;
use AsyncAws\BedrockAgent\Result\ListKnowledgeBaseDocumentsResponse;
use AsyncAws\BedrockAgent\ValueObject\DocumentIdentifier;
use AsyncAws\BedrockAgent\ValueObject\KnowledgeBaseDocument;
use AsyncAws\Core\AbstractApi;
use AsyncAws\Core\AwsError\AwsErrorFactoryInterface;
use AsyncAws\Core\AwsError\JsonRestAwsErrorFactory;
use AsyncAws\Core\Configuration;
use AsyncAws\Core\RequestContext;

class BedrockAgentClient extends AbstractApi
{
    /**
     * Deletes documents from a data source and syncs the changes to the knowledge base that is connected to it. For more
     * information, see Ingest changes directly into a knowledge base [^1] in the Amazon Bedrock User Guide.
     *
     * [^1]: https://docs.aws.amazon.com/bedrock/latest/userguide/kb-direct-ingestion.html
     *
     * @see https://docs.aws.amazon.com/bedrock/latest/APIReference/API_DeleteKnowledgeBaseDocuments.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-bedrock-agent-2023-06-05.html#deleteknowledgebasedocuments
     *
     * @param array{
     *   knowledgeBaseId: string,
     *   dataSourceId: string,
     *   clientToken?: string|null,
     *   documentIdentifiers: array<DocumentIdentifier|array>,
     *   '@region'?: string|null,
     * }|DeleteKnowledgeBaseDocumentsRequest $input
     *
     * @throws AccessDeniedException
     * @throws InternalServerException
     * @throws ResourceNotFoundException
     * @throws ServiceQuotaExceededException
     * @throws ThrottlingException
     * @throws ValidationException
     */
    public function deleteKnowledgeBaseDocuments($input): DeleteKnowledgeBaseDocumentsResponse
    {
        $input = DeleteKnowledgeBaseDocumentsRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'DeleteKnowledgeBaseDocuments', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'AccessDeniedException' => AccessDeniedException::class,
            'InternalServerException' => InternalServerException::class,
            'ResourceNotFoundException' => ResourceNotFoundException::class,
            'ServiceQuotaExceededException' => ServiceQuotaExceededException::class,
            'ThrottlingException' => ThrottlingException::class,
            'ValidationException' => ValidationException::class,
        ]]));

        return new DeleteKnowledgeBaseDocumentsResponse($response);
    }

    /**
     * Retrieves specific documents from a data source that is connected to a knowledge base. For more information, see
     * Ingest changes directly into a knowledge base [^1] in the Amazon Bedrock User Guide.
     *
     * [^1]: https://docs.aws.amazon.com/bedrock/latest/userguide/kb-direct-ingestion.html
     *
     * @see https://docs.aws.amazon.com/bedrock/latest/APIReference/API_GetKnowledgeBaseDocuments.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-bedrock-agent-2023-06-05.html#getknowledgebasedocuments
     *
     * @param array{
     *   knowledgeBaseId: string,
     *   dataSourceId: string,
     *   documentIdentifiers: array<DocumentIdentifier|array>,
     *   '@region'?: string|null,
     * }|GetKnowledgeBaseDocumentsRequest $input
     *
     * @throws AccessDeniedException
     * @throws InternalServerException
     * @throws ResourceNotFoundException
     * @throws ServiceQuotaExceededException
     * @throws ThrottlingException
     * @throws ValidationException
     */
    public function getKnowledgeBaseDocuments($input): GetKnowledgeBaseDocumentsResponse
    {
        $input = GetKnowledgeBaseDocumentsRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'GetKnowledgeBaseDocuments', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'AccessDeniedException' => AccessDeniedException::class,
            'InternalServerException' => InternalServerException::class,
            'ResourceNotFoundException' => ResourceNotFoundException::class,
            'ServiceQuotaExceededException' => ServiceQuotaExceededException::class,
            'ThrottlingException' => ThrottlingException::class,
            'ValidationException' => ValidationException::class,
        ]]));

        return new GetKnowledgeBaseDocumentsResponse($response);
    }

    /**
     * Ingests documents directly into the knowledge base that is connected to the data source. The `dataSourceType`
     * specified in the content for each document must match the type of the data source that you specify in the header. For
     * more information, see Ingest changes directly into a knowledge base [^1] in the Amazon Bedrock User Guide.
     *
     * [^1]: https://docs.aws.amazon.com/bedrock/latest/userguide/kb-direct-ingestion.html
     *
     * @see https://docs.aws.amazon.com/bedrock/latest/APIReference/API_IngestKnowledgeBaseDocuments.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-bedrock-agent-2023-06-05.html#ingestknowledgebasedocuments
     *
     * @param array{
     *   knowledgeBaseId: string,
     *   dataSourceId: string,
     *   clientToken?: string|null,
     *   documents: array<KnowledgeBaseDocument|array>,
     *   '@region'?: string|null,
     * }|IngestKnowledgeBaseDocumentsRequest $input
     *
     * @throws AccessDeniedException
     * @throws InternalServerException
     * @throws ResourceNotFoundException
     * @throws ServiceQuotaExceededException
     * @throws ThrottlingException
     * @throws ValidationException
     */
    public function ingestKnowledgeBaseDocuments($input): IngestKnowledgeBaseDocumentsResponse
    {
        $input = IngestKnowledgeBaseDocumentsRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'IngestKnowledgeBaseDocuments', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'AccessDeniedException' => AccessDeniedException::class,
            'InternalServerException' => InternalServerException::class,
            'ResourceNotFoundException' => ResourceNotFoundException::class,
            'ServiceQuotaExceededException' => ServiceQuotaExceededException::class,
            'ThrottlingException' => ThrottlingException::class,
            'ValidationException' => ValidationException::class,
        ]]));

        return new IngestKnowledgeBaseDocumentsResponse($response);
    }

    /**
     * Retrieves all the documents contained in a data source that is connected to a knowledge base. For more information,
     * see Ingest changes directly into a knowledge base [^1] in the Amazon Bedrock User Guide.
     *
     * [^1]: https://docs.aws.amazon.com/bedrock/latest/userguide/kb-direct-ingestion.html
     *
     * @see https://docs.aws.amazon.com/bedrock/latest/APIReference/API_ListKnowledgeBaseDocuments.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-bedrock-agent-2023-06-05.html#listknowledgebasedocuments
     *
     * @param array{
     *   knowledgeBaseId: string,
     *   dataSourceId: string,
     *   maxResults?: int|null,
     *   nextToken?: string|null,
     *   '@region'?: string|null,
     * }|ListKnowledgeBaseDocumentsRequest $input
     *
     * @throws AccessDeniedException
     * @throws InternalServerException
     * @throws ResourceNotFoundException
     * @throws ServiceQuotaExceededException
     * @throws ThrottlingException
     * @throws ValidationException
     */
    public function listKnowledgeBaseDocuments($input): ListKnowledgeBaseDocumentsResponse
    {
        $input = ListKnowledgeBaseDocumentsRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'ListKnowledgeBaseDocuments', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'AccessDeniedException' => AccessDeniedException::class,
            'InternalServerException' => InternalServerException::class,
            'ResourceNotFoundException' => ResourceNotFoundException::class,
            'ServiceQuotaExceededException' => ServiceQuotaExceededException::class,
            'ThrottlingException' => ThrottlingException::class,
            'ValidationException' => ValidationException::class,
        ]]));

        return new ListKnowledgeBaseDocumentsResponse($response, $this, $input);
    }

    protected function getAwsErrorFactory(): AwsErrorFactoryInterface
    {
        return new JsonRestAwsErrorFactory();
    }

    protected function getEndpointMetadata(?string $region): array
    {
        if (null === $region) {
            $region = Configuration::DEFAULT_REGION;
        }

        return [
            'endpoint' => "https://bedrock-agent.$region.amazonaws.com",
            'signRegion' => $region,
            'signService' => 'bedrock',
            'signVersions' => ['v4'],
        ];
    }
}
