<?php

namespace AsyncAws\Rekognition;

use AsyncAws\Core\AbstractApi;
use AsyncAws\Core\AwsError\AwsErrorFactoryInterface;
use AsyncAws\Core\AwsError\JsonRpcAwsErrorFactory;
use AsyncAws\Core\Configuration;
use AsyncAws\Core\RequestContext;
use AsyncAws\Rekognition\Enum\Attribute;
use AsyncAws\Rekognition\Enum\QualityFilter;
use AsyncAws\Rekognition\Exception\AccessDeniedException;
use AsyncAws\Rekognition\Exception\ImageTooLargeException;
use AsyncAws\Rekognition\Exception\InternalServerErrorException;
use AsyncAws\Rekognition\Exception\InvalidImageFormatException;
use AsyncAws\Rekognition\Exception\InvalidPaginationTokenException;
use AsyncAws\Rekognition\Exception\InvalidParameterException;
use AsyncAws\Rekognition\Exception\InvalidS3ObjectException;
use AsyncAws\Rekognition\Exception\LimitExceededException;
use AsyncAws\Rekognition\Exception\ProvisionedThroughputExceededException;
use AsyncAws\Rekognition\Exception\ResourceAlreadyExistsException;
use AsyncAws\Rekognition\Exception\ResourceInUseException;
use AsyncAws\Rekognition\Exception\ResourceNotFoundException;
use AsyncAws\Rekognition\Exception\ServiceQuotaExceededException;
use AsyncAws\Rekognition\Exception\ThrottlingException;
use AsyncAws\Rekognition\Input\CreateCollectionRequest;
use AsyncAws\Rekognition\Input\CreateProjectRequest;
use AsyncAws\Rekognition\Input\DeleteCollectionRequest;
use AsyncAws\Rekognition\Input\DeleteProjectRequest;
use AsyncAws\Rekognition\Input\DetectFacesRequest;
use AsyncAws\Rekognition\Input\IndexFacesRequest;
use AsyncAws\Rekognition\Input\ListCollectionsRequest;
use AsyncAws\Rekognition\Input\SearchFacesByImageRequest;
use AsyncAws\Rekognition\Result\CreateCollectionResponse;
use AsyncAws\Rekognition\Result\CreateProjectResponse;
use AsyncAws\Rekognition\Result\DeleteCollectionResponse;
use AsyncAws\Rekognition\Result\DeleteProjectResponse;
use AsyncAws\Rekognition\Result\DetectFacesResponse;
use AsyncAws\Rekognition\Result\IndexFacesResponse;
use AsyncAws\Rekognition\Result\ListCollectionsResponse;
use AsyncAws\Rekognition\Result\SearchFacesByImageResponse;
use AsyncAws\Rekognition\ValueObject\Image;

class RekognitionClient extends AbstractApi
{
    /**
     * Creates a collection in an AWS Region. You can add faces to the collection using the IndexFaces operation.
     *
     * @see https://docs.aws.amazon.com/rekognition/latest/dg/API_CreateCollection.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-rekognition-2016-06-27.html#createcollection
     *
     * @param array{
     *   CollectionId: string,
     *   @region?: string,
     * }|CreateCollectionRequest $input
     *
     * @throws InvalidParameterException
     * @throws AccessDeniedException
     * @throws InternalServerErrorException
     * @throws ThrottlingException
     * @throws ProvisionedThroughputExceededException
     * @throws ResourceAlreadyExistsException
     */
    public function createCollection($input): CreateCollectionResponse
    {
        $input = CreateCollectionRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'CreateCollection', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'InvalidParameterException' => InvalidParameterException::class,
            'AccessDeniedException' => AccessDeniedException::class,
            'InternalServerError' => InternalServerErrorException::class,
            'ThrottlingException' => ThrottlingException::class,
            'ProvisionedThroughputExceededException' => ProvisionedThroughputExceededException::class,
            'ResourceAlreadyExistsException' => ResourceAlreadyExistsException::class,
        ]]));

        return new CreateCollectionResponse($response);
    }

    /**
     * Creates a new Amazon Rekognition Custom Labels project. A project is a logical grouping of resources (images, Labels,
     * models) and operations (training, evaluation and detection).
     *
     * @see https://docs.aws.amazon.com/rekognition/latest/dg/API_CreateProject.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-rekognition-2016-06-27.html#createproject
     *
     * @param array{
     *   ProjectName: string,
     *   @region?: string,
     * }|CreateProjectRequest $input
     *
     * @throws ResourceInUseException
     * @throws LimitExceededException
     * @throws InvalidParameterException
     * @throws AccessDeniedException
     * @throws InternalServerErrorException
     * @throws ThrottlingException
     * @throws ProvisionedThroughputExceededException
     */
    public function createProject($input): CreateProjectResponse
    {
        $input = CreateProjectRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'CreateProject', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'ResourceInUseException' => ResourceInUseException::class,
            'LimitExceededException' => LimitExceededException::class,
            'InvalidParameterException' => InvalidParameterException::class,
            'AccessDeniedException' => AccessDeniedException::class,
            'InternalServerError' => InternalServerErrorException::class,
            'ThrottlingException' => ThrottlingException::class,
            'ProvisionedThroughputExceededException' => ProvisionedThroughputExceededException::class,
        ]]));

        return new CreateProjectResponse($response);
    }

    /**
     * Deletes the specified collection. Note that this operation removes all faces in the collection. For an example, see
     * delete-collection-procedure.
     *
     * @see https://docs.aws.amazon.com/rekognition/latest/dg/API_DeleteCollection.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-rekognition-2016-06-27.html#deletecollection
     *
     * @param array{
     *   CollectionId: string,
     *   @region?: string,
     * }|DeleteCollectionRequest $input
     *
     * @throws InvalidParameterException
     * @throws AccessDeniedException
     * @throws InternalServerErrorException
     * @throws ThrottlingException
     * @throws ProvisionedThroughputExceededException
     * @throws ResourceNotFoundException
     */
    public function deleteCollection($input): DeleteCollectionResponse
    {
        $input = DeleteCollectionRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'DeleteCollection', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'InvalidParameterException' => InvalidParameterException::class,
            'AccessDeniedException' => AccessDeniedException::class,
            'InternalServerError' => InternalServerErrorException::class,
            'ThrottlingException' => ThrottlingException::class,
            'ProvisionedThroughputExceededException' => ProvisionedThroughputExceededException::class,
            'ResourceNotFoundException' => ResourceNotFoundException::class,
        ]]));

        return new DeleteCollectionResponse($response);
    }

    /**
     * Deletes an Amazon Rekognition Custom Labels project. To delete a project you must first delete all models associated
     * with the project. To delete a model, see DeleteProjectVersion.
     *
     * @see https://docs.aws.amazon.com/rekognition/latest/dg/API_DeleteProject.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-rekognition-2016-06-27.html#deleteproject
     *
     * @param array{
     *   ProjectArn: string,
     *   @region?: string,
     * }|DeleteProjectRequest $input
     *
     * @throws ResourceInUseException
     * @throws ResourceNotFoundException
     * @throws InvalidParameterException
     * @throws AccessDeniedException
     * @throws InternalServerErrorException
     * @throws ThrottlingException
     * @throws ProvisionedThroughputExceededException
     */
    public function deleteProject($input): DeleteProjectResponse
    {
        $input = DeleteProjectRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'DeleteProject', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'ResourceInUseException' => ResourceInUseException::class,
            'ResourceNotFoundException' => ResourceNotFoundException::class,
            'InvalidParameterException' => InvalidParameterException::class,
            'AccessDeniedException' => AccessDeniedException::class,
            'InternalServerError' => InternalServerErrorException::class,
            'ThrottlingException' => ThrottlingException::class,
            'ProvisionedThroughputExceededException' => ProvisionedThroughputExceededException::class,
        ]]));

        return new DeleteProjectResponse($response);
    }

    /**
     * Detects faces within an image that is provided as input.
     *
     * @see https://docs.aws.amazon.com/rekognition/latest/dg/API_DetectFaces.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-rekognition-2016-06-27.html#detectfaces
     *
     * @param array{
     *   Image: Image|array,
     *   Attributes?: list<Attribute::*>,
     *   @region?: string,
     * }|DetectFacesRequest $input
     *
     * @throws InvalidS3ObjectException
     * @throws InvalidParameterException
     * @throws ImageTooLargeException
     * @throws AccessDeniedException
     * @throws InternalServerErrorException
     * @throws ThrottlingException
     * @throws ProvisionedThroughputExceededException
     * @throws InvalidImageFormatException
     */
    public function detectFaces($input): DetectFacesResponse
    {
        $input = DetectFacesRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'DetectFaces', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'InvalidS3ObjectException' => InvalidS3ObjectException::class,
            'InvalidParameterException' => InvalidParameterException::class,
            'ImageTooLargeException' => ImageTooLargeException::class,
            'AccessDeniedException' => AccessDeniedException::class,
            'InternalServerError' => InternalServerErrorException::class,
            'ThrottlingException' => ThrottlingException::class,
            'ProvisionedThroughputExceededException' => ProvisionedThroughputExceededException::class,
            'InvalidImageFormatException' => InvalidImageFormatException::class,
        ]]));

        return new DetectFacesResponse($response);
    }

    /**
     * Detects faces in the input image and adds them to the specified collection.
     *
     * @see https://docs.aws.amazon.com/rekognition/latest/dg/API_IndexFaces.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-rekognition-2016-06-27.html#indexfaces
     *
     * @param array{
     *   CollectionId: string,
     *   Image: Image|array,
     *   ExternalImageId?: string,
     *   DetectionAttributes?: list<Attribute::*>,
     *   MaxFaces?: int,
     *   QualityFilter?: QualityFilter::*,
     *   @region?: string,
     * }|IndexFacesRequest $input
     *
     * @throws InvalidS3ObjectException
     * @throws InvalidParameterException
     * @throws ImageTooLargeException
     * @throws AccessDeniedException
     * @throws InternalServerErrorException
     * @throws ThrottlingException
     * @throws ProvisionedThroughputExceededException
     * @throws ResourceNotFoundException
     * @throws InvalidImageFormatException
     * @throws ServiceQuotaExceededException
     */
    public function indexFaces($input): IndexFacesResponse
    {
        $input = IndexFacesRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'IndexFaces', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'InvalidS3ObjectException' => InvalidS3ObjectException::class,
            'InvalidParameterException' => InvalidParameterException::class,
            'ImageTooLargeException' => ImageTooLargeException::class,
            'AccessDeniedException' => AccessDeniedException::class,
            'InternalServerError' => InternalServerErrorException::class,
            'ThrottlingException' => ThrottlingException::class,
            'ProvisionedThroughputExceededException' => ProvisionedThroughputExceededException::class,
            'ResourceNotFoundException' => ResourceNotFoundException::class,
            'InvalidImageFormatException' => InvalidImageFormatException::class,
            'ServiceQuotaExceededException' => ServiceQuotaExceededException::class,
        ]]));

        return new IndexFacesResponse($response);
    }

    /**
     * Returns list of collection IDs in your account. If the result is truncated, the response also provides a `NextToken`
     * that you can use in the subsequent request to fetch the next set of collection IDs.
     *
     * @see https://docs.aws.amazon.com/rekognition/latest/dg/API_ListCollections.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-rekognition-2016-06-27.html#listcollections
     *
     * @param array{
     *   NextToken?: string,
     *   MaxResults?: int,
     *   @region?: string,
     * }|ListCollectionsRequest $input
     *
     * @throws InvalidParameterException
     * @throws AccessDeniedException
     * @throws InternalServerErrorException
     * @throws ThrottlingException
     * @throws ProvisionedThroughputExceededException
     * @throws InvalidPaginationTokenException
     * @throws ResourceNotFoundException
     */
    public function listCollections($input = []): ListCollectionsResponse
    {
        $input = ListCollectionsRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'ListCollections', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'InvalidParameterException' => InvalidParameterException::class,
            'AccessDeniedException' => AccessDeniedException::class,
            'InternalServerError' => InternalServerErrorException::class,
            'ThrottlingException' => ThrottlingException::class,
            'ProvisionedThroughputExceededException' => ProvisionedThroughputExceededException::class,
            'InvalidPaginationTokenException' => InvalidPaginationTokenException::class,
            'ResourceNotFoundException' => ResourceNotFoundException::class,
        ]]));

        return new ListCollectionsResponse($response, $this, $input);
    }

    /**
     * For a given input image, first detects the largest face in the image, and then searches the specified collection for
     * matching faces. The operation compares the features of the input face with faces in the specified collection.
     *
     * @see https://docs.aws.amazon.com/rekognition/latest/dg/API_SearchFacesByImage.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-rekognition-2016-06-27.html#searchfacesbyimage
     *
     * @param array{
     *   CollectionId: string,
     *   Image: Image|array,
     *   MaxFaces?: int,
     *   FaceMatchThreshold?: float,
     *   QualityFilter?: QualityFilter::*,
     *   @region?: string,
     * }|SearchFacesByImageRequest $input
     *
     * @throws InvalidS3ObjectException
     * @throws InvalidParameterException
     * @throws ImageTooLargeException
     * @throws AccessDeniedException
     * @throws InternalServerErrorException
     * @throws ThrottlingException
     * @throws ProvisionedThroughputExceededException
     * @throws ResourceNotFoundException
     * @throws InvalidImageFormatException
     */
    public function searchFacesByImage($input): SearchFacesByImageResponse
    {
        $input = SearchFacesByImageRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'SearchFacesByImage', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'InvalidS3ObjectException' => InvalidS3ObjectException::class,
            'InvalidParameterException' => InvalidParameterException::class,
            'ImageTooLargeException' => ImageTooLargeException::class,
            'AccessDeniedException' => AccessDeniedException::class,
            'InternalServerError' => InternalServerErrorException::class,
            'ThrottlingException' => ThrottlingException::class,
            'ProvisionedThroughputExceededException' => ProvisionedThroughputExceededException::class,
            'ResourceNotFoundException' => ResourceNotFoundException::class,
            'InvalidImageFormatException' => InvalidImageFormatException::class,
        ]]));

        return new SearchFacesByImageResponse($response);
    }

    protected function getAwsErrorFactory(): AwsErrorFactoryInterface
    {
        return new JsonRpcAwsErrorFactory();
    }

    protected function getEndpointMetadata(?string $region): array
    {
        if (null === $region) {
            $region = Configuration::DEFAULT_REGION;
        }

        switch ($region) {
            case 'us-gov-west-1':
                return [
                    'endpoint' => "https://rekognition.$region.amazonaws.com",
                    'signRegion' => $region,
                    'signService' => 'rekognition',
                    'signVersions' => ['v4'],
                ];
            case 'rekognition-fips.ca-central-1':
                return [
                    'endpoint' => 'https://rekognition-fips.ca-central-1.amazonaws.com',
                    'signRegion' => 'ca-central-1',
                    'signService' => 'rekognition',
                    'signVersions' => ['v4'],
                ];
            case 'rekognition-fips.us-east-1':
                return [
                    'endpoint' => 'https://rekognition-fips.us-east-1.amazonaws.com',
                    'signRegion' => 'us-east-1',
                    'signService' => 'rekognition',
                    'signVersions' => ['v4'],
                ];
            case 'rekognition-fips.us-east-2':
                return [
                    'endpoint' => 'https://rekognition-fips.us-east-2.amazonaws.com',
                    'signRegion' => 'us-east-2',
                    'signService' => 'rekognition',
                    'signVersions' => ['v4'],
                ];
            case 'rekognition-fips.us-gov-west-1':
                return [
                    'endpoint' => 'https://rekognition-fips.us-gov-west-1.amazonaws.com',
                    'signRegion' => 'us-gov-west-1',
                    'signService' => 'rekognition',
                    'signVersions' => ['v4'],
                ];
            case 'rekognition-fips.us-west-1':
                return [
                    'endpoint' => 'https://rekognition-fips.us-west-1.amazonaws.com',
                    'signRegion' => 'us-west-1',
                    'signService' => 'rekognition',
                    'signVersions' => ['v4'],
                ];
            case 'rekognition-fips.us-west-2':
                return [
                    'endpoint' => 'https://rekognition-fips.us-west-2.amazonaws.com',
                    'signRegion' => 'us-west-2',
                    'signService' => 'rekognition',
                    'signVersions' => ['v4'],
                ];
        }

        return [
            'endpoint' => "https://rekognition.$region.amazonaws.com",
            'signRegion' => $region,
            'signService' => 'rekognition',
            'signVersions' => ['v4'],
        ];
    }
}
