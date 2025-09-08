<?php

namespace AsyncAws\Scheduler;

use AsyncAws\Core\AbstractApi;
use AsyncAws\Core\AwsError\AwsErrorFactoryInterface;
use AsyncAws\Core\AwsError\JsonRestAwsErrorFactory;
use AsyncAws\Core\Configuration;
use AsyncAws\Core\RequestContext;
use AsyncAws\Scheduler\Enum\ActionAfterCompletion;
use AsyncAws\Scheduler\Enum\ScheduleState;
use AsyncAws\Scheduler\Exception\ConflictException;
use AsyncAws\Scheduler\Exception\InternalServerException;
use AsyncAws\Scheduler\Exception\ResourceNotFoundException;
use AsyncAws\Scheduler\Exception\ServiceQuotaExceededException;
use AsyncAws\Scheduler\Exception\ThrottlingException;
use AsyncAws\Scheduler\Exception\ValidationException;
use AsyncAws\Scheduler\Input\CreateScheduleGroupInput;
use AsyncAws\Scheduler\Input\CreateScheduleInput;
use AsyncAws\Scheduler\Input\DeleteScheduleGroupInput;
use AsyncAws\Scheduler\Input\DeleteScheduleInput;
use AsyncAws\Scheduler\Input\GetScheduleGroupInput;
use AsyncAws\Scheduler\Input\GetScheduleInput;
use AsyncAws\Scheduler\Input\ListScheduleGroupsInput;
use AsyncAws\Scheduler\Input\ListSchedulesInput;
use AsyncAws\Scheduler\Input\UpdateScheduleInput;
use AsyncAws\Scheduler\Result\CreateScheduleGroupOutput;
use AsyncAws\Scheduler\Result\CreateScheduleOutput;
use AsyncAws\Scheduler\Result\DeleteScheduleGroupOutput;
use AsyncAws\Scheduler\Result\DeleteScheduleOutput;
use AsyncAws\Scheduler\Result\GetScheduleGroupOutput;
use AsyncAws\Scheduler\Result\GetScheduleOutput;
use AsyncAws\Scheduler\Result\ListScheduleGroupsOutput;
use AsyncAws\Scheduler\Result\ListSchedulesOutput;
use AsyncAws\Scheduler\Result\UpdateScheduleOutput;
use AsyncAws\Scheduler\ValueObject\FlexibleTimeWindow;
use AsyncAws\Scheduler\ValueObject\Tag;
use AsyncAws\Scheduler\ValueObject\Target;

class SchedulerClient extends AbstractApi
{
    /**
     * Creates the specified schedule.
     *
     * @see https://docs.aws.amazon.com/scheduler/latest/APIReference/API_CreateSchedule.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-scheduler-2021-06-30.html#createschedule
     *
     * @param array{
     *   ActionAfterCompletion?: ActionAfterCompletion::*|null,
     *   ClientToken?: string|null,
     *   Description?: string|null,
     *   EndDate?: \DateTimeImmutable|string|null,
     *   FlexibleTimeWindow: FlexibleTimeWindow|array,
     *   GroupName?: string|null,
     *   KmsKeyArn?: string|null,
     *   Name: string,
     *   ScheduleExpression: string,
     *   ScheduleExpressionTimezone?: string|null,
     *   StartDate?: \DateTimeImmutable|string|null,
     *   State?: ScheduleState::*|null,
     *   Target: Target|array,
     *   '@region'?: string|null,
     * }|CreateScheduleInput $input
     *
     * @throws ConflictException
     * @throws InternalServerException
     * @throws ResourceNotFoundException
     * @throws ServiceQuotaExceededException
     * @throws ThrottlingException
     * @throws ValidationException
     */
    public function createSchedule($input): CreateScheduleOutput
    {
        $input = CreateScheduleInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'CreateSchedule', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'ConflictException' => ConflictException::class,
            'InternalServerException' => InternalServerException::class,
            'ResourceNotFoundException' => ResourceNotFoundException::class,
            'ServiceQuotaExceededException' => ServiceQuotaExceededException::class,
            'ThrottlingException' => ThrottlingException::class,
            'ValidationException' => ValidationException::class,
        ]]));

        return new CreateScheduleOutput($response);
    }

    /**
     * Creates the specified schedule group.
     *
     * @see https://docs.aws.amazon.com/scheduler/latest/APIReference/API_CreateScheduleGroup.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-scheduler-2021-06-30.html#createschedulegroup
     *
     * @param array{
     *   ClientToken?: string|null,
     *   Name: string,
     *   Tags?: array<Tag|array>|null,
     *   '@region'?: string|null,
     * }|CreateScheduleGroupInput $input
     *
     * @throws ConflictException
     * @throws InternalServerException
     * @throws ServiceQuotaExceededException
     * @throws ThrottlingException
     * @throws ValidationException
     */
    public function createScheduleGroup($input): CreateScheduleGroupOutput
    {
        $input = CreateScheduleGroupInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'CreateScheduleGroup', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'ConflictException' => ConflictException::class,
            'InternalServerException' => InternalServerException::class,
            'ServiceQuotaExceededException' => ServiceQuotaExceededException::class,
            'ThrottlingException' => ThrottlingException::class,
            'ValidationException' => ValidationException::class,
        ]]));

        return new CreateScheduleGroupOutput($response);
    }

    /**
     * Deletes the specified schedule.
     *
     * @see https://docs.aws.amazon.com/scheduler/latest/APIReference/API_DeleteSchedule.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-scheduler-2021-06-30.html#deleteschedule
     *
     * @param array{
     *   ClientToken?: string|null,
     *   GroupName?: string|null,
     *   Name: string,
     *   '@region'?: string|null,
     * }|DeleteScheduleInput $input
     *
     * @throws ConflictException
     * @throws InternalServerException
     * @throws ResourceNotFoundException
     * @throws ThrottlingException
     * @throws ValidationException
     */
    public function deleteSchedule($input): DeleteScheduleOutput
    {
        $input = DeleteScheduleInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'DeleteSchedule', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'ConflictException' => ConflictException::class,
            'InternalServerException' => InternalServerException::class,
            'ResourceNotFoundException' => ResourceNotFoundException::class,
            'ThrottlingException' => ThrottlingException::class,
            'ValidationException' => ValidationException::class,
        ]]));

        return new DeleteScheduleOutput($response);
    }

    /**
     * Deletes the specified schedule group. Deleting a schedule group results in EventBridge Scheduler deleting all
     * schedules associated with the group. When you delete a group, it remains in a `DELETING` state until all of its
     * associated schedules are deleted. Schedules associated with the group that are set to run while the schedule group is
     * in the process of being deleted might continue to invoke their targets until the schedule group and its associated
     * schedules are deleted.
     *
     * > This operation is eventually consistent.
     *
     * @see https://docs.aws.amazon.com/scheduler/latest/APIReference/API_DeleteScheduleGroup.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-scheduler-2021-06-30.html#deleteschedulegroup
     *
     * @param array{
     *   ClientToken?: string|null,
     *   Name: string,
     *   '@region'?: string|null,
     * }|DeleteScheduleGroupInput $input
     *
     * @throws ConflictException
     * @throws InternalServerException
     * @throws ResourceNotFoundException
     * @throws ThrottlingException
     * @throws ValidationException
     */
    public function deleteScheduleGroup($input): DeleteScheduleGroupOutput
    {
        $input = DeleteScheduleGroupInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'DeleteScheduleGroup', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'ConflictException' => ConflictException::class,
            'InternalServerException' => InternalServerException::class,
            'ResourceNotFoundException' => ResourceNotFoundException::class,
            'ThrottlingException' => ThrottlingException::class,
            'ValidationException' => ValidationException::class,
        ]]));

        return new DeleteScheduleGroupOutput($response);
    }

    /**
     * Retrieves the specified schedule.
     *
     * @see https://docs.aws.amazon.com/scheduler/latest/APIReference/API_GetSchedule.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-scheduler-2021-06-30.html#getschedule
     *
     * @param array{
     *   GroupName?: string|null,
     *   Name: string,
     *   '@region'?: string|null,
     * }|GetScheduleInput $input
     *
     * @throws InternalServerException
     * @throws ResourceNotFoundException
     * @throws ThrottlingException
     * @throws ValidationException
     */
    public function getSchedule($input): GetScheduleOutput
    {
        $input = GetScheduleInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'GetSchedule', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'InternalServerException' => InternalServerException::class,
            'ResourceNotFoundException' => ResourceNotFoundException::class,
            'ThrottlingException' => ThrottlingException::class,
            'ValidationException' => ValidationException::class,
        ]]));

        return new GetScheduleOutput($response);
    }

    /**
     * Retrieves the specified schedule group.
     *
     * @see https://docs.aws.amazon.com/scheduler/latest/APIReference/API_GetScheduleGroup.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-scheduler-2021-06-30.html#getschedulegroup
     *
     * @param array{
     *   Name: string,
     *   '@region'?: string|null,
     * }|GetScheduleGroupInput $input
     *
     * @throws InternalServerException
     * @throws ResourceNotFoundException
     * @throws ThrottlingException
     * @throws ValidationException
     */
    public function getScheduleGroup($input): GetScheduleGroupOutput
    {
        $input = GetScheduleGroupInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'GetScheduleGroup', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'InternalServerException' => InternalServerException::class,
            'ResourceNotFoundException' => ResourceNotFoundException::class,
            'ThrottlingException' => ThrottlingException::class,
            'ValidationException' => ValidationException::class,
        ]]));

        return new GetScheduleGroupOutput($response);
    }

    /**
     * Returns a paginated list of your schedule groups.
     *
     * @see https://docs.aws.amazon.com/scheduler/latest/APIReference/API_ListScheduleGroups.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-scheduler-2021-06-30.html#listschedulegroups
     *
     * @param array{
     *   MaxResults?: int|null,
     *   NamePrefix?: string|null,
     *   NextToken?: string|null,
     *   '@region'?: string|null,
     * }|ListScheduleGroupsInput $input
     *
     * @throws InternalServerException
     * @throws ThrottlingException
     * @throws ValidationException
     */
    public function listScheduleGroups($input = []): ListScheduleGroupsOutput
    {
        $input = ListScheduleGroupsInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'ListScheduleGroups', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'InternalServerException' => InternalServerException::class,
            'ThrottlingException' => ThrottlingException::class,
            'ValidationException' => ValidationException::class,
        ]]));

        return new ListScheduleGroupsOutput($response, $this, $input);
    }

    /**
     * Returns a paginated list of your EventBridge Scheduler schedules.
     *
     * @see https://docs.aws.amazon.com/scheduler/latest/APIReference/API_ListSchedules.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-scheduler-2021-06-30.html#listschedules
     *
     * @param array{
     *   GroupName?: string|null,
     *   MaxResults?: int|null,
     *   NamePrefix?: string|null,
     *   NextToken?: string|null,
     *   State?: ScheduleState::*|null,
     *   '@region'?: string|null,
     * }|ListSchedulesInput $input
     *
     * @throws InternalServerException
     * @throws ResourceNotFoundException
     * @throws ThrottlingException
     * @throws ValidationException
     */
    public function listSchedules($input = []): ListSchedulesOutput
    {
        $input = ListSchedulesInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'ListSchedules', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'InternalServerException' => InternalServerException::class,
            'ResourceNotFoundException' => ResourceNotFoundException::class,
            'ThrottlingException' => ThrottlingException::class,
            'ValidationException' => ValidationException::class,
        ]]));

        return new ListSchedulesOutput($response, $this, $input);
    }

    /**
     * Updates the specified schedule. When you call `UpdateSchedule`, EventBridge Scheduler uses all values, including
     * empty values, specified in the request and overrides the existing schedule. This is by design. This means that if you
     * do not set an optional field in your request, that field will be set to its system-default value after the update.
     *
     * Before calling this operation, we recommend that you call the `GetSchedule` API operation and make a note of all
     * optional parameters for your `UpdateSchedule` call.
     *
     * @see https://docs.aws.amazon.com/scheduler/latest/APIReference/API_UpdateSchedule.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-scheduler-2021-06-30.html#updateschedule
     *
     * @param array{
     *   ActionAfterCompletion?: ActionAfterCompletion::*|null,
     *   ClientToken?: string|null,
     *   Description?: string|null,
     *   EndDate?: \DateTimeImmutable|string|null,
     *   FlexibleTimeWindow: FlexibleTimeWindow|array,
     *   GroupName?: string|null,
     *   KmsKeyArn?: string|null,
     *   Name: string,
     *   ScheduleExpression: string,
     *   ScheduleExpressionTimezone?: string|null,
     *   StartDate?: \DateTimeImmutable|string|null,
     *   State?: ScheduleState::*|null,
     *   Target: Target|array,
     *   '@region'?: string|null,
     * }|UpdateScheduleInput $input
     *
     * @throws ConflictException
     * @throws InternalServerException
     * @throws ResourceNotFoundException
     * @throws ThrottlingException
     * @throws ValidationException
     */
    public function updateSchedule($input): UpdateScheduleOutput
    {
        $input = UpdateScheduleInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'UpdateSchedule', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'ConflictException' => ConflictException::class,
            'InternalServerException' => InternalServerException::class,
            'ResourceNotFoundException' => ResourceNotFoundException::class,
            'ThrottlingException' => ThrottlingException::class,
            'ValidationException' => ValidationException::class,
        ]]));

        return new UpdateScheduleOutput($response);
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
                    'endpoint' => "https://scheduler.$region.amazonaws.com.cn",
                    'signRegion' => $region,
                    'signService' => 'scheduler',
                    'signVersions' => ['v4'],
                ];
            case 'us-iso-east-1':
            case 'us-iso-west-1':
                return [
                    'endpoint' => "https://scheduler.$region.c2s.ic.gov",
                    'signRegion' => $region,
                    'signService' => 'scheduler',
                    'signVersions' => ['v4'],
                ];
            case 'us-isof-east-1':
            case 'us-isof-south-1':
                return [
                    'endpoint' => "https://scheduler.$region.csp.hci.ic.gov",
                    'signRegion' => $region,
                    'signService' => 'scheduler',
                    'signVersions' => ['v4'],
                ];
            case 'eu-isoe-west-1':
                return [
                    'endpoint' => 'https://scheduler.eu-isoe-west-1.cloud.adc-e.uk',
                    'signRegion' => 'eu-isoe-west-1',
                    'signService' => 'scheduler',
                    'signVersions' => ['v4'],
                ];
            case 'us-isob-east-1':
                return [
                    'endpoint' => 'https://scheduler.us-isob-east-1.sc2s.sgov.gov',
                    'signRegion' => 'us-isob-east-1',
                    'signService' => 'scheduler',
                    'signVersions' => ['v4'],
                ];
        }

        return [
            'endpoint' => "https://scheduler.$region.amazonaws.com",
            'signRegion' => $region,
            'signService' => 'scheduler',
            'signVersions' => ['v4'],
        ];
    }
}
