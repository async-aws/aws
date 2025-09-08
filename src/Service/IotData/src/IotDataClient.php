<?php

namespace AsyncAws\IotData;

use AsyncAws\Core\AbstractApi;
use AsyncAws\Core\AwsError\AwsErrorFactoryInterface;
use AsyncAws\Core\AwsError\JsonRestAwsErrorFactory;
use AsyncAws\Core\Configuration;
use AsyncAws\Core\RequestContext;
use AsyncAws\IotData\Exception\ConflictException;
use AsyncAws\IotData\Exception\InternalFailureException;
use AsyncAws\IotData\Exception\InvalidRequestException;
use AsyncAws\IotData\Exception\MethodNotAllowedException;
use AsyncAws\IotData\Exception\RequestEntityTooLargeException;
use AsyncAws\IotData\Exception\ResourceNotFoundException;
use AsyncAws\IotData\Exception\ServiceUnavailableException;
use AsyncAws\IotData\Exception\ThrottlingException;
use AsyncAws\IotData\Exception\UnauthorizedException;
use AsyncAws\IotData\Exception\UnsupportedDocumentEncodingException;
use AsyncAws\IotData\Input\GetThingShadowRequest;
use AsyncAws\IotData\Input\UpdateThingShadowRequest;
use AsyncAws\IotData\Result\GetThingShadowResponse;
use AsyncAws\IotData\Result\UpdateThingShadowResponse;

class IotDataClient extends AbstractApi
{
    /**
     * Gets the shadow for the specified thing.
     *
     * Requires permission to access the GetThingShadow [^1] action.
     *
     * For more information, see GetThingShadow [^2] in the IoT Developer Guide.
     *
     * [^1]: https://docs.aws.amazon.com/service-authorization/latest/reference/list_awsiot.html#awsiot-actions-as-permissions
     * [^2]: http://docs.aws.amazon.com/iot/latest/developerguide/API_GetThingShadow.html
     *
     * @see https://docs.aws.amazon.com/iot/latest/apireference/API_Operations_AWS_IoT_Data_Plane.html/API_GetThingShadow.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-data-ats.iot-2015-05-28.html#getthingshadow
     *
     * @param array{
     *   thingName: string,
     *   shadowName?: string|null,
     *   '@region'?: string|null,
     * }|GetThingShadowRequest $input
     *
     * @throws InternalFailureException
     * @throws InvalidRequestException
     * @throws MethodNotAllowedException
     * @throws ResourceNotFoundException
     * @throws ServiceUnavailableException
     * @throws ThrottlingException
     * @throws UnauthorizedException
     * @throws UnsupportedDocumentEncodingException
     */
    public function getThingShadow($input): GetThingShadowResponse
    {
        $input = GetThingShadowRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'GetThingShadow', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'InternalFailureException' => InternalFailureException::class,
            'InvalidRequestException' => InvalidRequestException::class,
            'MethodNotAllowedException' => MethodNotAllowedException::class,
            'ResourceNotFoundException' => ResourceNotFoundException::class,
            'ServiceUnavailableException' => ServiceUnavailableException::class,
            'ThrottlingException' => ThrottlingException::class,
            'UnauthorizedException' => UnauthorizedException::class,
            'UnsupportedDocumentEncodingException' => UnsupportedDocumentEncodingException::class,
        ]]));

        return new GetThingShadowResponse($response);
    }

    /**
     * Updates the shadow for the specified thing.
     *
     * Requires permission to access the UpdateThingShadow [^1] action.
     *
     * For more information, see UpdateThingShadow [^2] in the IoT Developer Guide.
     *
     * [^1]: https://docs.aws.amazon.com/service-authorization/latest/reference/list_awsiot.html#awsiot-actions-as-permissions
     * [^2]: http://docs.aws.amazon.com/iot/latest/developerguide/API_UpdateThingShadow.html
     *
     * @see https://docs.aws.amazon.com/iot/latest/apireference/API_Operations_AWS_IoT_Data_Plane.html/API_UpdateThingShadow.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-data-ats.iot-2015-05-28.html#updatethingshadow
     *
     * @param array{
     *   thingName: string,
     *   shadowName?: string|null,
     *   payload: string,
     *   '@region'?: string|null,
     * }|UpdateThingShadowRequest $input
     *
     * @throws ConflictException
     * @throws InternalFailureException
     * @throws InvalidRequestException
     * @throws MethodNotAllowedException
     * @throws RequestEntityTooLargeException
     * @throws ServiceUnavailableException
     * @throws ThrottlingException
     * @throws UnauthorizedException
     * @throws UnsupportedDocumentEncodingException
     */
    public function updateThingShadow($input): UpdateThingShadowResponse
    {
        $input = UpdateThingShadowRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'UpdateThingShadow', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'ConflictException' => ConflictException::class,
            'InternalFailureException' => InternalFailureException::class,
            'InvalidRequestException' => InvalidRequestException::class,
            'MethodNotAllowedException' => MethodNotAllowedException::class,
            'RequestEntityTooLargeException' => RequestEntityTooLargeException::class,
            'ServiceUnavailableException' => ServiceUnavailableException::class,
            'ThrottlingException' => ThrottlingException::class,
            'UnauthorizedException' => UnauthorizedException::class,
            'UnsupportedDocumentEncodingException' => UnsupportedDocumentEncodingException::class,
        ]]));

        return new UpdateThingShadowResponse($response);
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

        switch ($region) {
            case 'cn-north-1':
                return [
                    'endpoint' => 'https://data.ats.iot.cn-north-1.amazonaws.com.cn',
                    'signRegion' => 'cn-north-1',
                    'signService' => 'iotdata',
                    'signVersions' => ['v4'],
                ];
            case 'cn-northwest-1':
                return [
                    'endpoint' => 'https://data-ats.iot.cn-northwest-1.amazonaws.com.cn',
                    'signRegion' => 'cn-northwest-1',
                    'signService' => 'iotdata',
                    'signVersions' => ['v4'],
                ];
            case 'fips-ca-central-1':
                return [
                    'endpoint' => 'https://data.iot-fips.ca-central-1.amazonaws.com',
                    'signRegion' => 'fips-ca-central-1',
                    'signService' => 'iotdata',
                    'signVersions' => ['v4'],
                ];
            case 'fips-us-east-1':
                return [
                    'endpoint' => 'https://data.iot-fips.us-east-1.amazonaws.com',
                    'signRegion' => 'fips-us-east-1',
                    'signService' => 'iotdata',
                    'signVersions' => ['v4'],
                ];
            case 'fips-us-east-2':
                return [
                    'endpoint' => 'https://data.iot-fips.us-east-2.amazonaws.com',
                    'signRegion' => 'fips-us-east-2',
                    'signService' => 'iotdata',
                    'signVersions' => ['v4'],
                ];
            case 'fips-us-west-1':
                return [
                    'endpoint' => 'https://data.iot-fips.us-west-1.amazonaws.com',
                    'signRegion' => 'fips-us-west-1',
                    'signService' => 'iotdata',
                    'signVersions' => ['v4'],
                ];
            case 'fips-us-west-2':
                return [
                    'endpoint' => 'https://data.iot-fips.us-west-2.amazonaws.com',
                    'signRegion' => 'fips-us-west-2',
                    'signService' => 'iotdata',
                    'signVersions' => ['v4'],
                ];
            case 'fips-us-gov-east-1':
                return [
                    'endpoint' => 'https://data.iot-fips.us-gov-east-1.amazonaws.com',
                    'signRegion' => 'fips-us-gov-east-1',
                    'signService' => 'iotdata',
                    'signVersions' => ['v4'],
                ];
            case 'fips-us-gov-west-1':
                return [
                    'endpoint' => 'https://data.iot-fips.us-gov-west-1.amazonaws.com',
                    'signRegion' => 'fips-us-gov-west-1',
                    'signService' => 'iotdata',
                    'signVersions' => ['v4'],
                ];
        }

        return [
            'endpoint' => "https://data-ats.iot.$region.amazonaws.com",
            'signRegion' => $region,
            'signService' => 'iotdata',
            'signVersions' => ['v4'],
        ];
    }
}
