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
use AsyncAws\StepFunctions\Exception\KmsAccessDeniedException;
use AsyncAws\StepFunctions\Exception\KmsInvalidStateException;
use AsyncAws\StepFunctions\Exception\KmsThrottlingException;
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
     * Used by activity workers, Task states using the callback [^1] pattern, and optionally Task states using the job run
     * [^2] pattern to report that the task identified by the `taskToken` failed.
     *
     * For an execution with encryption enabled, Step Functions will encrypt the error and cause fields using the KMS key
     * for the execution role.
     *
     * A caller can mark a task as fail without using any KMS permissions in the execution role if the caller provides a
     * null value for both `error` and `cause` fields because no data needs to be encrypted.
     *
     * [^1]: https://docs.aws.amazon.com/step-functions/latest/dg/connect-to-resource.html#connect-wait-token
     * [^2]: https://docs.aws.amazon.com/step-functions/latest/dg/connect-to-resource.html#connect-sync
     *
     * @see https://docs.aws.amazon.com/step-functions/latest/apireference/API_SendTaskFailure.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-states-2016-11-23.html#sendtaskfailure
     *
     * @param array{
     *   taskToken: string,
     *   error?: string|null,
     *   cause?: string|null,
     *   '@region'?: string|null,
     * }|SendTaskFailureInput $input
     *
     * @throws InvalidTokenException
     * @throws KmsAccessDeniedException
     * @throws KmsInvalidStateException
     * @throws KmsThrottlingException
     * @throws TaskDoesNotExistException
     * @throws TaskTimedOutException
     */
    public function sendTaskFailure($input): SendTaskFailureOutput
    {
        $input = SendTaskFailureInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'SendTaskFailure', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'InvalidToken' => InvalidTokenException::class,
            'KmsAccessDeniedException' => KmsAccessDeniedException::class,
            'KmsInvalidStateException' => KmsInvalidStateException::class,
            'KmsThrottlingException' => KmsThrottlingException::class,
            'TaskDoesNotExist' => TaskDoesNotExistException::class,
            'TaskTimedOut' => TaskTimedOutException::class,
        ]]));

        return new SendTaskFailureOutput($response);
    }

    /**
     * Used by activity workers and Task states using the callback [^1] pattern, and optionally Task states using the job
     * run [^2] pattern to report to Step Functions that the task represented by the specified `taskToken` is still making
     * progress. This action resets the `Heartbeat` clock. The `Heartbeat` threshold is specified in the state machine's
     * Amazon States Language definition (`HeartbeatSeconds`). This action does not in itself create an event in the
     * execution history. However, if the task times out, the execution history contains an `ActivityTimedOut` entry for
     * activities, or a `TaskTimedOut` entry for tasks using the job run [^3] or callback [^4] pattern.
     *
     * > The `Timeout` of a task, defined in the state machine's Amazon States Language definition, is its maximum allowed
     * > duration, regardless of the number of SendTaskHeartbeat requests received. Use `HeartbeatSeconds` to configure the
     * > timeout interval for heartbeats.
     *
     * [^1]: https://docs.aws.amazon.com/step-functions/latest/dg/connect-to-resource.html#connect-wait-token
     * [^2]: https://docs.aws.amazon.com/step-functions/latest/dg/connect-to-resource.html#connect-sync
     * [^3]: https://docs.aws.amazon.com/step-functions/latest/dg/connect-to-resource.html#connect-sync
     * [^4]: https://docs.aws.amazon.com/step-functions/latest/dg/connect-to-resource.html#connect-wait-token
     *
     * @see https://docs.aws.amazon.com/step-functions/latest/apireference/API_SendTaskHeartbeat.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-states-2016-11-23.html#sendtaskheartbeat
     *
     * @param array{
     *   taskToken: string,
     *   '@region'?: string|null,
     * }|SendTaskHeartbeatInput $input
     *
     * @throws InvalidTokenException
     * @throws TaskDoesNotExistException
     * @throws TaskTimedOutException
     */
    public function sendTaskHeartbeat($input): SendTaskHeartbeatOutput
    {
        $input = SendTaskHeartbeatInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'SendTaskHeartbeat', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'InvalidToken' => InvalidTokenException::class,
            'TaskDoesNotExist' => TaskDoesNotExistException::class,
            'TaskTimedOut' => TaskTimedOutException::class,
        ]]));

        return new SendTaskHeartbeatOutput($response);
    }

    /**
     * Used by activity workers, Task states using the callback [^1] pattern, and optionally Task states using the job run
     * [^2] pattern to report that the task identified by the `taskToken` completed successfully.
     *
     * [^1]: https://docs.aws.amazon.com/step-functions/latest/dg/connect-to-resource.html#connect-wait-token
     * [^2]: https://docs.aws.amazon.com/step-functions/latest/dg/connect-to-resource.html#connect-sync
     *
     * @see https://docs.aws.amazon.com/step-functions/latest/apireference/API_SendTaskSuccess.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-states-2016-11-23.html#sendtasksuccess
     *
     * @param array{
     *   taskToken: string,
     *   output: string,
     *   '@region'?: string|null,
     * }|SendTaskSuccessInput $input
     *
     * @throws InvalidOutputException
     * @throws InvalidTokenException
     * @throws KmsAccessDeniedException
     * @throws KmsInvalidStateException
     * @throws KmsThrottlingException
     * @throws TaskDoesNotExistException
     * @throws TaskTimedOutException
     */
    public function sendTaskSuccess($input): SendTaskSuccessOutput
    {
        $input = SendTaskSuccessInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'SendTaskSuccess', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'InvalidOutput' => InvalidOutputException::class,
            'InvalidToken' => InvalidTokenException::class,
            'KmsAccessDeniedException' => KmsAccessDeniedException::class,
            'KmsInvalidStateException' => KmsInvalidStateException::class,
            'KmsThrottlingException' => KmsThrottlingException::class,
            'TaskDoesNotExist' => TaskDoesNotExistException::class,
            'TaskTimedOut' => TaskTimedOutException::class,
        ]]));

        return new SendTaskSuccessOutput($response);
    }

    /**
     * Starts a state machine execution.
     *
     * A qualified state machine ARN can either refer to a *Distributed Map state* defined within a state machine, a version
     * ARN, or an alias ARN.
     *
     * The following are some examples of qualified and unqualified state machine ARNs:
     *
     * - The following qualified state machine ARN refers to a *Distributed Map state* with a label `mapStateLabel` in a
     *   state machine named `myStateMachine`.
     *
     *   `arn:partition:states:region:account-id:stateMachine:myStateMachine/mapStateLabel`
     *
     *   > If you provide a qualified state machine ARN that refers to a *Distributed Map state*, the request fails with
     *   > `ValidationException`.
     *
     * - The following qualified state machine ARN refers to an alias named `PROD`.
     *
     *   `arn:<partition>:states:<region>:<account-id>:stateMachine:<myStateMachine:PROD>`
     *
     *   > If you provide a qualified state machine ARN that refers to a version ARN or an alias ARN, the request starts
     *   > execution for that version or alias.
     *
     * - The following unqualified state machine ARN refers to a state machine named `myStateMachine`.
     *
     *   `arn:<partition>:states:<region>:<account-id>:stateMachine:<myStateMachine>`
     *
     * If you start an execution with an unqualified state machine ARN, Step Functions uses the latest revision of the state
     * machine for the execution.
     *
     * To start executions of a state machine version [^1], call `StartExecution` and provide the version ARN or the ARN of
     * an alias [^2] that points to the version.
     *
     * > `StartExecution` is idempotent for `STANDARD` workflows. For a `STANDARD` workflow, if you call `StartExecution`
     * > with the same name and input as a running execution, the call succeeds and return the same response as the original
     * > request. If the execution is closed or if the input is different, it returns a `400 ExecutionAlreadyExists` error.
     * > You can reuse names after 90 days.
     * >
     * > `StartExecution` isn't idempotent for `EXPRESS` workflows.
     *
     * [^1]: https://docs.aws.amazon.com/step-functions/latest/dg/concepts-state-machine-version.html
     * [^2]: https://docs.aws.amazon.com/step-functions/latest/dg/concepts-state-machine-alias.html
     *
     * @see https://docs.aws.amazon.com/step-functions/latest/apireference/API_StartExecution.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-states-2016-11-23.html#startexecution
     *
     * @param array{
     *   stateMachineArn: string,
     *   name?: string|null,
     *   input?: string|null,
     *   traceHeader?: string|null,
     *   '@region'?: string|null,
     * }|StartExecutionInput $input
     *
     * @throws ExecutionAlreadyExistsException
     * @throws ExecutionLimitExceededException
     * @throws InvalidArnException
     * @throws InvalidExecutionInputException
     * @throws InvalidNameException
     * @throws KmsAccessDeniedException
     * @throws KmsInvalidStateException
     * @throws KmsThrottlingException
     * @throws StateMachineDeletingException
     * @throws StateMachineDoesNotExistException
     * @throws ValidationException
     */
    public function startExecution($input): StartExecutionOutput
    {
        $input = StartExecutionInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'StartExecution', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'ExecutionAlreadyExists' => ExecutionAlreadyExistsException::class,
            'ExecutionLimitExceeded' => ExecutionLimitExceededException::class,
            'InvalidArn' => InvalidArnException::class,
            'InvalidExecutionInput' => InvalidExecutionInputException::class,
            'InvalidName' => InvalidNameException::class,
            'KmsAccessDeniedException' => KmsAccessDeniedException::class,
            'KmsInvalidStateException' => KmsInvalidStateException::class,
            'KmsThrottlingException' => KmsThrottlingException::class,
            'StateMachineDeleting' => StateMachineDeletingException::class,
            'StateMachineDoesNotExist' => StateMachineDoesNotExistException::class,
            'ValidationException' => ValidationException::class,
        ]]));

        return new StartExecutionOutput($response);
    }

    /**
     * Stops an execution.
     *
     * This API action is not supported by `EXPRESS` state machines.
     *
     * For an execution with encryption enabled, Step Functions will encrypt the error and cause fields using the KMS key
     * for the execution role.
     *
     * A caller can stop an execution without using any KMS permissions in the execution role if the caller provides a null
     * value for both `error` and `cause` fields because no data needs to be encrypted.
     *
     * @see https://docs.aws.amazon.com/step-functions/latest/apireference/API_StopExecution.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-states-2016-11-23.html#stopexecution
     *
     * @param array{
     *   executionArn: string,
     *   error?: string|null,
     *   cause?: string|null,
     *   '@region'?: string|null,
     * }|StopExecutionInput $input
     *
     * @throws ExecutionDoesNotExistException
     * @throws InvalidArnException
     * @throws KmsAccessDeniedException
     * @throws KmsInvalidStateException
     * @throws KmsThrottlingException
     * @throws ValidationException
     */
    public function stopExecution($input): StopExecutionOutput
    {
        $input = StopExecutionInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'StopExecution', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'ExecutionDoesNotExist' => ExecutionDoesNotExistException::class,
            'InvalidArn' => InvalidArnException::class,
            'KmsAccessDeniedException' => KmsAccessDeniedException::class,
            'KmsInvalidStateException' => KmsInvalidStateException::class,
            'KmsThrottlingException' => KmsThrottlingException::class,
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
            case 'eusc-de-east-1':
                return [
                    'endpoint' => 'https://states.eusc-de-east-1.amazonaws.eu',
                    'signRegion' => 'eusc-de-east-1',
                    'signService' => 'states',
                    'signVersions' => ['v4'],
                ];
            case 'fips-ca-central-1':
                return [
                    'endpoint' => 'https://states-fips.ca-central-1.amazonaws.com',
                    'signRegion' => 'ca-central-1',
                    'signService' => 'states',
                    'signVersions' => ['v4'],
                ];
            case 'fips-ca-west-1':
                return [
                    'endpoint' => 'https://states-fips.ca-west-1.amazonaws.com',
                    'signRegion' => 'ca-west-1',
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
            case 'eu-isoe-west-1':
                return [
                    'endpoint' => 'https://states.eu-isoe-west-1.cloud.adc-e.uk',
                    'signRegion' => 'eu-isoe-west-1',
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
            case 'us-isob-west-1':
                return [
                    'endpoint' => "https://states.$region.sc2s.sgov.gov",
                    'signRegion' => $region,
                    'signService' => 'states',
                    'signVersions' => ['v4'],
                ];
            case 'us-isof-east-1':
            case 'us-isof-south-1':
                return [
                    'endpoint' => "https://states.$region.csp.hci.ic.gov",
                    'signRegion' => $region,
                    'signService' => 'states',
                    'signVersions' => ['v4'],
                ];
            case 'fips-us-iso-east-1':
                return [
                    'endpoint' => 'https://states-fips.us-iso-east-1.c2s.ic.gov',
                    'signRegion' => 'us-iso-east-1',
                    'signService' => 'states',
                    'signVersions' => ['v4'],
                ];
            case 'fips-us-iso-west-1':
                return [
                    'endpoint' => 'https://states-fips.us-iso-west-1.c2s.ic.gov',
                    'signRegion' => 'us-iso-west-1',
                    'signService' => 'states',
                    'signVersions' => ['v4'],
                ];
            case 'fips-us-isob-east-1':
                return [
                    'endpoint' => 'https://states-fips.us-isob-east-1.sc2s.sgov.gov',
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
