<?php

namespace AsyncAws\StepFunctions;

use AsyncAws\Core\AbstractApi;
use AsyncAws\Core\AwsError\AwsErrorFactoryInterface;
use AsyncAws\Core\AwsError\JsonRpcAwsErrorFactory;
use AsyncAws\Core\Configuration;
use AsyncAws\Core\RequestContext;
use AsyncAws\StepFunctions\Exception\ExecutionAlreadyExistsException;
use AsyncAws\StepFunctions\Exception\ExecutionDoesNotExistException;
use AsyncAws\StepFunctions\Exception\ExecutionLimitExceededException;
use AsyncAws\StepFunctions\Exception\InvalidArnException;
use AsyncAws\StepFunctions\Exception\InvalidExecutionInputException;
use AsyncAws\StepFunctions\Exception\InvalidNameException;
use AsyncAws\StepFunctions\Exception\InvalidOutputException;
use AsyncAws\StepFunctions\Exception\InvalidTokenException;
use AsyncAws\StepFunctions\Exception\StateMachineDeletingException;
use AsyncAws\StepFunctions\Exception\StateMachineDoesNotExistException;
use AsyncAws\StepFunctions\Exception\TaskDoesNotExistException;
use AsyncAws\StepFunctions\Exception\TaskTimedOutException;
use AsyncAws\StepFunctions\Input\SendTaskFailureInput;
use AsyncAws\StepFunctions\Input\SendTaskHeartbeatInput;
use AsyncAws\StepFunctions\Input\SendTaskSuccessInput;
use AsyncAws\StepFunctions\Input\StartExecutionInput;
use AsyncAws\StepFunctions\Input\StopExecutionInput;
use AsyncAws\StepFunctions\Result\SendTaskFailureOutput;
use AsyncAws\StepFunctions\Result\SendTaskHeartbeatOutput;
use AsyncAws\StepFunctions\Result\SendTaskSuccessOutput;
use AsyncAws\StepFunctions\Result\StartExecutionOutput;
use AsyncAws\StepFunctions\Result\StopExecutionOutput;

class StepFunctionsClient extends AbstractApi
{
    /**
     * Used by activity workers and task states using the callback pattern to report that the task identified by the
     * `taskToken` failed.
     *
     * @see https://docs.aws.amazon.com/step-functions/latest/dg/connect-to-resource.html#connect-wait-token
     * @see https://docs.aws.amazon.com/step-functions/latest/apireference/API_SendTaskFailure.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-states-2016-11-23.html#sendtaskfailure
     *
     * @param array{
     *   taskToken: string,
     *   error?: string,
     *   cause?: string,
     *   @region?: string,
     * }|SendTaskFailureInput $input
     *
     * @throws TaskDoesNotExistException
     * @throws InvalidTokenException
     * @throws TaskTimedOutException
     */
    public function sendTaskFailure($input): SendTaskFailureOutput
    {
        $input = SendTaskFailureInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'SendTaskFailure', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'TaskDoesNotExist' => TaskDoesNotExistException::class,
            'InvalidToken' => InvalidTokenException::class,
            'TaskTimedOut' => TaskTimedOutException::class,
        ]]));

        return new SendTaskFailureOutput($response);
    }

    /**
     * Used by activity workers and task states using the callback pattern to report to Step Functions that the task
     * represented by the specified `taskToken` is still making progress. This action resets the `Heartbeat` clock. The
     * `Heartbeat` threshold is specified in the state machine's Amazon States Language definition (`HeartbeatSeconds`).
     * This action does not in itself create an event in the execution history. However, if the task times out, the
     * execution history contains an `ActivityTimedOut` entry for activities, or a `TaskTimedOut` entry for for tasks using
     * the job run or callback pattern.
     *
     * @see https://docs.aws.amazon.com/step-functions/latest/dg/connect-to-resource.html#connect-wait-token
     * @see https://docs.aws.amazon.com/step-functions/latest/dg/connect-to-resource.html#connect-sync
     * @see https://docs.aws.amazon.com/step-functions/latest/dg/connect-to-resource.html#connect-wait-token
     * @see https://docs.aws.amazon.com/step-functions/latest/apireference/API_SendTaskHeartbeat.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-states-2016-11-23.html#sendtaskheartbeat
     *
     * @param array{
     *   taskToken: string,
     *   @region?: string,
     * }|SendTaskHeartbeatInput $input
     *
     * @throws TaskDoesNotExistException
     * @throws InvalidTokenException
     * @throws TaskTimedOutException
     */
    public function sendTaskHeartbeat($input): SendTaskHeartbeatOutput
    {
        $input = SendTaskHeartbeatInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'SendTaskHeartbeat', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'TaskDoesNotExist' => TaskDoesNotExistException::class,
            'InvalidToken' => InvalidTokenException::class,
            'TaskTimedOut' => TaskTimedOutException::class,
        ]]));

        return new SendTaskHeartbeatOutput($response);
    }

    /**
     * Used by activity workers and task states using the callback pattern to report that the task identified by the
     * `taskToken` completed successfully.
     *
     * @see https://docs.aws.amazon.com/step-functions/latest/dg/connect-to-resource.html#connect-wait-token
     * @see https://docs.aws.amazon.com/step-functions/latest/apireference/API_SendTaskSuccess.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-states-2016-11-23.html#sendtasksuccess
     *
     * @param array{
     *   taskToken: string,
     *   output: string,
     *   @region?: string,
     * }|SendTaskSuccessInput $input
     *
     * @throws TaskDoesNotExistException
     * @throws InvalidOutputException
     * @throws InvalidTokenException
     * @throws TaskTimedOutException
     */
    public function sendTaskSuccess($input): SendTaskSuccessOutput
    {
        $input = SendTaskSuccessInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'SendTaskSuccess', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'TaskDoesNotExist' => TaskDoesNotExistException::class,
            'InvalidOutput' => InvalidOutputException::class,
            'InvalidToken' => InvalidTokenException::class,
            'TaskTimedOut' => TaskTimedOutException::class,
        ]]));

        return new SendTaskSuccessOutput($response);
    }

    /**
     * Starts a state machine execution.
     *
     * @see https://docs.aws.amazon.com/step-functions/latest/apireference/API_StartExecution.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-states-2016-11-23.html#startexecution
     *
     * @param array{
     *   stateMachineArn: string,
     *   name?: string,
     *   input?: string,
     *   traceHeader?: string,
     *   @region?: string,
     * }|StartExecutionInput $input
     *
     * @throws ExecutionLimitExceededException
     * @throws ExecutionAlreadyExistsException
     * @throws InvalidArnException
     * @throws InvalidExecutionInputException
     * @throws InvalidNameException
     * @throws StateMachineDoesNotExistException
     * @throws StateMachineDeletingException
     */
    public function startExecution($input): StartExecutionOutput
    {
        $input = StartExecutionInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'StartExecution', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'ExecutionLimitExceeded' => ExecutionLimitExceededException::class,
            'ExecutionAlreadyExists' => ExecutionAlreadyExistsException::class,
            'InvalidArn' => InvalidArnException::class,
            'InvalidExecutionInput' => InvalidExecutionInputException::class,
            'InvalidName' => InvalidNameException::class,
            'StateMachineDoesNotExist' => StateMachineDoesNotExistException::class,
            'StateMachineDeleting' => StateMachineDeletingException::class,
        ]]));

        return new StartExecutionOutput($response);
    }

    /**
     * Stops an execution.
     *
     * @see https://docs.aws.amazon.com/step-functions/latest/apireference/API_StopExecution.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-states-2016-11-23.html#stopexecution
     *
     * @param array{
     *   executionArn: string,
     *   error?: string,
     *   cause?: string,
     *   @region?: string,
     * }|StopExecutionInput $input
     *
     * @throws ExecutionDoesNotExistException
     * @throws InvalidArnException
     */
    public function stopExecution($input): StopExecutionOutput
    {
        $input = StopExecutionInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'StopExecution', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'ExecutionDoesNotExist' => ExecutionDoesNotExistException::class,
            'InvalidArn' => InvalidArnException::class,
        ]]));

        return new StopExecutionOutput($response);
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
            case 'cn-north-1':
            case 'cn-northwest-1':
                return [
                    'endpoint' => "https://states.$region.amazonaws.com.cn",
                    'signRegion' => $region,
                    'signService' => 'states',
                    'signVersions' => ['v4'],
                ];
            case 'us-iso-east-1':
            case 'us-iso-west-1':
                return [
                    'endpoint' => "https://states.$region.c2s.ic.gov",
                    'signRegion' => $region,
                    'signService' => 'states',
                    'signVersions' => ['v4'],
                ];
            case 'us-isob-east-1':
                return [
                    'endpoint' => 'https://states.us-isob-east-1.sc2s.sgov.gov',
                    'signRegion' => 'us-isob-east-1',
                    'signService' => 'states',
                    'signVersions' => ['v4'],
                ];
            case 'fips-us-east-1':
                return [
                    'endpoint' => 'https://states-fips.us-east-1.amazonaws.com',
                    'signRegion' => 'us-east-1',
                    'signService' => 'states',
                    'signVersions' => ['v4'],
                ];
            case 'fips-us-east-2':
                return [
                    'endpoint' => 'https://states-fips.us-east-2.amazonaws.com',
                    'signRegion' => 'us-east-2',
                    'signService' => 'states',
                    'signVersions' => ['v4'],
                ];
            case 'fips-us-gov-east-1':
                return [
                    'endpoint' => 'https://states-fips.us-gov-east-1.amazonaws.com',
                    'signRegion' => 'us-gov-east-1',
                    'signService' => 'states',
                    'signVersions' => ['v4'],
                ];
            case 'fips-us-gov-west-1':
                return [
                    'endpoint' => 'https://states.us-gov-west-1.amazonaws.com',
                    'signRegion' => 'us-gov-west-1',
                    'signService' => 'states',
                    'signVersions' => ['v4'],
                ];
            case 'fips-us-west-1':
                return [
                    'endpoint' => 'https://states-fips.us-west-1.amazonaws.com',
                    'signRegion' => 'us-west-1',
                    'signService' => 'states',
                    'signVersions' => ['v4'],
                ];
            case 'fips-us-west-2':
                return [
                    'endpoint' => 'https://states-fips.us-west-2.amazonaws.com',
                    'signRegion' => 'us-west-2',
                    'signService' => 'states',
                    'signVersions' => ['v4'],
                ];
        }

        return [
            'endpoint' => "https://states.$region.amazonaws.com",
            'signRegion' => $region,
            'signService' => 'states',
            'signVersions' => ['v4'],
        ];
    }
}
