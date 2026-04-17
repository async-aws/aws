<?php

namespace AsyncAws\ImageBuilder;

use AsyncAws\Core\AbstractApi;
use AsyncAws\Core\AwsError\AwsErrorFactoryInterface;
use AsyncAws\Core\AwsError\JsonRestAwsErrorFactory;
use AsyncAws\Core\Configuration;
use AsyncAws\Core\RequestContext;
use AsyncAws\ImageBuilder\Exception\CallRateLimitExceededException;
use AsyncAws\ImageBuilder\Exception\ClientException;
use AsyncAws\ImageBuilder\Exception\ForbiddenException;
use AsyncAws\ImageBuilder\Exception\IdempotentParameterMismatchException;
use AsyncAws\ImageBuilder\Exception\InvalidRequestException;
use AsyncAws\ImageBuilder\Exception\ResourceInUseException;
use AsyncAws\ImageBuilder\Exception\ResourceNotFoundException;
use AsyncAws\ImageBuilder\Exception\ServiceException;
use AsyncAws\ImageBuilder\Exception\ServiceUnavailableException;
use AsyncAws\ImageBuilder\Input\GetImageRequest;
use AsyncAws\ImageBuilder\Input\StartImagePipelineExecutionRequest;
use AsyncAws\ImageBuilder\Result\GetImageResponse;
use AsyncAws\ImageBuilder\Result\StartImagePipelineExecutionResponse;

class ImageBuilderClient extends AbstractApi
{
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
