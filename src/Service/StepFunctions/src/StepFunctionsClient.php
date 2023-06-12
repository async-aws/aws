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
use AsyncAws\StepFunctions\Exception\ValidationException;
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
     * Used by activity workers and task states using the callback [^1] pattern to report that the task identified by the
     * `taskToken` failed.
     *
     * [^1]: https://docs.aws.amazon.com/step-functions/latest/dg/connect-to-resource.html#connect-wait-token
     *
     * @see https://docs.aws.amazon.com/step-functions/latest/apireference/API_SendTaskFailure.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-states-2016-11-23.html#sendtaskfailure
     *
     * @param array{
     *   taskToken: string,
     *   error?: string,
     *   cause?: string,
     *
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
     * Used by activity workers and task states using the callback [^1] pattern to report to Step Functions that the task
     * represented by the specified `taskToken` is still making progress. This action resets the `Heartbeat` clock. The
     * `Heartbeat` threshold is specified in the state machine's Amazon States Language definition (`HeartbeatSeconds`).
     * This action does not in itself create an event in the execution history. However, if the task times out, the
     * execution history contains an `ActivityTimedOut` entry for activities, or a `TaskTimedOut` entry for for tasks using
     * the job run [^2] or callback [^3] pattern.
     *
     * > The `Timeout` of a task, defined in the state machine's Amazon States Language definition, is its maximum allowed
     * > duration, regardless of the number of SendTaskHeartbeat requests received. Use `HeartbeatSeconds` to configure the
     * > timeout interval for heartbeats.
     *
     * [^1]: https://docs.aws.amazon.com/step-functions/latest/dg/connect-to-resource.html#connect-wait-token
     * [^2]: https://docs.aws.amazon.com/step-functions/latest/dg/connect-to-resource.html#connect-sync
     * [^3]: https://docs.aws.amazon.com/step-functions/latest/dg/connect-to-resource.html#connect-wait-token
     *
     * @see https://docs.aws.amazon.com/step-functions/latest/apireference/API_SendTaskHeartbeat.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-states-2016-11-23.html#sendtaskheartbeat
     *
     * @param array{
     *   taskToken: string,
     *
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
     * Used by activity workers and task states using the callback [^1] pattern to report that the task identified by the
     * `taskToken` completed successfully.
     *
     * [^1]: https://docs.aws.amazon.com/step-functions/latest/dg/connect-to-resource.html#connect-wait-token
     *
     * @see https://docs.aws.amazon.com/step-functions/latest/apireference/API_SendTaskSuccess.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-states-2016-11-23.html#sendtasksuccess
     *
     * @param array{
     *   taskToken: string,
     *   output: string,
     *
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
     * Starts a state machine execution. If the given state machine Amazon Resource Name (ARN) is a qualified state machine
     * ARN, it will fail with ValidationException.
     *
     * A qualified state machine ARN refers to a *Distributed Map state* defined within a state machine. For example, the
     * qualified state machine ARN `arn:partition:states:region:account-id:stateMachine:stateMachineName/mapStateLabel`
     * refers to a *Distributed Map state* with a label `mapStateLabel` in the state machine named `stateMachineName`.
     *
     * > `StartExecution` is idempotent for `STANDARD` workflows. For a `STANDARD` workflow, if `StartExecution` is called
     * > with the same name and input as a running execution, the call will succeed and return the same response as the
     * > original request. If the execution is closed or if the input is different, it will return a `400
     * > ExecutionAlreadyExists` error. Names can be reused after 90 days.
     * >
     * > `StartExecution` is not idempotent for `EXPRESS` workflows.
     *
     * @see https://docs.aws.amazon.com/step-functions/latest/apireference/API_StartExecution.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-states-2016-11-23.html#startexecution
     *
     * @param array{
     *   stateMachineArn: string,
     *   name?: string,
     *   input?: string,
     *   traceHeader?: string,
     *
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
     * @throws ValidationException
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
            'ValidationException' => ValidationException::class,
        ]]));

        return new StartExecutionOutput($response);
    }

    /**
     * Stops an execution.
     *
     * This API action is not supported by `EXPRESS` state machines.
     *
     * @see https://docs.aws.amazon.com/step-functions/latest/apireference/API_StopExecution.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-states-2016-11-23.html#stopexecution
     *
     * @param array{
     *   executionArn: string,
     *   error?: string,
     *   cause?: string,
     *
     *   @region?: string,
     * }|StopExecutionInput $input
     *
     * @throws ExecutionDoesNotExistException
     * @throws InvalidArnException
     * @throws ValidationException
     */
    public function stopExecution($input): StopExecutionOutput
    {
        $input = StopExecutionInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'StopExecution', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'ExecutionDoesNotExist' => ExecutionDoesNotExistException::class,
            'InvalidArn' => InvalidArnException::class,
            'ValidationException' => ValidationException::class,
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
        }

        return [
            'endpoint' => "https://states.$region.amazonaws.com",
            'signRegion' => $region,
            'signService' => 'states',
            'signVersions' => ['v4'],
        ];
    }
}
