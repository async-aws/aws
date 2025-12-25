<?php

namespace AsyncAws\S3Vectors;

use AsyncAws\Core\AbstractApi;
use AsyncAws\Core\AwsError\AwsErrorFactoryInterface;
use AsyncAws\Core\AwsError\JsonRestAwsErrorFactory;
use AsyncAws\Core\Configuration;
use AsyncAws\Core\RequestContext;
use AsyncAws\S3Vectors\Enum\DataType;
use AsyncAws\S3Vectors\Enum\DistanceMetric;
use AsyncAws\S3Vectors\Exception\AccessDeniedException;
use AsyncAws\S3Vectors\Exception\ConflictException;
use AsyncAws\S3Vectors\Exception\InternalServerException;
use AsyncAws\S3Vectors\Exception\KmsDisabledException;
use AsyncAws\S3Vectors\Exception\KmsInvalidKeyUsageException;
use AsyncAws\S3Vectors\Exception\KmsInvalidStateException;
use AsyncAws\S3Vectors\Exception\KmsNotFoundException;
use AsyncAws\S3Vectors\Exception\NotFoundException;
use AsyncAws\S3Vectors\Exception\RequestTimeoutException;
use AsyncAws\S3Vectors\Exception\ServiceQuotaExceededException;
use AsyncAws\S3Vectors\Exception\ServiceUnavailableException;
use AsyncAws\S3Vectors\Exception\TooManyRequestsException;
use AsyncAws\S3Vectors\Exception\ValidationException;
use AsyncAws\S3Vectors\Input\CreateIndexInput;
use AsyncAws\S3Vectors\Input\CreateVectorBucketInput;
use AsyncAws\S3Vectors\Input\DeleteIndexInput;
use AsyncAws\S3Vectors\Input\DeleteVectorBucketInput;
use AsyncAws\S3Vectors\Input\DeleteVectorBucketPolicyInput;
use AsyncAws\S3Vectors\Input\DeleteVectorsInput;
use AsyncAws\S3Vectors\Input\GetIndexInput;
use AsyncAws\S3Vectors\Input\GetVectorBucketInput;
use AsyncAws\S3Vectors\Input\GetVectorBucketPolicyInput;
use AsyncAws\S3Vectors\Input\ListIndexesInput;
use AsyncAws\S3Vectors\Input\ListTagsForResourceInput;
use AsyncAws\S3Vectors\Input\ListVectorBucketsInput;
use AsyncAws\S3Vectors\Input\PutVectorBucketPolicyInput;
use AsyncAws\S3Vectors\Input\TagResourceInput;
use AsyncAws\S3Vectors\Result\CreateIndexOutput;
use AsyncAws\S3Vectors\Result\CreateVectorBucketOutput;
use AsyncAws\S3Vectors\Result\DeleteIndexOutput;
use AsyncAws\S3Vectors\Result\DeleteVectorBucketOutput;
use AsyncAws\S3Vectors\Result\DeleteVectorBucketPolicyOutput;
use AsyncAws\S3Vectors\Result\DeleteVectorsOutput;
use AsyncAws\S3Vectors\Result\GetIndexOutput;
use AsyncAws\S3Vectors\Result\GetVectorBucketOutput;
use AsyncAws\S3Vectors\Result\GetVectorBucketPolicyOutput;
use AsyncAws\S3Vectors\Result\ListIndexesOutput;
use AsyncAws\S3Vectors\Result\ListTagsForResourceOutput;
use AsyncAws\S3Vectors\Result\ListVectorBucketsOutput;
use AsyncAws\S3Vectors\Result\PutVectorBucketPolicyOutput;
use AsyncAws\S3Vectors\Result\TagResourceOutput;
use AsyncAws\S3Vectors\ValueObject\EncryptionConfiguration;
use AsyncAws\S3Vectors\ValueObject\MetadataConfiguration;

class S3VectorsClient extends AbstractApi
{
    /**
     * Creates a vector index within a vector bucket. To specify the vector bucket, you must use either the vector bucket
     * name or the vector bucket Amazon Resource Name (ARN).
     *
     * - `Permissions`:
     *
     *   You must have the `s3vectors:CreateIndex` permission to use this operation.
     *
     *   You must have the `s3vectors:TagResource` permission in addition to `s3vectors:CreateIndex` permission to create a
     *   vector index with tags.
     *
     * @see https://docs.aws.amazon.com/AmazonS3/latest/API/API_Operations_Amazon_S3_Vectors.html/API_CreateIndex.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-s3vectors-2025-07-15.html#createindex
     *
     * @param array{
     *   vectorBucketName?: string|null,
     *   vectorBucketArn?: string|null,
     *   indexName: string,
     *   dataType: DataType::*,
     *   dimension: int,
     *   distanceMetric: DistanceMetric::*,
     *   metadataConfiguration?: MetadataConfiguration|array|null,
     *   encryptionConfiguration?: EncryptionConfiguration|array|null,
     *   tags?: array<string, string>|null,
     *   '@region'?: string|null,
     * }|CreateIndexInput $input
     *
     * @throws AccessDeniedException
     * @throws ConflictException
     * @throws InternalServerException
     * @throws NotFoundException
     * @throws RequestTimeoutException
     * @throws ServiceQuotaExceededException
     * @throws ServiceUnavailableException
     * @throws TooManyRequestsException
     * @throws ValidationException
     */
    public function createIndex($input): CreateIndexOutput
    {
        $input = CreateIndexInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'CreateIndex', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'AccessDeniedException' => AccessDeniedException::class,
            'ConflictException' => ConflictException::class,
            'InternalServerException' => InternalServerException::class,
            'NotFoundException' => NotFoundException::class,
            'RequestTimeoutException' => RequestTimeoutException::class,
            'ServiceQuotaExceededException' => ServiceQuotaExceededException::class,
            'ServiceUnavailableException' => ServiceUnavailableException::class,
            'TooManyRequestsException' => TooManyRequestsException::class,
            'ValidationException' => ValidationException::class,
        ]]));

        return new CreateIndexOutput($response);
    }

    /**
     * Creates a vector bucket in the Amazon Web Services Region that you want your bucket to be in.
     *
     * - `Permissions`:
     *
     *   You must have the `s3vectors:CreateVectorBucket` permission to use this operation.
     *
     *   You must have the `s3vectors:TagResource` permission in addition to `s3vectors:CreateVectorBucket` permission to
     *   create a vector bucket with tags.
     *
     * @see https://docs.aws.amazon.com/AmazonS3/latest/API/API_Operations_Amazon_S3_Vectors.html/API_CreateVectorBucket.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-s3vectors-2025-07-15.html#createvectorbucket
     *
     * @param array{
     *   vectorBucketName: string,
     *   encryptionConfiguration?: EncryptionConfiguration|array|null,
     *   tags?: array<string, string>|null,
     *   '@region'?: string|null,
     * }|CreateVectorBucketInput $input
     *
     * @throws AccessDeniedException
     * @throws ConflictException
     * @throws InternalServerException
     * @throws RequestTimeoutException
     * @throws ServiceQuotaExceededException
     * @throws ServiceUnavailableException
     * @throws TooManyRequestsException
     * @throws ValidationException
     */
    public function createVectorBucket($input): CreateVectorBucketOutput
    {
        $input = CreateVectorBucketInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'CreateVectorBucket', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'AccessDeniedException' => AccessDeniedException::class,
            'ConflictException' => ConflictException::class,
            'InternalServerException' => InternalServerException::class,
            'RequestTimeoutException' => RequestTimeoutException::class,
            'ServiceQuotaExceededException' => ServiceQuotaExceededException::class,
            'ServiceUnavailableException' => ServiceUnavailableException::class,
            'TooManyRequestsException' => TooManyRequestsException::class,
            'ValidationException' => ValidationException::class,
        ]]));

        return new CreateVectorBucketOutput($response);
    }

    /**
     * Deletes a vector index. To specify the vector index, you can either use both the vector bucket name and vector index
     * name, or use the vector index Amazon Resource Name (ARN).
     *
     * - `Permissions`:
     *
     *   You must have the `s3vectors:DeleteIndex` permission to use this operation.
     *
     * @see https://docs.aws.amazon.com/AmazonS3/latest/API/API_Operations_Amazon_S3_Vectors.html/API_DeleteIndex.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-s3vectors-2025-07-15.html#deleteindex
     *
     * @param array{
     *   vectorBucketName?: string|null,
     *   indexName?: string|null,
     *   indexArn?: string|null,
     *   '@region'?: string|null,
     * }|DeleteIndexInput $input
     *
     * @throws AccessDeniedException
     * @throws InternalServerException
     * @throws NotFoundException
     * @throws RequestTimeoutException
     * @throws ServiceUnavailableException
     * @throws TooManyRequestsException
     * @throws ValidationException
     */
    public function deleteIndex($input = []): DeleteIndexOutput
    {
        $input = DeleteIndexInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'DeleteIndex', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'AccessDeniedException' => AccessDeniedException::class,
            'InternalServerException' => InternalServerException::class,
            'NotFoundException' => NotFoundException::class,
            'RequestTimeoutException' => RequestTimeoutException::class,
            'ServiceUnavailableException' => ServiceUnavailableException::class,
            'TooManyRequestsException' => TooManyRequestsException::class,
            'ValidationException' => ValidationException::class,
        ]]));

        return new DeleteIndexOutput($response);
    }

    /**
     * Deletes a vector bucket. All vector indexes in the vector bucket must be deleted before the vector bucket can be
     * deleted. To perform this operation, you must use either the vector bucket name or the vector bucket Amazon Resource
     * Name (ARN).
     *
     * - `Permissions`:
     *
     *   You must have the `s3vectors:DeleteVectorBucket` permission to use this operation.
     *
     * @see https://docs.aws.amazon.com/AmazonS3/latest/API/API_Operations_Amazon_S3_Vectors.html/API_DeleteVectorBucket.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-s3vectors-2025-07-15.html#deletevectorbucket
     *
     * @param array{
     *   vectorBucketName?: string|null,
     *   vectorBucketArn?: string|null,
     *   '@region'?: string|null,
     * }|DeleteVectorBucketInput $input
     *
     * @throws AccessDeniedException
     * @throws ConflictException
     * @throws InternalServerException
     * @throws NotFoundException
     * @throws RequestTimeoutException
     * @throws ServiceUnavailableException
     * @throws TooManyRequestsException
     * @throws ValidationException
     */
    public function deleteVectorBucket($input = []): DeleteVectorBucketOutput
    {
        $input = DeleteVectorBucketInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'DeleteVectorBucket', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'AccessDeniedException' => AccessDeniedException::class,
            'ConflictException' => ConflictException::class,
            'InternalServerException' => InternalServerException::class,
            'NotFoundException' => NotFoundException::class,
            'RequestTimeoutException' => RequestTimeoutException::class,
            'ServiceUnavailableException' => ServiceUnavailableException::class,
            'TooManyRequestsException' => TooManyRequestsException::class,
            'ValidationException' => ValidationException::class,
        ]]));

        return new DeleteVectorBucketOutput($response);
    }

    /**
     * Deletes a vector bucket policy. To specify the bucket, you must use either the vector bucket name or the vector
     * bucket Amazon Resource Name (ARN).
     *
     * - `Permissions`:
     *
     *   You must have the `s3vectors:DeleteVectorBucketPolicy` permission to use this operation.
     *
     * @see https://docs.aws.amazon.com/AmazonS3/latest/API/API_Operations_Amazon_S3_Vectors.html/API_DeleteVectorBucketPolicy.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-s3vectors-2025-07-15.html#deletevectorbucketpolicy
     *
     * @param array{
     *   vectorBucketName?: string|null,
     *   vectorBucketArn?: string|null,
     *   '@region'?: string|null,
     * }|DeleteVectorBucketPolicyInput $input
     *
     * @throws AccessDeniedException
     * @throws InternalServerException
     * @throws NotFoundException
     * @throws RequestTimeoutException
     * @throws ServiceUnavailableException
     * @throws TooManyRequestsException
     * @throws ValidationException
     */
    public function deleteVectorBucketPolicy($input = []): DeleteVectorBucketPolicyOutput
    {
        $input = DeleteVectorBucketPolicyInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'DeleteVectorBucketPolicy', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'AccessDeniedException' => AccessDeniedException::class,
            'InternalServerException' => InternalServerException::class,
            'NotFoundException' => NotFoundException::class,
            'RequestTimeoutException' => RequestTimeoutException::class,
            'ServiceUnavailableException' => ServiceUnavailableException::class,
            'TooManyRequestsException' => TooManyRequestsException::class,
            'ValidationException' => ValidationException::class,
        ]]));

        return new DeleteVectorBucketPolicyOutput($response);
    }

    /**
     * Deletes one or more vectors in a vector index. To specify the vector index, you can either use both the vector bucket
     * name and vector index name, or use the vector index Amazon Resource Name (ARN).
     *
     * - `Permissions`:
     *
     *   You must have the `s3vectors:DeleteVectors` permission to use this operation.
     *
     * @see https://docs.aws.amazon.com/AmazonS3/latest/API/API_Operations_Amazon_S3_Vectors.html/API_DeleteVectors.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-s3vectors-2025-07-15.html#deletevectors
     *
     * @param array{
     *   vectorBucketName?: string|null,
     *   indexName?: string|null,
     *   indexArn?: string|null,
     *   keys: string[],
     *   '@region'?: string|null,
     * }|DeleteVectorsInput $input
     *
     * @throws AccessDeniedException
     * @throws InternalServerException
     * @throws KmsDisabledException
     * @throws KmsInvalidKeyUsageException
     * @throws KmsInvalidStateException
     * @throws KmsNotFoundException
     * @throws NotFoundException
     * @throws RequestTimeoutException
     * @throws ServiceUnavailableException
     * @throws TooManyRequestsException
     * @throws ValidationException
     */
    public function deleteVectors($input): DeleteVectorsOutput
    {
        $input = DeleteVectorsInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'DeleteVectors', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'AccessDeniedException' => AccessDeniedException::class,
            'InternalServerException' => InternalServerException::class,
            'KmsDisabledException' => KmsDisabledException::class,
            'KmsInvalidKeyUsageException' => KmsInvalidKeyUsageException::class,
            'KmsInvalidStateException' => KmsInvalidStateException::class,
            'KmsNotFoundException' => KmsNotFoundException::class,
            'NotFoundException' => NotFoundException::class,
            'RequestTimeoutException' => RequestTimeoutException::class,
            'ServiceUnavailableException' => ServiceUnavailableException::class,
            'TooManyRequestsException' => TooManyRequestsException::class,
            'ValidationException' => ValidationException::class,
        ]]));

        return new DeleteVectorsOutput($response);
    }

    /**
     * Returns vector index attributes. To specify the vector index, you can either use both the vector bucket name and the
     * vector index name, or use the vector index Amazon Resource Name (ARN).
     *
     * - `Permissions`:
     *
     *   You must have the `s3vectors:GetIndex` permission to use this operation.
     *
     * @see https://docs.aws.amazon.com/AmazonS3/latest/API/API_Operations_Amazon_S3_Vectors.html/API_GetIndex.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-s3vectors-2025-07-15.html#getindex
     *
     * @param array{
     *   vectorBucketName?: string|null,
     *   indexName?: string|null,
     *   indexArn?: string|null,
     *   '@region'?: string|null,
     * }|GetIndexInput $input
     *
     * @throws AccessDeniedException
     * @throws InternalServerException
     * @throws NotFoundException
     * @throws RequestTimeoutException
     * @throws ServiceUnavailableException
     * @throws TooManyRequestsException
     * @throws ValidationException
     */
    public function getIndex($input = []): GetIndexOutput
    {
        $input = GetIndexInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'GetIndex', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'AccessDeniedException' => AccessDeniedException::class,
            'InternalServerException' => InternalServerException::class,
            'NotFoundException' => NotFoundException::class,
            'RequestTimeoutException' => RequestTimeoutException::class,
            'ServiceUnavailableException' => ServiceUnavailableException::class,
            'TooManyRequestsException' => TooManyRequestsException::class,
            'ValidationException' => ValidationException::class,
        ]]));

        return new GetIndexOutput($response);
    }

    /**
     * Returns vector bucket attributes. To specify the bucket, you must use either the vector bucket name or the vector
     * bucket Amazon Resource Name (ARN).
     *
     * - `Permissions`:
     *
     *   You must have the `s3vectors:GetVectorBucket` permission to use this operation.
     *
     * @see https://docs.aws.amazon.com/AmazonS3/latest/API/API_Operations_Amazon_S3_Vectors.html/API_GetVectorBucket.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-s3vectors-2025-07-15.html#getvectorbucket
     *
     * @param array{
     *   vectorBucketName?: string|null,
     *   vectorBucketArn?: string|null,
     *   '@region'?: string|null,
     * }|GetVectorBucketInput $input
     *
     * @throws AccessDeniedException
     * @throws InternalServerException
     * @throws NotFoundException
     * @throws RequestTimeoutException
     * @throws ServiceUnavailableException
     * @throws TooManyRequestsException
     * @throws ValidationException
     */
    public function getVectorBucket($input = []): GetVectorBucketOutput
    {
        $input = GetVectorBucketInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'GetVectorBucket', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'AccessDeniedException' => AccessDeniedException::class,
            'InternalServerException' => InternalServerException::class,
            'NotFoundException' => NotFoundException::class,
            'RequestTimeoutException' => RequestTimeoutException::class,
            'ServiceUnavailableException' => ServiceUnavailableException::class,
            'TooManyRequestsException' => TooManyRequestsException::class,
            'ValidationException' => ValidationException::class,
        ]]));

        return new GetVectorBucketOutput($response);
    }

    /**
     * Gets details about a vector bucket policy. To specify the bucket, you must use either the vector bucket name or the
     * vector bucket Amazon Resource Name (ARN).
     *
     * - `Permissions`:
     *
     *   You must have the `s3vectors:GetVectorBucketPolicy` permission to use this operation.
     *
     * @see https://docs.aws.amazon.com/AmazonS3/latest/API/API_Operations_Amazon_S3_Vectors.html/API_GetVectorBucketPolicy.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-s3vectors-2025-07-15.html#getvectorbucketpolicy
     *
     * @param array{
     *   vectorBucketName?: string|null,
     *   vectorBucketArn?: string|null,
     *   '@region'?: string|null,
     * }|GetVectorBucketPolicyInput $input
     *
     * @throws AccessDeniedException
     * @throws InternalServerException
     * @throws NotFoundException
     * @throws RequestTimeoutException
     * @throws ServiceUnavailableException
     * @throws TooManyRequestsException
     * @throws ValidationException
     */
    public function getVectorBucketPolicy($input = []): GetVectorBucketPolicyOutput
    {
        $input = GetVectorBucketPolicyInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'GetVectorBucketPolicy', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'AccessDeniedException' => AccessDeniedException::class,
            'InternalServerException' => InternalServerException::class,
            'NotFoundException' => NotFoundException::class,
            'RequestTimeoutException' => RequestTimeoutException::class,
            'ServiceUnavailableException' => ServiceUnavailableException::class,
            'TooManyRequestsException' => TooManyRequestsException::class,
            'ValidationException' => ValidationException::class,
        ]]));

        return new GetVectorBucketPolicyOutput($response);
    }

    /**
     * Returns a list of all the vector indexes within the specified vector bucket. To specify the bucket, you must use
     * either the vector bucket name or the vector bucket Amazon Resource Name (ARN).
     *
     * - `Permissions`:
     *
     *   You must have the `s3vectors:ListIndexes` permission to use this operation.
     *
     * @see https://docs.aws.amazon.com/AmazonS3/latest/API/API_Operations_Amazon_S3_Vectors.html/API_ListIndexes.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-s3vectors-2025-07-15.html#listindexes
     *
     * @param array{
     *   vectorBucketName?: string|null,
     *   vectorBucketArn?: string|null,
     *   maxResults?: int|null,
     *   nextToken?: string|null,
     *   prefix?: string|null,
     *   '@region'?: string|null,
     * }|ListIndexesInput $input
     *
     * @throws AccessDeniedException
     * @throws InternalServerException
     * @throws NotFoundException
     * @throws RequestTimeoutException
     * @throws ServiceUnavailableException
     * @throws TooManyRequestsException
     * @throws ValidationException
     */
    public function listIndexes($input = []): ListIndexesOutput
    {
        $input = ListIndexesInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'ListIndexes', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'AccessDeniedException' => AccessDeniedException::class,
            'InternalServerException' => InternalServerException::class,
            'NotFoundException' => NotFoundException::class,
            'RequestTimeoutException' => RequestTimeoutException::class,
            'ServiceUnavailableException' => ServiceUnavailableException::class,
            'TooManyRequestsException' => TooManyRequestsException::class,
            'ValidationException' => ValidationException::class,
        ]]));

        return new ListIndexesOutput($response, $this, $input);
    }

    /**
     * Lists all of the tags applied to a specified Amazon S3 Vectors resource. Each tag is a label consisting of a key and
     * value pair. Tags can help you organize, track costs for, and control access to resources.
     *
     * > For a list of S3 resources that support tagging, see Managing tags for Amazon S3 resources [^1].
     *
     * - `Permissions`:
     *
     *   For vector buckets and vector indexes, you must have the `s3vectors:ListTagsForResource` permission to use this
     *   operation.
     *
     * [^1]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/tagging.html#manage-tags
     *
     * @see https://docs.aws.amazon.com/AmazonS3/latest/API/API_Operations_Amazon_S3_Vectors.html/API_ListTagsForResource.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-s3vectors-2025-07-15.html#listtagsforresource
     *
     * @param array{
     *   resourceArn: string,
     *   '@region'?: string|null,
     * }|ListTagsForResourceInput $input
     *
     * @throws AccessDeniedException
     * @throws InternalServerException
     * @throws NotFoundException
     * @throws RequestTimeoutException
     * @throws ServiceUnavailableException
     * @throws TooManyRequestsException
     * @throws ValidationException
     */
    public function listTagsForResource($input): ListTagsForResourceOutput
    {
        $input = ListTagsForResourceInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'ListTagsForResource', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'AccessDeniedException' => AccessDeniedException::class,
            'InternalServerException' => InternalServerException::class,
            'NotFoundException' => NotFoundException::class,
            'RequestTimeoutException' => RequestTimeoutException::class,
            'ServiceUnavailableException' => ServiceUnavailableException::class,
            'TooManyRequestsException' => TooManyRequestsException::class,
            'ValidationException' => ValidationException::class,
        ]]));

        return new ListTagsForResourceOutput($response);
    }

    /**
     * Returns a list of all the vector buckets that are owned by the authenticated sender of the request.
     *
     * - `Permissions`:
     *
     *   You must have the `s3vectors:ListVectorBuckets` permission to use this operation.
     *
     * @see https://docs.aws.amazon.com/AmazonS3/latest/API/API_Operations_Amazon_S3_Vectors.html/API_ListVectorBuckets.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-s3vectors-2025-07-15.html#listvectorbuckets
     *
     * @param array{
     *   maxResults?: int|null,
     *   nextToken?: string|null,
     *   prefix?: string|null,
     *   '@region'?: string|null,
     * }|ListVectorBucketsInput $input
     *
     * @throws AccessDeniedException
     * @throws InternalServerException
     * @throws RequestTimeoutException
     * @throws ServiceUnavailableException
     * @throws TooManyRequestsException
     * @throws ValidationException
     */
    public function listVectorBuckets($input = []): ListVectorBucketsOutput
    {
        $input = ListVectorBucketsInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'ListVectorBuckets', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'AccessDeniedException' => AccessDeniedException::class,
            'InternalServerException' => InternalServerException::class,
            'RequestTimeoutException' => RequestTimeoutException::class,
            'ServiceUnavailableException' => ServiceUnavailableException::class,
            'TooManyRequestsException' => TooManyRequestsException::class,
            'ValidationException' => ValidationException::class,
        ]]));

        return new ListVectorBucketsOutput($response, $this, $input);
    }

    /**
     * Creates a bucket policy for a vector bucket. To specify the bucket, you must use either the vector bucket name or the
     * vector bucket Amazon Resource Name (ARN).
     *
     * - `Permissions`:
     *
     *   You must have the `s3vectors:PutVectorBucketPolicy` permission to use this operation.
     *
     * @see https://docs.aws.amazon.com/AmazonS3/latest/API/API_Operations_Amazon_S3_Vectors.html/API_PutVectorBucketPolicy.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-s3vectors-2025-07-15.html#putvectorbucketpolicy
     *
     * @param array{
     *   vectorBucketName?: string|null,
     *   vectorBucketArn?: string|null,
     *   policy: string,
     *   '@region'?: string|null,
     * }|PutVectorBucketPolicyInput $input
     *
     * @throws AccessDeniedException
     * @throws InternalServerException
     * @throws NotFoundException
     * @throws RequestTimeoutException
     * @throws ServiceUnavailableException
     * @throws TooManyRequestsException
     * @throws ValidationException
     */
    public function putVectorBucketPolicy($input): PutVectorBucketPolicyOutput
    {
        $input = PutVectorBucketPolicyInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'PutVectorBucketPolicy', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'AccessDeniedException' => AccessDeniedException::class,
            'InternalServerException' => InternalServerException::class,
            'NotFoundException' => NotFoundException::class,
            'RequestTimeoutException' => RequestTimeoutException::class,
            'ServiceUnavailableException' => ServiceUnavailableException::class,
            'TooManyRequestsException' => TooManyRequestsException::class,
            'ValidationException' => ValidationException::class,
        ]]));

        return new PutVectorBucketPolicyOutput($response);
    }

    /**
     * Applies one or more user-defined tags to an Amazon S3 Vectors resource or updates existing tags. Each tag is a label
     * consisting of a key and value pair. Tags can help you organize, track costs for, and control access to your
     * resources. You can add up to 50 tags for each resource.
     *
     * > For a list of S3 resources that support tagging, see Managing tags for Amazon S3 resources [^1].
     *
     * - `Permissions`:
     *
     *   For vector buckets and vector indexes, you must have the `s3vectors:TagResource` permission to use this operation.
     *
     * [^1]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/tagging.html#manage-tags
     *
     * @see https://docs.aws.amazon.com/AmazonS3/latest/API/API_Operations_Amazon_S3_Vectors.html/API_TagResource.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-s3vectors-2025-07-15.html#tagresource
     *
     * @param array{
     *   resourceArn: string,
     *   tags: array<string, string>,
     *   '@region'?: string|null,
     * }|TagResourceInput $input
     *
     * @throws AccessDeniedException
     * @throws ConflictException
     * @throws InternalServerException
     * @throws NotFoundException
     * @throws RequestTimeoutException
     * @throws ServiceUnavailableException
     * @throws TooManyRequestsException
     * @throws ValidationException
     */
    public function tagResource($input): TagResourceOutput
    {
        $input = TagResourceInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'TagResource', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'AccessDeniedException' => AccessDeniedException::class,
            'ConflictException' => ConflictException::class,
            'InternalServerException' => InternalServerException::class,
            'NotFoundException' => NotFoundException::class,
            'RequestTimeoutException' => RequestTimeoutException::class,
            'ServiceUnavailableException' => ServiceUnavailableException::class,
            'TooManyRequestsException' => TooManyRequestsException::class,
            'ValidationException' => ValidationException::class,
        ]]));

        return new TagResourceOutput($response);
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
            'endpoint' => "https://s3vectors.$region.amazonaws.com",
            'signRegion' => $region,
            'signService' => 's3vectors',
            'signVersions' => ['v4'],
        ];
    }
}
