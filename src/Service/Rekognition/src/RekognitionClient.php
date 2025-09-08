<?php

namespace AsyncAws\Rekognition;

use AsyncAws\Core\AbstractApi;
use AsyncAws\Core\AwsError\AwsErrorFactoryInterface;
use AsyncAws\Core\AwsError\JsonRpcAwsErrorFactory;
use AsyncAws\Core\Configuration;
use AsyncAws\Core\Exception\UnsupportedRegion;
use AsyncAws\Core\RequestContext;
use AsyncAws\Rekognition\Enum\Attribute;
use AsyncAws\Rekognition\Enum\CustomizationFeature;
use AsyncAws\Rekognition\Enum\ProjectAutoUpdate;
use AsyncAws\Rekognition\Enum\QualityFilter;
use AsyncAws\Rekognition\Exception\AccessDeniedException;
use AsyncAws\Rekognition\Exception\HumanLoopQuotaExceededException;
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
use AsyncAws\Rekognition\Exception\ResourceNotReadyException;
use AsyncAws\Rekognition\Exception\ServiceQuotaExceededException;
use AsyncAws\Rekognition\Exception\ThrottlingException;
use AsyncAws\Rekognition\Input\CreateCollectionRequest;
use AsyncAws\Rekognition\Input\CreateProjectRequest;
use AsyncAws\Rekognition\Input\DeleteCollectionRequest;
use AsyncAws\Rekognition\Input\DeleteProjectRequest;
use AsyncAws\Rekognition\Input\DetectFacesRequest;
use AsyncAws\Rekognition\Input\DetectModerationLabelsRequest;
use AsyncAws\Rekognition\Input\GetCelebrityInfoRequest;
use AsyncAws\Rekognition\Input\IndexFacesRequest;
use AsyncAws\Rekognition\Input\ListCollectionsRequest;
use AsyncAws\Rekognition\Input\RecognizeCelebritiesRequest;
use AsyncAws\Rekognition\Input\SearchFacesByImageRequest;
use AsyncAws\Rekognition\Result\CreateCollectionResponse;
use AsyncAws\Rekognition\Result\CreateProjectResponse;
use AsyncAws\Rekognition\Result\DeleteCollectionResponse;
use AsyncAws\Rekognition\Result\DeleteProjectResponse;
use AsyncAws\Rekognition\Result\DetectFacesResponse;
use AsyncAws\Rekognition\Result\DetectModerationLabelsResponse;
use AsyncAws\Rekognition\Result\GetCelebrityInfoResponse;
use AsyncAws\Rekognition\Result\IndexFacesResponse;
use AsyncAws\Rekognition\Result\ListCollectionsResponse;
use AsyncAws\Rekognition\Result\RecognizeCelebritiesResponse;
use AsyncAws\Rekognition\Result\SearchFacesByImageResponse;
use AsyncAws\Rekognition\ValueObject\HumanLoopConfig;
use AsyncAws\Rekognition\ValueObject\Image;

class RekognitionClient extends AbstractApi
{
    /**
     * Creates a collection in an AWS Region. You can add faces to the collection using the IndexFaces operation.
     *
     * For example, you might create collections, one for each of your application users. A user can then index faces using
     * the `IndexFaces` operation and persist results in a specific collection. Then, a user can search the collection for
     * faces in the user-specific container.
     *
     * When you create a collection, it is associated with the latest version of the face model version.
     *
     * > Collection names are case-sensitive.
     *
     * This operation requires permissions to perform the `rekognition:CreateCollection` action. If you want to tag your
     * collection, you also require permission to perform the `rekognition:TagResource` operation.
     *
     * @see https://docs.aws.amazon.com/rekognition/latest/dg/API_CreateCollection.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-rekognition-2016-06-27.html#createcollection
     *
     * @param array{
     *   CollectionId: string,
     *   Tags?: array<string, string>|null,
     *   '@region'?: string|null,
     * }|CreateCollectionRequest $input
     *
     * @throws AccessDeniedException
     * @throws InternalServerErrorException
     * @throws InvalidParameterException
     * @throws ProvisionedThroughputExceededException
     * @throws ResourceAlreadyExistsException
     * @throws ServiceQuotaExceededException
     * @throws ThrottlingException
     */
    public function createCollection($input): CreateCollectionResponse
    {
        $input = CreateCollectionRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'CreateCollection', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'AccessDeniedException' => AccessDeniedException::class,
            'InternalServerError' => InternalServerErrorException::class,
            'InvalidParameterException' => InvalidParameterException::class,
            'ProvisionedThroughputExceededException' => ProvisionedThroughputExceededException::class,
            'ResourceAlreadyExistsException' => ResourceAlreadyExistsException::class,
            'ServiceQuotaExceededException' => ServiceQuotaExceededException::class,
            'ThrottlingException' => ThrottlingException::class,
        ]]));

        return new CreateCollectionResponse($response);
    }

    /**
     * Creates a new Amazon Rekognition project. A project is a group of resources (datasets, model versions) that you use
     * to create and manage a Amazon Rekognition Custom Labels Model or custom adapter. You can specify a feature to create
     * the project with, if no feature is specified then Custom Labels is used by default. For adapters, you can also choose
     * whether or not to have the project auto update by using the AutoUpdate argument. This operation requires permissions
     * to perform the `rekognition:CreateProject` action.
     *
     * @see https://docs.aws.amazon.com/rekognition/latest/dg/API_CreateProject.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-rekognition-2016-06-27.html#createproject
     *
     * @param array{
     *   ProjectName: string,
     *   Feature?: CustomizationFeature::*|null,
     *   AutoUpdate?: ProjectAutoUpdate::*|null,
     *   Tags?: array<string, string>|null,
     *   '@region'?: string|null,
     * }|CreateProjectRequest $input
     *
     * @throws AccessDeniedException
     * @throws InternalServerErrorException
     * @throws InvalidParameterException
     * @throws LimitExceededException
     * @throws ProvisionedThroughputExceededException
     * @throws ResourceInUseException
     * @throws ThrottlingException
     */
    public function createProject($input): CreateProjectResponse
    {
        $input = CreateProjectRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'CreateProject', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'AccessDeniedException' => AccessDeniedException::class,
            'InternalServerError' => InternalServerErrorException::class,
            'InvalidParameterException' => InvalidParameterException::class,
            'LimitExceededException' => LimitExceededException::class,
            'ProvisionedThroughputExceededException' => ProvisionedThroughputExceededException::class,
            'ResourceInUseException' => ResourceInUseException::class,
            'ThrottlingException' => ThrottlingException::class,
        ]]));

        return new CreateProjectResponse($response);
    }

    /**
     * Deletes the specified collection. Note that this operation removes all faces in the collection. For an example, see
     * Deleting a collection [^1].
     *
     * This operation requires permissions to perform the `rekognition:DeleteCollection` action.
     *
     * [^1]: https://docs.aws.amazon.com/rekognition/latest/dg/delete-collection-procedure.html
     *
     * @see https://docs.aws.amazon.com/rekognition/latest/dg/API_DeleteCollection.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-rekognition-2016-06-27.html#deletecollection
     *
     * @param array{
     *   CollectionId: string,
     *   '@region'?: string|null,
     * }|DeleteCollectionRequest $input
     *
     * @throws AccessDeniedException
     * @throws InternalServerErrorException
     * @throws InvalidParameterException
     * @throws ProvisionedThroughputExceededException
     * @throws ResourceNotFoundException
     * @throws ThrottlingException
     */
    public function deleteCollection($input): DeleteCollectionResponse
    {
        $input = DeleteCollectionRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'DeleteCollection', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'AccessDeniedException' => AccessDeniedException::class,
            'InternalServerError' => InternalServerErrorException::class,
            'InvalidParameterException' => InvalidParameterException::class,
            'ProvisionedThroughputExceededException' => ProvisionedThroughputExceededException::class,
            'ResourceNotFoundException' => ResourceNotFoundException::class,
            'ThrottlingException' => ThrottlingException::class,
        ]]));

        return new DeleteCollectionResponse($response);
    }

    /**
     * Deletes a Amazon Rekognition project. To delete a project you must first delete all models or adapters associated
     * with the project. To delete a model or adapter, see DeleteProjectVersion.
     *
     * `DeleteProject` is an asynchronous operation. To check if the project is deleted, call DescribeProjects. The project
     * is deleted when the project no longer appears in the response. Be aware that deleting a given project will also
     * delete any `ProjectPolicies` associated with that project.
     *
     * This operation requires permissions to perform the `rekognition:DeleteProject` action.
     *
     * @see https://docs.aws.amazon.com/rekognition/latest/dg/API_DeleteProject.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-rekognition-2016-06-27.html#deleteproject
     *
     * @param array{
     *   ProjectArn: string,
     *   '@region'?: string|null,
     * }|DeleteProjectRequest $input
     *
     * @throws AccessDeniedException
     * @throws InternalServerErrorException
     * @throws InvalidParameterException
     * @throws ProvisionedThroughputExceededException
     * @throws ResourceInUseException
     * @throws ResourceNotFoundException
     * @throws ThrottlingException
     */
    public function deleteProject($input): DeleteProjectResponse
    {
        $input = DeleteProjectRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'DeleteProject', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'AccessDeniedException' => AccessDeniedException::class,
            'InternalServerError' => InternalServerErrorException::class,
            'InvalidParameterException' => InvalidParameterException::class,
            'ProvisionedThroughputExceededException' => ProvisionedThroughputExceededException::class,
            'ResourceInUseException' => ResourceInUseException::class,
            'ResourceNotFoundException' => ResourceNotFoundException::class,
            'ThrottlingException' => ThrottlingException::class,
        ]]));

        return new DeleteProjectResponse($response);
    }

    /**
     * Detects faces within an image that is provided as input.
     *
     * `DetectFaces` detects the 100 largest faces in the image. For each face detected, the operation returns face details.
     * These details include a bounding box of the face, a confidence value (that the bounding box contains a face), and a
     * fixed set of attributes such as facial landmarks (for example, coordinates of eye and mouth), pose, presence of
     * facial occlusion, and so on.
     *
     * The face-detection algorithm is most effective on frontal faces. For non-frontal or obscured faces, the algorithm
     * might not detect the faces or might detect faces with lower confidence.
     *
     * You pass the input image either as base64-encoded image bytes or as a reference to an image in an Amazon S3 bucket.
     * If you use the AWS CLI to call Amazon Rekognition operations, passing image bytes is not supported. The image must be
     * either a PNG or JPEG formatted file.
     *
     * > This is a stateless API operation. That is, the operation does not persist any data.
     *
     * This operation requires permissions to perform the `rekognition:DetectFaces` action.
     *
     * @see https://docs.aws.amazon.com/rekognition/latest/dg/API_DetectFaces.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-rekognition-2016-06-27.html#detectfaces
     *
     * @param array{
     *   Image: Image|array,
     *   Attributes?: array<Attribute::*>|null,
     *   '@region'?: string|null,
     * }|DetectFacesRequest $input
     *
     * @throws AccessDeniedException
     * @throws ImageTooLargeException
     * @throws InternalServerErrorException
     * @throws InvalidImageFormatException
     * @throws InvalidParameterException
     * @throws InvalidS3ObjectException
     * @throws ProvisionedThroughputExceededException
     * @throws ThrottlingException
     */
    public function detectFaces($input): DetectFacesResponse
    {
        $input = DetectFacesRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'DetectFaces', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'AccessDeniedException' => AccessDeniedException::class,
            'ImageTooLargeException' => ImageTooLargeException::class,
            'InternalServerError' => InternalServerErrorException::class,
            'InvalidImageFormatException' => InvalidImageFormatException::class,
            'InvalidParameterException' => InvalidParameterException::class,
            'InvalidS3ObjectException' => InvalidS3ObjectException::class,
            'ProvisionedThroughputExceededException' => ProvisionedThroughputExceededException::class,
            'ThrottlingException' => ThrottlingException::class,
        ]]));

        return new DetectFacesResponse($response);
    }

    /**
     * Detects unsafe content in a specified JPEG or PNG format image. Use `DetectModerationLabels` to moderate images
     * depending on your requirements. For example, you might want to filter images that contain nudity, but not images
     * containing suggestive content.
     *
     * To filter images, use the labels returned by `DetectModerationLabels` to determine which types of content are
     * appropriate.
     *
     * For information about moderation labels, see Detecting Unsafe Content in the Amazon Rekognition Developer Guide.
     *
     * You pass the input image either as base64-encoded image bytes or as a reference to an image in an Amazon S3 bucket.
     * If you use the AWS CLI to call Amazon Rekognition operations, passing image bytes is not supported. The image must be
     * either a PNG or JPEG formatted file.
     *
     * You can specify an adapter to use when retrieving label predictions by providing a `ProjectVersionArn` to the
     * `ProjectVersion` argument.
     *
     * @see https://docs.aws.amazon.com/rekognition/latest/dg/API_DetectModerationLabels.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-rekognition-2016-06-27.html#detectmoderationlabels
     *
     * @param array{
     *   Image: Image|array,
     *   MinConfidence?: float|null,
     *   HumanLoopConfig?: HumanLoopConfig|array|null,
     *   ProjectVersion?: string|null,
     *   '@region'?: string|null,
     * }|DetectModerationLabelsRequest $input
     *
     * @throws AccessDeniedException
     * @throws HumanLoopQuotaExceededException
     * @throws ImageTooLargeException
     * @throws InternalServerErrorException
     * @throws InvalidImageFormatException
     * @throws InvalidParameterException
     * @throws InvalidS3ObjectException
     * @throws ProvisionedThroughputExceededException
     * @throws ResourceNotFoundException
     * @throws ResourceNotReadyException
     * @throws ThrottlingException
     */
    public function detectModerationLabels($input): DetectModerationLabelsResponse
    {
        $input = DetectModerationLabelsRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'DetectModerationLabels', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'AccessDeniedException' => AccessDeniedException::class,
            'HumanLoopQuotaExceededException' => HumanLoopQuotaExceededException::class,
            'ImageTooLargeException' => ImageTooLargeException::class,
            'InternalServerError' => InternalServerErrorException::class,
            'InvalidImageFormatException' => InvalidImageFormatException::class,
            'InvalidParameterException' => InvalidParameterException::class,
            'InvalidS3ObjectException' => InvalidS3ObjectException::class,
            'ProvisionedThroughputExceededException' => ProvisionedThroughputExceededException::class,
            'ResourceNotFoundException' => ResourceNotFoundException::class,
            'ResourceNotReadyException' => ResourceNotReadyException::class,
            'ThrottlingException' => ThrottlingException::class,
        ]]));

        return new DetectModerationLabelsResponse($response);
    }

    /**
     * Gets the name and additional information about a celebrity based on their Amazon Rekognition ID. The additional
     * information is returned as an array of URLs. If there is no additional information about the celebrity, this list is
     * empty.
     *
     * For more information, see Getting information about a celebrity in the Amazon Rekognition Developer Guide.
     *
     * This operation requires permissions to perform the `rekognition:GetCelebrityInfo` action.
     *
     * @see https://docs.aws.amazon.com/rekognition/latest/dg/API_GetCelebrityInfo.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-rekognition-2016-06-27.html#getcelebrityinfo
     *
     * @param array{
     *   Id: string,
     *   '@region'?: string|null,
     * }|GetCelebrityInfoRequest $input
     *
     * @throws AccessDeniedException
     * @throws InternalServerErrorException
     * @throws InvalidParameterException
     * @throws ProvisionedThroughputExceededException
     * @throws ResourceNotFoundException
     * @throws ThrottlingException
     */
    public function getCelebrityInfo($input): GetCelebrityInfoResponse
    {
        $input = GetCelebrityInfoRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'GetCelebrityInfo', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'AccessDeniedException' => AccessDeniedException::class,
            'InternalServerError' => InternalServerErrorException::class,
            'InvalidParameterException' => InvalidParameterException::class,
            'ProvisionedThroughputExceededException' => ProvisionedThroughputExceededException::class,
            'ResourceNotFoundException' => ResourceNotFoundException::class,
            'ThrottlingException' => ThrottlingException::class,
        ]]));

        return new GetCelebrityInfoResponse($response);
    }

    /**
     * Detects faces in the input image and adds them to the specified collection.
     *
     * Amazon Rekognition doesn't save the actual faces that are detected. Instead, the underlying detection algorithm first
     * detects the faces in the input image. For each face, the algorithm extracts facial features into a feature vector,
     * and stores it in the backend database. Amazon Rekognition uses feature vectors when it performs face match and search
     * operations using the SearchFaces and SearchFacesByImage operations.
     *
     * For more information, see Adding faces to a collection in the Amazon Rekognition Developer Guide.
     *
     * To get the number of faces in a collection, call DescribeCollection.
     *
     * If you're using version 1.0 of the face detection model, `IndexFaces` indexes the 15 largest faces in the input
     * image. Later versions of the face detection model index the 100 largest faces in the input image.
     *
     * If you're using version 4 or later of the face model, image orientation information is not returned in the
     * `OrientationCorrection` field.
     *
     * To determine which version of the model you're using, call DescribeCollection and supply the collection ID. You can
     * also get the model version from the value of `FaceModelVersion` in the response from `IndexFaces`
     *
     * For more information, see Model Versioning in the Amazon Rekognition Developer Guide.
     *
     * If you provide the optional `ExternalImageId` for the input image you provided, Amazon Rekognition associates this ID
     * with all faces that it detects. When you call the ListFaces operation, the response returns the external ID. You can
     * use this external image ID to create a client-side index to associate the faces with each image. You can then use the
     * index to find all faces in an image.
     *
     * You can specify the maximum number of faces to index with the `MaxFaces` input parameter. This is useful when you
     * want to index the largest faces in an image and don't want to index smaller faces, such as those belonging to people
     * standing in the background.
     *
     * The `QualityFilter` input parameter allows you to filter out detected faces that don’t meet a required quality bar.
     * The quality bar is based on a variety of common use cases. By default, `IndexFaces` chooses the quality bar that's
     * used to filter faces. You can also explicitly choose the quality bar. Use `QualityFilter`, to set the quality bar by
     * specifying `LOW`, `MEDIUM`, or `HIGH`. If you do not want to filter detected faces, specify `NONE`.
     *
     * > To use quality filtering, you need a collection associated with version 3 of the face model or higher. To get the
     * > version of the face model associated with a collection, call DescribeCollection.
     *
     * Information about faces detected in an image, but not indexed, is returned in an array of UnindexedFace objects,
     * `UnindexedFaces`. Faces aren't indexed for reasons such as:
     *
     * - The number of faces detected exceeds the value of the `MaxFaces` request parameter.
     * - The face is too small compared to the image dimensions.
     * - The face is too blurry.
     * - The image is too dark.
     * - The face has an extreme pose.
     * - The face doesn’t have enough detail to be suitable for face search.
     *
     * In response, the `IndexFaces` operation returns an array of metadata for all detected faces, `FaceRecords`. This
     * includes:
     *
     * - The bounding box, `BoundingBox`, of the detected face.
     * - A confidence value, `Confidence`, which indicates the confidence that the bounding box contains a face.
     * - A face ID, `FaceId`, assigned by the service for each face that's detected and stored.
     * - An image ID, `ImageId`, assigned by the service for the input image.
     *
     * If you request `ALL` or specific facial attributes (e.g., `FACE_OCCLUDED`) by using the detectionAttributes
     * parameter, Amazon Rekognition returns detailed facial attributes, such as facial landmarks (for example, location of
     * eye and mouth), facial occlusion, and other facial attributes.
     *
     * If you provide the same image, specify the same collection, and use the same external ID in the `IndexFaces`
     * operation, Amazon Rekognition doesn't save duplicate face metadata.
     *
     * The input image is passed either as base64-encoded image bytes, or as a reference to an image in an Amazon S3 bucket.
     * If you use the AWS CLI to call Amazon Rekognition operations, passing image bytes isn't supported. The image must be
     * formatted as a PNG or JPEG file.
     *
     * This operation requires permissions to perform the `rekognition:IndexFaces` action.
     *
     * @see https://docs.aws.amazon.com/rekognition/latest/dg/API_IndexFaces.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-rekognition-2016-06-27.html#indexfaces
     *
     * @param array{
     *   CollectionId: string,
     *   Image: Image|array,
     *   ExternalImageId?: string|null,
     *   DetectionAttributes?: array<Attribute::*>|null,
     *   MaxFaces?: int|null,
     *   QualityFilter?: QualityFilter::*|null,
     *   '@region'?: string|null,
     * }|IndexFacesRequest $input
     *
     * @throws AccessDeniedException
     * @throws ImageTooLargeException
     * @throws InternalServerErrorException
     * @throws InvalidImageFormatException
     * @throws InvalidParameterException
     * @throws InvalidS3ObjectException
     * @throws ProvisionedThroughputExceededException
     * @throws ResourceNotFoundException
     * @throws ServiceQuotaExceededException
     * @throws ThrottlingException
     */
    public function indexFaces($input): IndexFacesResponse
    {
        $input = IndexFacesRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'IndexFaces', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'AccessDeniedException' => AccessDeniedException::class,
            'ImageTooLargeException' => ImageTooLargeException::class,
            'InternalServerError' => InternalServerErrorException::class,
            'InvalidImageFormatException' => InvalidImageFormatException::class,
            'InvalidParameterException' => InvalidParameterException::class,
            'InvalidS3ObjectException' => InvalidS3ObjectException::class,
            'ProvisionedThroughputExceededException' => ProvisionedThroughputExceededException::class,
            'ResourceNotFoundException' => ResourceNotFoundException::class,
            'ServiceQuotaExceededException' => ServiceQuotaExceededException::class,
            'ThrottlingException' => ThrottlingException::class,
        ]]));

        return new IndexFacesResponse($response);
    }

    /**
     * Returns list of collection IDs in your account. If the result is truncated, the response also provides a `NextToken`
     * that you can use in the subsequent request to fetch the next set of collection IDs.
     *
     * For an example, see Listing collections in the Amazon Rekognition Developer Guide.
     *
     * This operation requires permissions to perform the `rekognition:ListCollections` action.
     *
     * @see https://docs.aws.amazon.com/rekognition/latest/dg/API_ListCollections.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-rekognition-2016-06-27.html#listcollections
     *
     * @param array{
     *   NextToken?: string|null,
     *   MaxResults?: int|null,
     *   '@region'?: string|null,
     * }|ListCollectionsRequest $input
     *
     * @throws AccessDeniedException
     * @throws InternalServerErrorException
     * @throws InvalidPaginationTokenException
     * @throws InvalidParameterException
     * @throws ProvisionedThroughputExceededException
     * @throws ResourceNotFoundException
     * @throws ThrottlingException
     */
    public function listCollections($input = []): ListCollectionsResponse
    {
        $input = ListCollectionsRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'ListCollections', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'AccessDeniedException' => AccessDeniedException::class,
            'InternalServerError' => InternalServerErrorException::class,
            'InvalidPaginationTokenException' => InvalidPaginationTokenException::class,
            'InvalidParameterException' => InvalidParameterException::class,
            'ProvisionedThroughputExceededException' => ProvisionedThroughputExceededException::class,
            'ResourceNotFoundException' => ResourceNotFoundException::class,
            'ThrottlingException' => ThrottlingException::class,
        ]]));

        return new ListCollectionsResponse($response, $this, $input);
    }

    /**
     * Returns an array of celebrities recognized in the input image. For more information, see Recognizing celebrities in
     * the Amazon Rekognition Developer Guide.
     *
     * `RecognizeCelebrities` returns the 64 largest faces in the image. It lists the recognized celebrities in the
     * `CelebrityFaces` array and any unrecognized faces in the `UnrecognizedFaces` array. `RecognizeCelebrities` doesn't
     * return celebrities whose faces aren't among the largest 64 faces in the image.
     *
     * For each celebrity recognized, `RecognizeCelebrities` returns a `Celebrity` object. The `Celebrity` object contains
     * the celebrity name, ID, URL links to additional information, match confidence, and a `ComparedFace` object that you
     * can use to locate the celebrity's face on the image.
     *
     * Amazon Rekognition doesn't retain information about which images a celebrity has been recognized in. Your application
     * must store this information and use the `Celebrity` ID property as a unique identifier for the celebrity. If you
     * don't store the celebrity name or additional information URLs returned by `RecognizeCelebrities`, you will need the
     * ID to identify the celebrity in a call to the GetCelebrityInfo operation.
     *
     * You pass the input image either as base64-encoded image bytes or as a reference to an image in an Amazon S3 bucket.
     * If you use the AWS CLI to call Amazon Rekognition operations, passing image bytes is not supported. The image must be
     * either a PNG or JPEG formatted file.
     *
     * For an example, see Recognizing celebrities in an image in the Amazon Rekognition Developer Guide.
     *
     * This operation requires permissions to perform the `rekognition:RecognizeCelebrities` operation.
     *
     * @see https://docs.aws.amazon.com/rekognition/latest/dg/API_RecognizeCelebrities.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-rekognition-2016-06-27.html#recognizecelebrities
     *
     * @param array{
     *   Image: Image|array,
     *   '@region'?: string|null,
     * }|RecognizeCelebritiesRequest $input
     *
     * @throws AccessDeniedException
     * @throws ImageTooLargeException
     * @throws InternalServerErrorException
     * @throws InvalidImageFormatException
     * @throws InvalidParameterException
     * @throws InvalidS3ObjectException
     * @throws ProvisionedThroughputExceededException
     * @throws ThrottlingException
     */
    public function recognizeCelebrities($input): RecognizeCelebritiesResponse
    {
        $input = RecognizeCelebritiesRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'RecognizeCelebrities', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'AccessDeniedException' => AccessDeniedException::class,
            'ImageTooLargeException' => ImageTooLargeException::class,
            'InternalServerError' => InternalServerErrorException::class,
            'InvalidImageFormatException' => InvalidImageFormatException::class,
            'InvalidParameterException' => InvalidParameterException::class,
            'InvalidS3ObjectException' => InvalidS3ObjectException::class,
            'ProvisionedThroughputExceededException' => ProvisionedThroughputExceededException::class,
            'ThrottlingException' => ThrottlingException::class,
        ]]));

        return new RecognizeCelebritiesResponse($response);
    }

    /**
     * For a given input image, first detects the largest face in the image, and then searches the specified collection for
     * matching faces. The operation compares the features of the input face with faces in the specified collection.
     *
     * > To search for all faces in an input image, you might first call the IndexFaces operation, and then use the face IDs
     * > returned in subsequent calls to the SearchFaces operation.
     * >
     * > You can also call the `DetectFaces` operation and use the bounding boxes in the response to make face crops, which
     * > then you can pass in to the `SearchFacesByImage` operation.
     *
     * You pass the input image either as base64-encoded image bytes or as a reference to an image in an Amazon S3 bucket.
     * If you use the AWS CLI to call Amazon Rekognition operations, passing image bytes is not supported. The image must be
     * either a PNG or JPEG formatted file.
     *
     * The response returns an array of faces that match, ordered by similarity score with the highest similarity first.
     * More specifically, it is an array of metadata for each face match found. Along with the metadata, the response also
     * includes a `similarity` indicating how similar the face is to the input face. In the response, the operation also
     * returns the bounding box (and a confidence level that the bounding box contains a face) of the face that Amazon
     * Rekognition used for the input image.
     *
     * If no faces are detected in the input image, `SearchFacesByImage` returns an `InvalidParameterException` error.
     *
     * For an example, Searching for a Face Using an Image in the Amazon Rekognition Developer Guide.
     *
     * The `QualityFilter` input parameter allows you to filter out detected faces that don’t meet a required quality bar.
     * The quality bar is based on a variety of common use cases. Use `QualityFilter` to set the quality bar for filtering
     * by specifying `LOW`, `MEDIUM`, or `HIGH`. If you do not want to filter detected faces, specify `NONE`. The default
     * value is `NONE`.
     *
     * > To use quality filtering, you need a collection associated with version 3 of the face model or higher. To get the
     * > version of the face model associated with a collection, call DescribeCollection.
     *
     * This operation requires permissions to perform the `rekognition:SearchFacesByImage` action.
     *
     * @see https://docs.aws.amazon.com/rekognition/latest/dg/API_SearchFacesByImage.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-rekognition-2016-06-27.html#searchfacesbyimage
     *
     * @param array{
     *   CollectionId: string,
     *   Image: Image|array,
     *   MaxFaces?: int|null,
     *   FaceMatchThreshold?: float|null,
     *   QualityFilter?: QualityFilter::*|null,
     *   '@region'?: string|null,
     * }|SearchFacesByImageRequest $input
     *
     * @throws AccessDeniedException
     * @throws ImageTooLargeException
     * @throws InternalServerErrorException
     * @throws InvalidImageFormatException
     * @throws InvalidParameterException
     * @throws InvalidS3ObjectException
     * @throws ProvisionedThroughputExceededException
     * @throws ResourceNotFoundException
     * @throws ThrottlingException
     */
    public function searchFacesByImage($input): SearchFacesByImageResponse
    {
        $input = SearchFacesByImageRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'SearchFacesByImage', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'AccessDeniedException' => AccessDeniedException::class,
            'ImageTooLargeException' => ImageTooLargeException::class,
            'InternalServerError' => InternalServerErrorException::class,
            'InvalidImageFormatException' => InvalidImageFormatException::class,
            'InvalidParameterException' => InvalidParameterException::class,
            'InvalidS3ObjectException' => InvalidS3ObjectException::class,
            'ProvisionedThroughputExceededException' => ProvisionedThroughputExceededException::class,
            'ResourceNotFoundException' => ResourceNotFoundException::class,
            'ThrottlingException' => ThrottlingException::class,
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
            case 'ap-northeast-1':
            case 'ap-northeast-2':
            case 'ap-south-1':
            case 'ap-southeast-1':
            case 'ap-southeast-2':
            case 'ca-central-1':
            case 'eu-central-1':
            case 'eu-south-2':
            case 'eu-west-1':
            case 'eu-west-2':
            case 'il-central-1':
            case 'us-east-1':
            case 'us-east-2':
            case 'us-gov-west-1':
            case 'us-west-1':
            case 'us-west-2':
                return [
                    'endpoint' => "https://rekognition.$region.amazonaws.com",
                    'signRegion' => $region,
                    'signService' => 'rekognition',
                    'signVersions' => ['v4'],
                ];
            case 'rekognition.ca-central-1':
                return [
                    'endpoint' => 'https://rekognition.rekognition.ca-central-1.amazonaws.com',
                    'signRegion' => 'ca-central-1',
                    'signService' => 'rekognition',
                    'signVersions' => ['v4'],
                ];
            case 'rekognition.us-east-1':
                return [
                    'endpoint' => 'https://rekognition.rekognition.us-east-1.amazonaws.com',
                    'signRegion' => 'us-east-1',
                    'signService' => 'rekognition',
                    'signVersions' => ['v4'],
                ];
            case 'rekognition.us-east-2':
                return [
                    'endpoint' => 'https://rekognition.rekognition.us-east-2.amazonaws.com',
                    'signRegion' => 'us-east-2',
                    'signService' => 'rekognition',
                    'signVersions' => ['v4'],
                ];
            case 'rekognition.us-west-1':
                return [
                    'endpoint' => 'https://rekognition.rekognition.us-west-1.amazonaws.com',
                    'signRegion' => 'us-west-1',
                    'signService' => 'rekognition',
                    'signVersions' => ['v4'],
                ];
            case 'rekognition.us-west-2':
                return [
                    'endpoint' => 'https://rekognition.rekognition.us-west-2.amazonaws.com',
                    'signRegion' => 'us-west-2',
                    'signService' => 'rekognition',
                    'signVersions' => ['v4'],
                ];
            case 'rekognition.us-gov-west-1':
                return [
                    'endpoint' => 'https://rekognition.rekognition.us-gov-west-1.amazonaws.com',
                    'signRegion' => 'us-gov-west-1',
                    'signService' => 'rekognition',
                    'signVersions' => ['v4'],
                ];
            case 'ca-central-1-fips':
            case 'rekognition-fips.ca-central-1':
                return [
                    'endpoint' => 'https://rekognition-fips.ca-central-1.amazonaws.com',
                    'signRegion' => 'ca-central-1',
                    'signService' => 'rekognition',
                    'signVersions' => ['v4'],
                ];
            case 'rekognition-fips.us-east-1':
            case 'us-east-1-fips':
                return [
                    'endpoint' => 'https://rekognition-fips.us-east-1.amazonaws.com',
                    'signRegion' => 'us-east-1',
                    'signService' => 'rekognition',
                    'signVersions' => ['v4'],
                ];
            case 'rekognition-fips.us-east-2':
            case 'us-east-2-fips':
                return [
                    'endpoint' => 'https://rekognition-fips.us-east-2.amazonaws.com',
                    'signRegion' => 'us-east-2',
                    'signService' => 'rekognition',
                    'signVersions' => ['v4'],
                ];
            case 'rekognition-fips.us-west-1':
            case 'us-west-1-fips':
                return [
                    'endpoint' => 'https://rekognition-fips.us-west-1.amazonaws.com',
                    'signRegion' => 'us-west-1',
                    'signService' => 'rekognition',
                    'signVersions' => ['v4'],
                ];
            case 'rekognition-fips.us-west-2':
            case 'us-west-2-fips':
                return [
                    'endpoint' => 'https://rekognition-fips.us-west-2.amazonaws.com',
                    'signRegion' => 'us-west-2',
                    'signService' => 'rekognition',
                    'signVersions' => ['v4'],
                ];
            case 'rekognition-fips.us-gov-west-1':
            case 'us-gov-west-1-fips':
                return [
                    'endpoint' => 'https://rekognition-fips.us-gov-west-1.amazonaws.com',
                    'signRegion' => 'us-gov-west-1',
                    'signService' => 'rekognition',
                    'signVersions' => ['v4'],
                ];
            case 'us-isof-east-1':
            case 'us-isof-south-1':
                return [
                    'endpoint' => "https://rekognition.$region.csp.hci.ic.gov",
                    'signRegion' => $region,
                    'signService' => 'rekognition',
                    'signVersions' => ['v4'],
                ];
        }

        throw new UnsupportedRegion(\sprintf('The region "%s" is not supported by "Rekognition".', $region));
    }
}
