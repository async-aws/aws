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
use AsyncAws\S3Vectors\Input\DeleteVectorsInput;
use AsyncAws\S3Vectors\Input\GetIndexInput;
use AsyncAws\S3Vectors\Input\GetVectorBucketInput;
use AsyncAws\S3Vectors\Input\GetVectorsInput;
use AsyncAws\S3Vectors\Input\ListIndexesInput;
use AsyncAws\S3Vectors\Input\ListVectorBucketsInput;
use AsyncAws\S3Vectors\Input\ListVectorsInput;
use AsyncAws\S3Vectors\Input\PutVectorsInput;
use AsyncAws\S3Vectors\Input\QueryVectorsInput;
use AsyncAws\S3Vectors\Result\CreateIndexOutput;
use AsyncAws\S3Vectors\Result\CreateVectorBucketOutput;
use AsyncAws\S3Vectors\Result\DeleteIndexOutput;
use AsyncAws\S3Vectors\Result\DeleteVectorBucketOutput;
use AsyncAws\S3Vectors\Result\DeleteVectorsOutput;
use AsyncAws\S3Vectors\Result\GetIndexOutput;
use AsyncAws\S3Vectors\Result\GetVectorBucketOutput;
use AsyncAws\S3Vectors\Result\GetVectorsOutput;
use AsyncAws\S3Vectors\Result\ListIndexesOutput;
use AsyncAws\S3Vectors\Result\ListVectorBucketsOutput;
use AsyncAws\S3Vectors\Result\ListVectorsOutput;
use AsyncAws\S3Vectors\Result\PutVectorsOutput;
use AsyncAws\S3Vectors\Result\QueryVectorsOutput;
use AsyncAws\S3Vectors\ValueObject\EncryptionConfiguration;
use AsyncAws\S3Vectors\ValueObject\MetadataConfiguration;
use AsyncAws\S3Vectors\ValueObject\PutInputVector;
use AsyncAws\S3Vectors\ValueObject\VectorData;

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
     * Returns vector attributes. To specify the vector index, you can either use both the vector bucket name and the vector
     * index name, or use the vector index Amazon Resource Name (ARN).
     *
     * - `Permissions`:
     *
     *   You must have the `s3vectors:GetVectors` permission to use this operation.
     *
     * @see https://docs.aws.amazon.com/AmazonS3/latest/API/API_Operations_Amazon_S3_Vectors.html/API_GetVectors.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-s3vectors-2025-07-15.html#getvectors
     *
     * @param array{
     *   vectorBucketName?: string|null,
     *   indexName?: string|null,
     *   indexArn?: string|null,
     *   keys: string[],
     *   returnData?: bool|null,
     *   returnMetadata?: bool|null,
     *   '@region'?: string|null,
     * }|GetVectorsInput $input
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
    public function getVectors($input): GetVectorsOutput
    {
        $input = GetVectorsInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'GetVectors', 'region' => $input->getRegion(), 'exceptionMapping' => [
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

        return new GetVectorsOutput($response);
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
     * List vectors in the specified vector index. To specify the vector index, you can either use both the vector bucket
     * name and the vector index name, or use the vector index Amazon Resource Name (ARN).
     *
     * `ListVectors` operations proceed sequentially; however, for faster performance on a large number of vectors in a
     * vector index, applications can request a parallel `ListVectors` operation by providing the `segmentCount` and
     * `segmentIndex` parameters.
     *
     * - `Permissions`:
     *
     *   You must have the `s3vectors:ListVectors` permission to use this operation. Additional permissions are required
     *   based on the request parameters you specify:
     *
     *   - With only `s3vectors:ListVectors` permission, you can list vector keys when `returnData` and `returnMetadata` are
     *     both set to false or not specified..
     *   - If you set `returnData` or `returnMetadata` to true, you must have both `s3vectors:ListVectors` and
     *     `s3vectors:GetVectors` permissions. The request fails with a `403 Forbidden` error if you request vector data or
     *     metadata without the `s3vectors:GetVectors` permission.
     *
     * @see https://docs.aws.amazon.com/AmazonS3/latest/API/API_Operations_Amazon_S3_Vectors.html/API_ListVectors.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-s3vectors-2025-07-15.html#listvectors
     *
     * @param array{
     *   vectorBucketName?: string|null,
     *   indexName?: string|null,
     *   indexArn?: string|null,
     *   maxResults?: int|null,
     *   nextToken?: string|null,
     *   segmentCount?: int|null,
     *   segmentIndex?: int|null,
     *   returnData?: bool|null,
     *   returnMetadata?: bool|null,
     *   '@region'?: string|null,
     * }|ListVectorsInput $input
     *
     * @throws AccessDeniedException
     * @throws InternalServerException
     * @throws NotFoundException
     * @throws RequestTimeoutException
     * @throws ServiceUnavailableException
     * @throws TooManyRequestsException
     * @throws ValidationException
     */
    public function listVectors($input = []): ListVectorsOutput
    {
        $input = ListVectorsInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'ListVectors', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'AccessDeniedException' => AccessDeniedException::class,
            'InternalServerException' => InternalServerException::class,
            'NotFoundException' => NotFoundException::class,
            'RequestTimeoutException' => RequestTimeoutException::class,
            'ServiceUnavailableException' => ServiceUnavailableException::class,
            'TooManyRequestsException' => TooManyRequestsException::class,
            'ValidationException' => ValidationException::class,
        ]]));

        return new ListVectorsOutput($response, $this, $input);
    }

    /**
     * Adds one or more vectors to a vector index. To specify the vector index, you can either use both the vector bucket
     * name and the vector index name, or use the vector index Amazon Resource Name (ARN).
     *
     * For more information about limits, see Limitations and restrictions [^1] in the *Amazon S3 User Guide*.
     *
     * > When inserting vector data into your vector index, you must provide the vector data as `float32` (32-bit floating
     * > point) values. If you pass higher-precision values to an Amazon Web Services SDK, S3 Vectors converts the values to
     * > 32-bit floating point before storing them, and `GetVectors`, `ListVectors`, and `QueryVectors` operations return
     * > the float32 values. Different Amazon Web Services SDKs may have different default numeric types, so ensure your
     * > vectors are properly formatted as `float32` values regardless of which SDK you're using. For example, in Python,
     * > use `numpy.float32` or explicitly cast your values.
     *
     * - `Permissions`:
     *
     *   You must have the `s3vectors:PutVectors` permission to use this operation.
     *
     * [^1]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/s3-vectors-limitations.html
     *
     * @see https://docs.aws.amazon.com/AmazonS3/latest/API/API_Operations_Amazon_S3_Vectors.html/API_PutVectors.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-s3vectors-2025-07-15.html#putvectors
     *
     * @param array{
     *   vectorBucketName?: string|null,
     *   indexName?: string|null,
     *   indexArn?: string|null,
     *   vectors: array<PutInputVector|array>,
     *   '@region'?: string|null,
     * }|PutVectorsInput $input
     *
     * @throws AccessDeniedException
     * @throws InternalServerException
     * @throws KmsDisabledException
     * @throws KmsInvalidKeyUsageException
     * @throws KmsInvalidStateException
     * @throws KmsNotFoundException
     * @throws NotFoundException
     * @throws RequestTimeoutException
     * @throws ServiceQuotaExceededException
     * @throws ServiceUnavailableException
     * @throws TooManyRequestsException
     * @throws ValidationException
     */
    public function putVectors($input): PutVectorsOutput
    {
        $input = PutVectorsInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'PutVectors', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'AccessDeniedException' => AccessDeniedException::class,
            'InternalServerException' => InternalServerException::class,
            'KmsDisabledException' => KmsDisabledException::class,
            'KmsInvalidKeyUsageException' => KmsInvalidKeyUsageException::class,
            'KmsInvalidStateException' => KmsInvalidStateException::class,
            'KmsNotFoundException' => KmsNotFoundException::class,
            'NotFoundException' => NotFoundException::class,
            'RequestTimeoutException' => RequestTimeoutException::class,
            'ServiceQuotaExceededException' => ServiceQuotaExceededException::class,
            'ServiceUnavailableException' => ServiceUnavailableException::class,
            'TooManyRequestsException' => TooManyRequestsException::class,
            'ValidationException' => ValidationException::class,
        ]]));

        return new PutVectorsOutput($response);
    }

    /**
     * Performs an approximate nearest neighbor search query in a vector index using a query vector. By default, it returns
     * the keys of approximate nearest neighbors. You can optionally include the computed distance (between the query vector
     * and each vector in the response), the vector data, and metadata of each vector in the response.
     *
     * To specify the vector index, you can either use both the vector bucket name and the vector index name, or use the
     * vector index Amazon Resource Name (ARN).
     *
     * - `Permissions`:
     *
     *   You must have the `s3vectors:QueryVectors` permission to use this operation. Additional permissions are required
     *   based on the request parameters you specify:
     *
     *   - With only `s3vectors:QueryVectors` permission, you can retrieve vector keys of approximate nearest neighbors and
     *     computed distances between these vectors. This permission is sufficient only when you don't set any metadata
     *     filters and don't request vector data or metadata (by keeping the `returnMetadata` parameter set to `false` or
     *     not specified).
     *   - If you specify a metadata filter or set `returnMetadata` to true, you must have both `s3vectors:QueryVectors` and
     *     `s3vectors:GetVectors` permissions. The request fails with a `403 Forbidden error` if you request metadata
     *     filtering, vector data, or metadata without the `s3vectors:GetVectors` permission.
     *
     * @see https://docs.aws.amazon.com/AmazonS3/latest/API/API_Operations_Amazon_S3_Vectors.html/API_QueryVectors.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-s3vectors-2025-07-15.html#queryvectors
     *
     * @param array{
     *   vectorBucketName?: string|null,
     *   indexName?: string|null,
     *   indexArn?: string|null,
     *   topK: int,
     *   queryVector: VectorData|array,
     *   filter?: bool|string|int|float|list<mixed>|array<string, mixed>|null|null,
     *   returnMetadata?: bool|null,
     *   returnDistance?: bool|null,
     *   '@region'?: string|null,
     * }|QueryVectorsInput $input
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
    public function queryVectors($input): QueryVectorsOutput
    {
        $input = QueryVectorsInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'QueryVectors', 'region' => $input->getRegion(), 'exceptionMapping' => [
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

        return new QueryVectorsOutput($response);
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
