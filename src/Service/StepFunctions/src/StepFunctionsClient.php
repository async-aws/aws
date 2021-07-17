<?php

namespace AsyncAws\StepFunctions;

use AsyncAws\Core\AbstractApi;
use AsyncAws\Core\AwsError\AwsErrorFactoryInterface;
use AsyncAws\Core\AwsError\JsonRpcAwsErrorFactory;
use AsyncAws\Core\Configuration;
use AsyncAws\Core\RequestContext;
use AsyncAws\StepFunctions\Exception\ExecutionAlreadyExistsException;
use AsyncAws\StepFunctions\Exception\ExecutionLimitExceededException;
use AsyncAws\StepFunctions\Exception\InvalidArnException;
use AsyncAws\StepFunctions\Exception\InvalidExecutionInputException;
use AsyncAws\StepFunctions\Exception\InvalidNameException;
use AsyncAws\StepFunctions\Exception\StateMachineDeletingException;
use AsyncAws\StepFunctions\Exception\StateMachineDoesNotExistException;
use AsyncAws\StepFunctions\Input\StartExecutionInput;
use AsyncAws\StepFunctions\Result\StartExecutionOutput;

class StepFunctionsClient extends AbstractApi
{
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
            case 'us-gov-east-1':
            case 'us-gov-west-1':
                return [
                    'endpoint' => "https://states.$region.amazonaws.com",
                    'signRegion' => $region,
                    'signService' => 'states',
                    'signVersions' => ['v4'],
                ];
            case 'us-iso-east-1':
                return [
                    'endpoint' => "https://states.$region.c2s.ic.gov",
                    'signRegion' => $region,
                    'signService' => 'states',
                    'signVersions' => ['v4'],
                ];
            case 'us-isob-east-1':
                return [
                    'endpoint' => "https://states.$region.sc2s.sgov.gov",
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
