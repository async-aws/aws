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
     * Requires permission to access the AddThingToThingGroup [^1] action.
     *
     * [^1]: https://docs.aws.amazon.com/service-authorization/latest/reference/list_awsiot.html#awsiot-actions-as-permissions
     *
     * @see https://docs.aws.amazon.com/iot/latest/apireference/API_AddThingToThingGroup.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-iot-2015-05-28.html#addthingtothinggroup
     *
     * @param array{
     *   thingGroupName?: null|string,
     *   thingGroupArn?: null|string,
     *   thingName?: null|string,
     *   thingArn?: null|string,
     *   overrideDynamicGroups?: null|bool,
     *   '@region'?: string|null,
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
     * > This is a control plane operation. See Authorization [^1] for information about authorizing control plane actions.
     *
     * Requires permission to access the CreateThing [^2] action.
     *
     * [^1]: https://docs.aws.amazon.com/iot/latest/developerguide/iot-authorization.html
     * [^2]: https://docs.aws.amazon.com/service-authorization/latest/reference/list_awsiot.html#awsiot-actions-as-permissions
     *
     * @see https://docs.aws.amazon.com/iot/latest/apireference/API_CreateThing.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-iot-2015-05-28.html#creatething
     *
     * @param array{
     *   thingName: string,
     *   thingTypeName?: null|string,
     *   attributePayload?: null|AttributePayload|array,
     *   billingGroupName?: null|string,
     *   '@region'?: string|null,
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
     * > This is a control plane operation. See Authorization [^1] for information about authorizing control plane actions.
     *
     * Requires permission to access the CreateThingGroup [^2] action.
     *
     * [^1]: https://docs.aws.amazon.com/iot/latest/developerguide/iot-authorization.html
     * [^2]: https://docs.aws.amazon.com/service-authorization/latest/reference/list_awsiot.html#awsiot-actions-as-permissions
     *
     * @see https://docs.aws.amazon.com/iot/latest/apireference/API_CreateThingGroup.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-iot-2015-05-28.html#createthinggroup
     *
     * @param array{
     *   thingGroupName: string,
     *   parentGroupName?: null|string,
     *   thingGroupProperties?: null|ThingGroupProperties|array,
     *   tags?: null|array<Tag|array>,
     *   '@region'?: string|null,
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
     * Requires permission to access the CreateThingType [^1] action.
     *
     * [^1]: https://docs.aws.amazon.com/service-authorization/latest/reference/list_awsiot.html#awsiot-actions-as-permissions
     *
     * @see https://docs.aws.amazon.com/iot/latest/apireference/API_CreateThingType.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-iot-2015-05-28.html#createthingtype
     *
     * @param array{
     *   thingTypeName: string,
     *   thingTypeProperties?: null|ThingTypeProperties|array,
     *   tags?: null|array<Tag|array>,
     *   '@region'?: string|null,
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
     * Requires permission to access the DeleteThing [^1] action.
     *
     * [^1]: https://docs.aws.amazon.com/service-authorization/latest/reference/list_awsiot.html#awsiot-actions-as-permissions
     *
     * @see https://docs.aws.amazon.com/iot/latest/apireference/API_DeleteThing.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-iot-2015-05-28.html#deletething
     *
     * @param array{
     *   thingName: string,
     *   expectedVersion?: null|int,
     *   '@region'?: string|null,
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
     * Requires permission to access the DeleteThingGroup [^1] action.
     *
     * [^1]: https://docs.aws.amazon.com/service-authorization/latest/reference/list_awsiot.html#awsiot-actions-as-permissions
     *
     * @see https://docs.aws.amazon.com/iot/latest/apireference/API_DeleteThingGroup.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-iot-2015-05-28.html#deletethinggroup
     *
     * @param array{
     *   thingGroupName: string,
     *   expectedVersion?: null|int,
     *   '@region'?: string|null,
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
     * Requires permission to access the DeleteThingType [^1] action.
     *
     * [^1]: https://docs.aws.amazon.com/service-authorization/latest/reference/list_awsiot.html#awsiot-actions-as-permissions
     *
     * @see https://docs.aws.amazon.com/iot/latest/apireference/API_DeleteThingType.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-iot-2015-05-28.html#deletethingtype
     *
     * @param array{
     *   thingTypeName: string,
     *   '@region'?: string|null,
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
     * Requires permission to access the ListThingGroups [^1] action.
     *
     * [^1]: https://docs.aws.amazon.com/service-authorization/latest/reference/list_awsiot.html#awsiot-actions-as-permissions
     *
     * @see https://docs.aws.amazon.com/iot/latest/apireference/API_ListThingGroups.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-iot-2015-05-28.html#listthinggroups
     *
     * @param array{
     *   nextToken?: null|string,
     *   maxResults?: null|int,
     *   parentGroup?: null|string,
     *   namePrefixFilter?: null|string,
     *   recursive?: null|bool,
     *   '@region'?: string|null,
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
     * Requires permission to access the ListThingGroupsForThing [^1] action.
     *
     * [^1]: https://docs.aws.amazon.com/service-authorization/latest/reference/list_awsiot.html#awsiot-actions-as-permissions
     *
     * @see https://docs.aws.amazon.com/iot/latest/apireference/API_ListThingGroupsForThing.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-iot-2015-05-28.html#listthinggroupsforthing
     *
     * @param array{
     *   thingName: string,
     *   nextToken?: null|string,
     *   maxResults?: null|int,
     *   '@region'?: string|null,
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
     * Requires permission to access the ListThingTypes [^1] action.
     *
     * [^1]: https://docs.aws.amazon.com/service-authorization/latest/reference/list_awsiot.html#awsiot-actions-as-permissions
     *
     * @see https://docs.aws.amazon.com/iot/latest/apireference/API_ListThingTypes.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-iot-2015-05-28.html#listthingtypes
     *
     * @param array{
     *   nextToken?: null|string,
     *   maxResults?: null|int,
     *   thingTypeName?: null|string,
     *   '@region'?: string|null,
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
     * contain an attribute **Color** with the value **Red**. For more information, see List Things [^1] from the *Amazon
     * Web Services IoT Core Developer Guide*.
     *
     * Requires permission to access the ListThings [^2] action.
     *
     * > You will not be charged for calling this API if an `Access denied` error is returned. You will also not be charged
     * > if no attributes or pagination token was provided in request and no pagination token and no results were returned.
     *
     * [^1]: https://docs.aws.amazon.com/iot/latest/developerguide/thing-registry.html#list-things
     * [^2]: https://docs.aws.amazon.com/service-authorization/latest/reference/list_awsiot.html#awsiot-actions-as-permissions
     *
     * @see https://docs.aws.amazon.com/iot/latest/apireference/API_ListThings.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-iot-2015-05-28.html#listthings
     *
     * @param array{
     *   nextToken?: null|string,
     *   maxResults?: null|int,
     *   attributeName?: null|string,
     *   attributeValue?: null|string,
     *   thingTypeName?: null|string,
     *   usePrefixAttributeValue?: null|bool,
     *   '@region'?: string|null,
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
     * Requires permission to access the ListThingsInThingGroup [^1] action.
     *
     * [^1]: https://docs.aws.amazon.com/service-authorization/latest/reference/list_awsiot.html#awsiot-actions-as-permissions
     *
     * @see https://docs.aws.amazon.com/iot/latest/apireference/API_ListThingsInThingGroup.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-iot-2015-05-28.html#listthingsinthinggroup
     *
     * @param array{
     *   thingGroupName: string,
     *   recursive?: null|bool,
     *   nextToken?: null|string,
     *   maxResults?: null|int,
     *   '@region'?: string|null,
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
                    'signService' => 'iot',
                    'signVersions' => ['v4'],
                ];
            case 'fips-ca-central-1':
                return [
                    'endpoint' => 'https://iot-fips.ca-central-1.amazonaws.com',
                    'signRegion' => 'fips-ca-central-1',
                    'signService' => 'iot',
                    'signVersions' => ['v4'],
                ];
            case 'fips-us-east-1':
                return [
                    'endpoint' => 'https://iot-fips.us-east-1.amazonaws.com',
                    'signRegion' => 'fips-us-east-1',
                    'signService' => 'iot',
                    'signVersions' => ['v4'],
                ];
            case 'fips-us-east-2':
                return [
                    'endpoint' => 'https://iot-fips.us-east-2.amazonaws.com',
                    'signRegion' => 'fips-us-east-2',
                    'signService' => 'iot',
                    'signVersions' => ['v4'],
                ];
            case 'fips-us-west-1':
                return [
                    'endpoint' => 'https://iot-fips.us-west-1.amazonaws.com',
                    'signRegion' => 'fips-us-west-1',
                    'signService' => 'iot',
                    'signVersions' => ['v4'],
                ];
            case 'fips-us-west-2':
                return [
                    'endpoint' => 'https://iot-fips.us-west-2.amazonaws.com',
                    'signRegion' => 'fips-us-west-2',
                    'signService' => 'iot',
                    'signVersions' => ['v4'],
                ];
            case 'fips-us-gov-east-1':
                return [
                    'endpoint' => 'https://iot-fips.us-gov-east-1.amazonaws.com',
                    'signRegion' => 'fips-us-gov-east-1',
                    'signService' => 'iot',
                    'signVersions' => ['v4'],
                ];
            case 'fips-us-gov-west-1':
                return [
                    'endpoint' => 'https://iot-fips.us-gov-west-1.amazonaws.com',
                    'signRegion' => 'fips-us-gov-west-1',
                    'signService' => 'iot',
                    'signVersions' => ['v4'],
                ];
        }

        return [
            'endpoint' => "https://iot.$region.amazonaws.com",
            'signRegion' => $region,
            'signService' => 'iot',
            'signVersions' => ['v4'],
        ];
    }
}
