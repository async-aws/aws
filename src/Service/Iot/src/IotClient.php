<?php

namespace AsyncAws\Iot;

use AsyncAws\Core\AbstractApi;
use AsyncAws\Core\AwsError\AwsErrorFactoryInterface;
use AsyncAws\Core\AwsError\JsonRestAwsErrorFactory;
use AsyncAws\Core\Configuration;
use AsyncAws\Core\RequestContext;
use AsyncAws\Iot\Exception\InternalFailureException;
use AsyncAws\Iot\Exception\InvalidRequestException;
use AsyncAws\Iot\Exception\ResourceAlreadyExistsException;
use AsyncAws\Iot\Exception\ResourceNotFoundException;
use AsyncAws\Iot\Exception\ServiceUnavailableException;
use AsyncAws\Iot\Exception\ThrottlingException;
use AsyncAws\Iot\Exception\UnauthorizedException;
use AsyncAws\Iot\Exception\VersionConflictException;
use AsyncAws\Iot\Input\AddThingToThingGroupRequest;
use AsyncAws\Iot\Input\CreateThingGroupRequest;
use AsyncAws\Iot\Input\CreateThingRequest;
use AsyncAws\Iot\Input\CreateThingTypeRequest;
use AsyncAws\Iot\Input\DeleteThingGroupRequest;
use AsyncAws\Iot\Input\DeleteThingRequest;
use AsyncAws\Iot\Input\DeleteThingTypeRequest;
use AsyncAws\Iot\Input\ListThingGroupsForThingRequest;
use AsyncAws\Iot\Input\ListThingGroupsRequest;
use AsyncAws\Iot\Input\ListThingsInThingGroupRequest;
use AsyncAws\Iot\Input\ListThingsRequest;
use AsyncAws\Iot\Input\ListThingTypesRequest;
use AsyncAws\Iot\Result\AddThingToThingGroupResponse;
use AsyncAws\Iot\Result\CreateThingGroupResponse;
use AsyncAws\Iot\Result\CreateThingResponse;
use AsyncAws\Iot\Result\CreateThingTypeResponse;
use AsyncAws\Iot\Result\DeleteThingGroupResponse;
use AsyncAws\Iot\Result\DeleteThingResponse;
use AsyncAws\Iot\Result\DeleteThingTypeResponse;
use AsyncAws\Iot\Result\ListThingGroupsForThingResponse;
use AsyncAws\Iot\Result\ListThingGroupsResponse;
use AsyncAws\Iot\Result\ListThingsInThingGroupResponse;
use AsyncAws\Iot\Result\ListThingsResponse;
use AsyncAws\Iot\Result\ListThingTypesResponse;
use AsyncAws\Iot\ValueObject\AttributePayload;
use AsyncAws\Iot\ValueObject\Tag;
use AsyncAws\Iot\ValueObject\ThingGroupProperties;
use AsyncAws\Iot\ValueObject\ThingTypeProperties;

class IotClient extends AbstractApi
{
    /**
     * Adds a thing to a thing group.
     *
     * @see https://docs.aws.amazon.com/iot/latest/apireference/API_AddThingToThingGroup.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-iot-2015-05-28.html#addthingtothinggroup
     *
     * @param array{
     *   thingGroupName?: string,
     *   thingGroupArn?: string,
     *   thingName?: string,
     *   thingArn?: string,
     *   overrideDynamicGroups?: bool,
     *   @region?: string,
     * }|AddThingToThingGroupRequest $input
     *
     * @throws InvalidRequestException
     * @throws ThrottlingException
     * @throws InternalFailureException
     * @throws ResourceNotFoundException
     */
    public function addThingToThingGroup($input = []): AddThingToThingGroupResponse
    {
        $input = AddThingToThingGroupRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'AddThingToThingGroup', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'InvalidRequestException' => InvalidRequestException::class,
            'ThrottlingException' => ThrottlingException::class,
            'InternalFailureException' => InternalFailureException::class,
            'ResourceNotFoundException' => ResourceNotFoundException::class,
        ]]));

        return new AddThingToThingGroupResponse($response);
    }

    /**
     * Creates a thing record in the registry. If this call is made multiple times using the same thing name and
     * configuration, the call will succeed. If this call is made with the same thing name but different configuration a
     * `ResourceAlreadyExistsException` is thrown.
     *
     * @see https://docs.aws.amazon.com/iot/latest/apireference/API_CreateThing.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-iot-2015-05-28.html#creatething
     *
     * @param array{
     *   thingName: string,
     *   thingTypeName?: string,
     *   attributePayload?: AttributePayload|array,
     *   billingGroupName?: string,
     *   @region?: string,
     * }|CreateThingRequest $input
     *
     * @throws InvalidRequestException
     * @throws ThrottlingException
     * @throws UnauthorizedException
     * @throws ServiceUnavailableException
     * @throws InternalFailureException
     * @throws ResourceAlreadyExistsException
     * @throws ResourceNotFoundException
     */
    public function createThing($input): CreateThingResponse
    {
        $input = CreateThingRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'CreateThing', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'InvalidRequestException' => InvalidRequestException::class,
            'ThrottlingException' => ThrottlingException::class,
            'UnauthorizedException' => UnauthorizedException::class,
            'ServiceUnavailableException' => ServiceUnavailableException::class,
            'InternalFailureException' => InternalFailureException::class,
            'ResourceAlreadyExistsException' => ResourceAlreadyExistsException::class,
            'ResourceNotFoundException' => ResourceNotFoundException::class,
        ]]));

        return new CreateThingResponse($response);
    }

    /**
     * Create a thing group.
     *
     * @see https://docs.aws.amazon.com/iot/latest/apireference/API_CreateThingGroup.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-iot-2015-05-28.html#createthinggroup
     *
     * @param array{
     *   thingGroupName: string,
     *   parentGroupName?: string,
     *   thingGroupProperties?: ThingGroupProperties|array,
     *   tags?: Tag[],
     *   @region?: string,
     * }|CreateThingGroupRequest $input
     *
     * @throws InvalidRequestException
     * @throws ResourceAlreadyExistsException
     * @throws ThrottlingException
     * @throws InternalFailureException
     */
    public function createThingGroup($input): CreateThingGroupResponse
    {
        $input = CreateThingGroupRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'CreateThingGroup', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'InvalidRequestException' => InvalidRequestException::class,
            'ResourceAlreadyExistsException' => ResourceAlreadyExistsException::class,
            'ThrottlingException' => ThrottlingException::class,
            'InternalFailureException' => InternalFailureException::class,
        ]]));

        return new CreateThingGroupResponse($response);
    }

    /**
     * Creates a new thing type.
     *
     * @see https://docs.aws.amazon.com/iot/latest/apireference/API_CreateThingType.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-iot-2015-05-28.html#createthingtype
     *
     * @param array{
     *   thingTypeName: string,
     *   thingTypeProperties?: ThingTypeProperties|array,
     *   tags?: Tag[],
     *   @region?: string,
     * }|CreateThingTypeRequest $input
     *
     * @throws InvalidRequestException
     * @throws ThrottlingException
     * @throws UnauthorizedException
     * @throws ServiceUnavailableException
     * @throws InternalFailureException
     * @throws ResourceAlreadyExistsException
     */
    public function createThingType($input): CreateThingTypeResponse
    {
        $input = CreateThingTypeRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'CreateThingType', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'InvalidRequestException' => InvalidRequestException::class,
            'ThrottlingException' => ThrottlingException::class,
            'UnauthorizedException' => UnauthorizedException::class,
            'ServiceUnavailableException' => ServiceUnavailableException::class,
            'InternalFailureException' => InternalFailureException::class,
            'ResourceAlreadyExistsException' => ResourceAlreadyExistsException::class,
        ]]));

        return new CreateThingTypeResponse($response);
    }

    /**
     * Deletes the specified thing. Returns successfully with no error if the deletion is successful or you specify a thing
     * that doesn't exist.
     *
     * @see https://docs.aws.amazon.com/iot/latest/apireference/API_DeleteThing.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-iot-2015-05-28.html#deletething
     *
     * @param array{
     *   thingName: string,
     *   expectedVersion?: string,
     *   @region?: string,
     * }|DeleteThingRequest $input
     *
     * @throws ResourceNotFoundException
     * @throws VersionConflictException
     * @throws InvalidRequestException
     * @throws ThrottlingException
     * @throws UnauthorizedException
     * @throws ServiceUnavailableException
     * @throws InternalFailureException
     */
    public function deleteThing($input): DeleteThingResponse
    {
        $input = DeleteThingRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'DeleteThing', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'ResourceNotFoundException' => ResourceNotFoundException::class,
            'VersionConflictException' => VersionConflictException::class,
            'InvalidRequestException' => InvalidRequestException::class,
            'ThrottlingException' => ThrottlingException::class,
            'UnauthorizedException' => UnauthorizedException::class,
            'ServiceUnavailableException' => ServiceUnavailableException::class,
            'InternalFailureException' => InternalFailureException::class,
        ]]));

        return new DeleteThingResponse($response);
    }

    /**
     * Deletes a thing group.
     *
     * @see https://docs.aws.amazon.com/iot/latest/apireference/API_DeleteThingGroup.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-iot-2015-05-28.html#deletethinggroup
     *
     * @param array{
     *   thingGroupName: string,
     *   expectedVersion?: string,
     *   @region?: string,
     * }|DeleteThingGroupRequest $input
     *
     * @throws InvalidRequestException
     * @throws VersionConflictException
     * @throws ThrottlingException
     * @throws InternalFailureException
     */
    public function deleteThingGroup($input): DeleteThingGroupResponse
    {
        $input = DeleteThingGroupRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'DeleteThingGroup', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'InvalidRequestException' => InvalidRequestException::class,
            'VersionConflictException' => VersionConflictException::class,
            'ThrottlingException' => ThrottlingException::class,
            'InternalFailureException' => InternalFailureException::class,
        ]]));

        return new DeleteThingGroupResponse($response);
    }

    /**
     * Deletes the specified thing type. You cannot delete a thing type if it has things associated with it. To delete a
     * thing type, first mark it as deprecated by calling DeprecateThingType, then remove any associated things by calling
     * UpdateThing to change the thing type on any associated thing, and finally use DeleteThingType to delete the thing
     * type.
     *
     * @see https://docs.aws.amazon.com/iot/latest/apireference/API_DeleteThingType.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-iot-2015-05-28.html#deletethingtype
     *
     * @param array{
     *   thingTypeName: string,
     *   @region?: string,
     * }|DeleteThingTypeRequest $input
     *
     * @throws ResourceNotFoundException
     * @throws InvalidRequestException
     * @throws ThrottlingException
     * @throws UnauthorizedException
     * @throws ServiceUnavailableException
     * @throws InternalFailureException
     */
    public function deleteThingType($input): DeleteThingTypeResponse
    {
        $input = DeleteThingTypeRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'DeleteThingType', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'ResourceNotFoundException' => ResourceNotFoundException::class,
            'InvalidRequestException' => InvalidRequestException::class,
            'ThrottlingException' => ThrottlingException::class,
            'UnauthorizedException' => UnauthorizedException::class,
            'ServiceUnavailableException' => ServiceUnavailableException::class,
            'InternalFailureException' => InternalFailureException::class,
        ]]));

        return new DeleteThingTypeResponse($response);
    }

    /**
     * List the thing groups in your account.
     *
     * @see https://docs.aws.amazon.com/iot/latest/apireference/API_ListThingGroups.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-iot-2015-05-28.html#listthinggroups
     *
     * @param array{
     *   nextToken?: string,
     *   maxResults?: int,
     *   parentGroup?: string,
     *   namePrefixFilter?: string,
     *   recursive?: bool,
     *   @region?: string,
     * }|ListThingGroupsRequest $input
     *
     * @throws InvalidRequestException
     * @throws InternalFailureException
     * @throws ResourceNotFoundException
     * @throws ThrottlingException
     */
    public function listThingGroups($input = []): ListThingGroupsResponse
    {
        $input = ListThingGroupsRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'ListThingGroups', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'InvalidRequestException' => InvalidRequestException::class,
            'InternalFailureException' => InternalFailureException::class,
            'ResourceNotFoundException' => ResourceNotFoundException::class,
            'ThrottlingException' => ThrottlingException::class,
        ]]));

        return new ListThingGroupsResponse($response, $this, $input);
    }

    /**
     * List the thing groups to which the specified thing belongs.
     *
     * @see https://docs.aws.amazon.com/iot/latest/apireference/API_ListThingGroupsForThing.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-iot-2015-05-28.html#listthinggroupsforthing
     *
     * @param array{
     *   thingName: string,
     *   nextToken?: string,
     *   maxResults?: int,
     *   @region?: string,
     * }|ListThingGroupsForThingRequest $input
     *
     * @throws InvalidRequestException
     * @throws InternalFailureException
     * @throws ResourceNotFoundException
     * @throws ThrottlingException
     */
    public function listThingGroupsForThing($input): ListThingGroupsForThingResponse
    {
        $input = ListThingGroupsForThingRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'ListThingGroupsForThing', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'InvalidRequestException' => InvalidRequestException::class,
            'InternalFailureException' => InternalFailureException::class,
            'ResourceNotFoundException' => ResourceNotFoundException::class,
            'ThrottlingException' => ThrottlingException::class,
        ]]));

        return new ListThingGroupsForThingResponse($response, $this, $input);
    }

    /**
     * Lists the existing thing types.
     *
     * @see https://docs.aws.amazon.com/iot/latest/apireference/API_ListThingTypes.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-iot-2015-05-28.html#listthingtypes
     *
     * @param array{
     *   nextToken?: string,
     *   maxResults?: int,
     *   thingTypeName?: string,
     *   @region?: string,
     * }|ListThingTypesRequest $input
     *
     * @throws InvalidRequestException
     * @throws ThrottlingException
     * @throws UnauthorizedException
     * @throws ServiceUnavailableException
     * @throws InternalFailureException
     */
    public function listThingTypes($input = []): ListThingTypesResponse
    {
        $input = ListThingTypesRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'ListThingTypes', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'InvalidRequestException' => InvalidRequestException::class,
            'ThrottlingException' => ThrottlingException::class,
            'UnauthorizedException' => UnauthorizedException::class,
            'ServiceUnavailableException' => ServiceUnavailableException::class,
            'InternalFailureException' => InternalFailureException::class,
        ]]));

        return new ListThingTypesResponse($response, $this, $input);
    }

    /**
     * Lists your things. Use the **attributeName** and **attributeValue** parameters to filter your things. For example,
     * calling `ListThings` with attributeName=Color and attributeValue=Red retrieves all things in the registry that
     * contain an attribute **Color** with the value **Red**. For more information, see List Things from the *Amazon Web
     * Services IoT Core Developer Guide*.
     *
     * @see https://docs.aws.amazon.com/iot/latest/developerguide/thing-registry.html#list-things
     * @see https://docs.aws.amazon.com/iot/latest/apireference/API_ListThings.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-iot-2015-05-28.html#listthings
     *
     * @param array{
     *   nextToken?: string,
     *   maxResults?: int,
     *   attributeName?: string,
     *   attributeValue?: string,
     *   thingTypeName?: string,
     *   usePrefixAttributeValue?: bool,
     *   @region?: string,
     * }|ListThingsRequest $input
     *
     * @throws InvalidRequestException
     * @throws ThrottlingException
     * @throws UnauthorizedException
     * @throws ServiceUnavailableException
     * @throws InternalFailureException
     */
    public function listThings($input = []): ListThingsResponse
    {
        $input = ListThingsRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'ListThings', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'InvalidRequestException' => InvalidRequestException::class,
            'ThrottlingException' => ThrottlingException::class,
            'UnauthorizedException' => UnauthorizedException::class,
            'ServiceUnavailableException' => ServiceUnavailableException::class,
            'InternalFailureException' => InternalFailureException::class,
        ]]));

        return new ListThingsResponse($response, $this, $input);
    }

    /**
     * Lists the things in the specified group.
     *
     * @see https://docs.aws.amazon.com/iot/latest/apireference/API_ListThingsInThingGroup.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-iot-2015-05-28.html#listthingsinthinggroup
     *
     * @param array{
     *   thingGroupName: string,
     *   recursive?: bool,
     *   nextToken?: string,
     *   maxResults?: int,
     *   @region?: string,
     * }|ListThingsInThingGroupRequest $input
     *
     * @throws InvalidRequestException
     * @throws InternalFailureException
     * @throws ResourceNotFoundException
     * @throws ThrottlingException
     */
    public function listThingsInThingGroup($input): ListThingsInThingGroupResponse
    {
        $input = ListThingsInThingGroupRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'ListThingsInThingGroup', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'InvalidRequestException' => InvalidRequestException::class,
            'InternalFailureException' => InternalFailureException::class,
            'ResourceNotFoundException' => ResourceNotFoundException::class,
            'ThrottlingException' => ThrottlingException::class,
        ]]));

        return new ListThingsInThingGroupResponse($response, $this, $input);
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
            case 'cn-northwest-1':
                return [
                    'endpoint' => "https://iot.$region.amazonaws.com.cn",
                    'signRegion' => $region,
                    'signService' => 'execute-api',
                    'signVersions' => ['v4'],
                ];
            case 'fips-ca-central-1':
                return [
                    'endpoint' => 'https://iot-fips.ca-central-1.amazonaws.com',
                    'signRegion' => 'fips-ca-central-1',
                    'signService' => 'execute-api',
                    'signVersions' => ['v4'],
                ];
            case 'fips-us-east-1':
                return [
                    'endpoint' => 'https://iot-fips.us-east-1.amazonaws.com',
                    'signRegion' => 'fips-us-east-1',
                    'signService' => 'execute-api',
                    'signVersions' => ['v4'],
                ];
            case 'fips-us-east-2':
                return [
                    'endpoint' => 'https://iot-fips.us-east-2.amazonaws.com',
                    'signRegion' => 'fips-us-east-2',
                    'signService' => 'execute-api',
                    'signVersions' => ['v4'],
                ];
            case 'fips-us-west-1':
                return [
                    'endpoint' => 'https://iot-fips.us-west-1.amazonaws.com',
                    'signRegion' => 'fips-us-west-1',
                    'signService' => 'execute-api',
                    'signVersions' => ['v4'],
                ];
            case 'fips-us-west-2':
                return [
                    'endpoint' => 'https://iot-fips.us-west-2.amazonaws.com',
                    'signRegion' => 'fips-us-west-2',
                    'signService' => 'execute-api',
                    'signVersions' => ['v4'],
                ];
            case 'fips-us-gov-east-1':
                return [
                    'endpoint' => 'https://iot-fips.us-gov-east-1.amazonaws.com',
                    'signRegion' => 'fips-us-gov-east-1',
                    'signService' => 'execute-api',
                    'signVersions' => ['v4'],
                ];
            case 'fips-us-gov-west-1':
                return [
                    'endpoint' => 'https://iot-fips.us-gov-west-1.amazonaws.com',
                    'signRegion' => 'fips-us-gov-west-1',
                    'signService' => 'execute-api',
                    'signVersions' => ['v4'],
                ];
        }

        return [
            'endpoint' => "https://iot.$region.amazonaws.com",
            'signRegion' => $region,
            'signService' => 'execute-api',
            'signVersions' => ['v4'],
        ];
    }
}
