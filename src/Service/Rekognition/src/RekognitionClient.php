<?php

namespace AsyncAws\Rekognition;

use AsyncAws\Core\AbstractApi;
use AsyncAws\Core\Configuration;
use AsyncAws\Core\Exception\UnsupportedRegion;
use AsyncAws\Core\RequestContext;
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

class RekognitionClient extends AbstractApi
{
    /**
     * Creates a collection in an AWS Region. You can add faces to the collection using the IndexFaces operation.
     *
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-rekognition-2016-06-27.html#createcollection
     *
     * @param array{
     *   CollectionId: string,
     *   @region?: string,
     * }|CreateCollectionRequest $input
     */
    public function createCollection($input): CreateCollectionResponse
    {
        $input = CreateCollectionRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'CreateCollection', 'region' => $input->getRegion()]));

        return new CreateCollectionResponse($response);
    }

    /**
     * Creates a new Amazon Rekognition Custom Labels project. A project is a logical grouping of resources (images, Labels,
     * models) and operations (training, evaluation and detection).
     *
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-rekognition-2016-06-27.html#createproject
     *
     * @param array{
     *   ProjectName: string,
     *   @region?: string,
     * }|CreateProjectRequest $input
     */
    public function createProject($input): CreateProjectResponse
    {
        $input = CreateProjectRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'CreateProject', 'region' => $input->getRegion()]));

        return new CreateProjectResponse($response);
    }

    /**
     * Deletes the specified collection. Note that this operation removes all faces in the collection. For an example, see
     * delete-collection-procedure.
     *
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-rekognition-2016-06-27.html#deletecollection
     *
     * @param array{
     *   CollectionId: string,
     *   @region?: string,
     * }|DeleteCollectionRequest $input
     */
    public function deleteCollection($input): DeleteCollectionResponse
    {
        $input = DeleteCollectionRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'DeleteCollection', 'region' => $input->getRegion()]));

        return new DeleteCollectionResponse($response);
    }

    /**
     * Deletes an Amazon Rekognition Custom Labels project. To delete a project you must first delete all versions of the
     * model associated with the project. To delete a version of a model, see DeleteProjectVersion.
     *
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-rekognition-2016-06-27.html#deleteproject
     *
     * @param array{
     *   ProjectArn: string,
     *   @region?: string,
     * }|DeleteProjectRequest $input
     */
    public function deleteProject($input): DeleteProjectResponse
    {
        $input = DeleteProjectRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'DeleteProject', 'region' => $input->getRegion()]));

        return new DeleteProjectResponse($response);
    }

    /**
     * Detects faces within an image that is provided as input.
     *
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-rekognition-2016-06-27.html#detectfaces
     *
     * @param array{
     *   Image: \AsyncAws\Rekognition\ValueObject\Image|array,
     *   Attributes?: list<\AsyncAws\Rekognition\Enum\Attribute::*>,
     *   @region?: string,
     * }|DetectFacesRequest $input
     */
    public function detectFaces($input): DetectFacesResponse
    {
        $input = DetectFacesRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'DetectFaces', 'region' => $input->getRegion()]));

        return new DetectFacesResponse($response);
    }

    /**
     * Detects faces in the input image and adds them to the specified collection.
     *
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-rekognition-2016-06-27.html#indexfaces
     *
     * @param array{
     *   CollectionId: string,
     *   Image: \AsyncAws\Rekognition\ValueObject\Image|array,
     *   ExternalImageId?: string,
     *   DetectionAttributes?: list<\AsyncAws\Rekognition\Enum\Attribute::*>,
     *   MaxFaces?: int,
     *   QualityFilter?: \AsyncAws\Rekognition\Enum\QualityFilter::*,
     *   @region?: string,
     * }|IndexFacesRequest $input
     */
    public function indexFaces($input): IndexFacesResponse
    {
        $input = IndexFacesRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'IndexFaces', 'region' => $input->getRegion()]));

        return new IndexFacesResponse($response);
    }

    /**
     * Returns list of collection IDs in your account. If the result is truncated, the response also provides a `NextToken`
     * that you can use in the subsequent request to fetch the next set of collection IDs.
     *
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-rekognition-2016-06-27.html#listcollections
     *
     * @param array{
     *   NextToken?: string,
     *   MaxResults?: int,
     *   @region?: string,
     * }|ListCollectionsRequest $input
     *
     * @return \Traversable<string> & ListCollectionsResponse
     */
    public function listCollections($input = []): ListCollectionsResponse
    {
        $input = ListCollectionsRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'ListCollections', 'region' => $input->getRegion()]));

        return new ListCollectionsResponse($response, $this, $input);
    }

    /**
     * For a given input image, first detects the largest face in the image, and then searches the specified collection for
     * matching faces. The operation compares the features of the input face with faces in the specified collection.
     *
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-rekognition-2016-06-27.html#searchfacesbyimage
     *
     * @param array{
     *   CollectionId: string,
     *   Image: \AsyncAws\Rekognition\ValueObject\Image|array,
     *   MaxFaces?: int,
     *   FaceMatchThreshold?: float,
     *   QualityFilter?: \AsyncAws\Rekognition\Enum\QualityFilter::*,
     *   @region?: string,
     * }|SearchFacesByImageRequest $input
     */
    public function searchFacesByImage($input): SearchFacesByImageResponse
    {
        $input = SearchFacesByImageRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'SearchFacesByImage', 'region' => $input->getRegion()]));

        return new SearchFacesByImageResponse($response);
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
            case 'eu-central-1':
            case 'eu-west-1':
            case 'eu-west-2':
            case 'us-east-1':
            case 'us-east-2':
            case 'us-west-1':
            case 'us-west-2':
                return [
                    'endpoint' => "https://rekognition.$region.amazonaws.com",
                    'signRegion' => $region,
                    'signService' => 'rekognition',
                    'signVersions' => ['v4'],
                ];
            case 'us-gov-west-1':
                return [
                    'endpoint' => "https://rekognition.$region.amazonaws.com",
                    'signRegion' => $region,
                    'signService' => 'rekognition',
                    'signVersions' => ['v4'],
                ];
        }

        throw new UnsupportedRegion(sprintf('The region "%s" is not supported by "Rekognition".', $region));
    }
}
