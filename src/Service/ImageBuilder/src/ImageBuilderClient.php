<?php

namespace AsyncAws\ImageBuilder;

use AsyncAws\Core\AbstractApi;
use AsyncAws\Core\AwsError\AwsErrorFactoryInterface;
use AsyncAws\Core\AwsError\JsonRestAwsErrorFactory;
use AsyncAws\Core\Configuration;
use AsyncAws\Core\RequestContext;
use AsyncAws\ImageBuilder\Enum\Ownership;
use AsyncAws\ImageBuilder\Exception\CallRateLimitExceededException;
use AsyncAws\ImageBuilder\Exception\ClientException;
use AsyncAws\ImageBuilder\Exception\ForbiddenException;
use AsyncAws\ImageBuilder\Exception\IdempotentParameterMismatchException;
use AsyncAws\ImageBuilder\Exception\InvalidPaginationTokenException;
use AsyncAws\ImageBuilder\Exception\InvalidRequestException;
use AsyncAws\ImageBuilder\Exception\ResourceDependencyException;
use AsyncAws\ImageBuilder\Exception\ResourceInUseException;
use AsyncAws\ImageBuilder\Exception\ResourceNotFoundException;
use AsyncAws\ImageBuilder\Exception\ServiceException;
use AsyncAws\ImageBuilder\Exception\ServiceUnavailableException;
use AsyncAws\ImageBuilder\Input\DeleteImageRequest;
use AsyncAws\ImageBuilder\Input\GetImageRequest;
use AsyncAws\ImageBuilder\Input\ListImageBuildVersionsRequest;
use AsyncAws\ImageBuilder\Input\ListImagesRequest;
use AsyncAws\ImageBuilder\Input\StartImagePipelineExecutionRequest;
use AsyncAws\ImageBuilder\Result\DeleteImageResponse;
use AsyncAws\ImageBuilder\Result\GetImageResponse;
use AsyncAws\ImageBuilder\Result\ListImageBuildVersionsResponse;
use AsyncAws\ImageBuilder\Result\ListImagesResponse;
use AsyncAws\ImageBuilder\Result\StartImagePipelineExecutionResponse;
use AsyncAws\ImageBuilder\ValueObject\Filter;

class ImageBuilderClient extends AbstractApi
{
    /**
     * Deletes an Image Builder image resource. This does not delete any EC2 AMIs or ECR container images that are created
     * during the image build process. You must clean those up separately, using the appropriate Amazon EC2 or Amazon ECR
     * console actions, or API or CLI commands.
     *
     * - To deregister an EC2 Linux AMI, see Deregister your Linux AMI [^1] in the **Amazon EC2 User Guide**.
     * - To deregister an EC2 Windows AMI, see Deregister your Windows AMI [^2] in the **Amazon EC2 Windows Guide**.
     * - To delete a container image from Amazon ECR, see Deleting an image [^3] in the *Amazon ECR User Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/AWSEC2/latest/UserGuide/deregister-ami.html
     * [^2]: https://docs.aws.amazon.com/AWSEC2/latest/WindowsGuide/deregister-ami.html
     * [^3]: https://docs.aws.amazon.com/AmazonECR/latest/userguide/delete_image.html
     *
     * @see https://docs.aws.amazon.com/imagebuilder/latest/APIReference/API_DeleteImage.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-imagebuilder-2019-12-02.html#deleteimage
     *
     * @param array{
     *   imageBuildVersionArn: string,
     *   '@region'?: string|null,
     * }|DeleteImageRequest $input
     *
     * @throws CallRateLimitExceededException
     * @throws ClientException
     * @throws ForbiddenException
     * @throws InvalidRequestException
     * @throws ResourceDependencyException
     * @throws ServiceException
     * @throws ServiceUnavailableException
     */
    public function deleteImage($input): DeleteImageResponse
    {
        $input = DeleteImageRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'DeleteImage', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'CallRateLimitExceededException' => CallRateLimitExceededException::class,
            'ClientException' => ClientException::class,
            'ForbiddenException' => ForbiddenException::class,
            'InvalidRequestException' => InvalidRequestException::class,
            'ResourceDependencyException' => ResourceDependencyException::class,
            'ServiceException' => ServiceException::class,
            'ServiceUnavailableException' => ServiceUnavailableException::class,
        ]]));

        return new DeleteImageResponse($response);
    }

    /**
     * Gets an image.
     *
     * @see https://docs.aws.amazon.com/imagebuilder/latest/APIReference/API_GetImage.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-imagebuilder-2019-12-02.html#getimage
     *
     * @param array{
     *   imageBuildVersionArn: string,
     *   '@region'?: string|null,
     * }|GetImageRequest $input
     *
     * @throws CallRateLimitExceededException
     * @throws ClientException
     * @throws ForbiddenException
     * @throws InvalidRequestException
     * @throws ServiceException
     * @throws ServiceUnavailableException
     */
    public function getImage($input): GetImageResponse
    {
        $input = GetImageRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'GetImage', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'CallRateLimitExceededException' => CallRateLimitExceededException::class,
            'ClientException' => ClientException::class,
            'ForbiddenException' => ForbiddenException::class,
            'InvalidRequestException' => InvalidRequestException::class,
            'ServiceException' => ServiceException::class,
            'ServiceUnavailableException' => ServiceUnavailableException::class,
        ]]));

        return new GetImageResponse($response);
    }

    /**
     * Returns a list of image build versions.
     *
     * @see https://docs.aws.amazon.com/imagebuilder/latest/APIReference/API_ListImageBuildVersions.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-imagebuilder-2019-12-02.html#listimagebuildversions
     *
     * @param array{
     *   imageVersionArn?: string|null,
     *   filters?: array<Filter|array>|null,
     *   maxResults?: int|null,
     *   nextToken?: string|null,
     *   '@region'?: string|null,
     * }|ListImageBuildVersionsRequest $input
     *
     * @throws CallRateLimitExceededException
     * @throws ClientException
     * @throws ForbiddenException
     * @throws InvalidPaginationTokenException
     * @throws InvalidRequestException
     * @throws ServiceException
     * @throws ServiceUnavailableException
     */
    public function listImageBuildVersions($input = []): ListImageBuildVersionsResponse
    {
        $input = ListImageBuildVersionsRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'ListImageBuildVersions', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'CallRateLimitExceededException' => CallRateLimitExceededException::class,
            'ClientException' => ClientException::class,
            'ForbiddenException' => ForbiddenException::class,
            'InvalidPaginationTokenException' => InvalidPaginationTokenException::class,
            'InvalidRequestException' => InvalidRequestException::class,
            'ServiceException' => ServiceException::class,
            'ServiceUnavailableException' => ServiceUnavailableException::class,
        ]]));

        return new ListImageBuildVersionsResponse($response, $this, $input);
    }

    /**
     * Returns the list of images that you have access to. Newly created images can take up to two minutes to appear in the
     * ListImages API Results.
     *
     * @see https://docs.aws.amazon.com/imagebuilder/latest/APIReference/API_ListImages.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-imagebuilder-2019-12-02.html#listimages
     *
     * @param array{
     *   owner?: Ownership::*|null,
     *   filters?: array<Filter|array>|null,
     *   byName?: bool|null,
     *   maxResults?: int|null,
     *   nextToken?: string|null,
     *   includeDeprecated?: bool|null,
     *   '@region'?: string|null,
     * }|ListImagesRequest $input
     *
     * @throws CallRateLimitExceededException
     * @throws ClientException
     * @throws ForbiddenException
     * @throws InvalidPaginationTokenException
     * @throws InvalidRequestException
     * @throws ServiceException
     * @throws ServiceUnavailableException
     */
    public function listImages($input = []): ListImagesResponse
    {
        $input = ListImagesRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'ListImages', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'CallRateLimitExceededException' => CallRateLimitExceededException::class,
            'ClientException' => ClientException::class,
            'ForbiddenException' => ForbiddenException::class,
            'InvalidPaginationTokenException' => InvalidPaginationTokenException::class,
            'InvalidRequestException' => InvalidRequestException::class,
            'ServiceException' => ServiceException::class,
            'ServiceUnavailableException' => ServiceUnavailableException::class,
        ]]));

        return new ListImagesResponse($response, $this, $input);
    }

    /**
     * Manually triggers a pipeline to create an image.
     *
     * @see https://docs.aws.amazon.com/imagebuilder/latest/APIReference/API_StartImagePipelineExecution.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-imagebuilder-2019-12-02.html#startimagepipelineexecution
     *
     * @param array{
     *   imagePipelineArn: string,
     *   clientToken: string,
     *   tags?: array<string, string>|null,
     *   '@region'?: string|null,
     * }|StartImagePipelineExecutionRequest $input
     *
     * @throws CallRateLimitExceededException
     * @throws ClientException
     * @throws ForbiddenException
     * @throws IdempotentParameterMismatchException
     * @throws InvalidRequestException
     * @throws ResourceInUseException
     * @throws ResourceNotFoundException
     * @throws ServiceException
     * @throws ServiceUnavailableException
     */
    public function startImagePipelineExecution($input): StartImagePipelineExecutionResponse
    {
        $input = StartImagePipelineExecutionRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'StartImagePipelineExecution', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'CallRateLimitExceededException' => CallRateLimitExceededException::class,
            'ClientException' => ClientException::class,
            'ForbiddenException' => ForbiddenException::class,
            'IdempotentParameterMismatchException' => IdempotentParameterMismatchException::class,
            'InvalidRequestException' => InvalidRequestException::class,
            'ResourceInUseException' => ResourceInUseException::class,
            'ResourceNotFoundException' => ResourceNotFoundException::class,
            'ServiceException' => ServiceException::class,
            'ServiceUnavailableException' => ServiceUnavailableException::class,
        ]]));

        return new StartImagePipelineExecutionResponse($response);
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
            'endpoint' => "https://imagebuilder.$region.amazonaws.com",
            'signRegion' => $region,
            'signService' => 'imagebuilder',
            'signVersions' => ['v4'],
        ];
    }
}
