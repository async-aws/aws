<?php

namespace AsyncAws\Athena;

use AsyncAws\Athena\Exception\InternalServerException;
use AsyncAws\Athena\Exception\InvalidRequestException;
use AsyncAws\Athena\Exception\TooManyRequestsException;
use AsyncAws\Athena\Input\GetQueryExecutionInput;
use AsyncAws\Athena\Input\StartQueryExecutionInput;
use AsyncAws\Athena\Result\GetQueryExecutionOutput;
use AsyncAws\Athena\Result\StartQueryExecutionOutput;
use AsyncAws\Athena\ValueObject\QueryExecutionContext;
use AsyncAws\Athena\ValueObject\ResultConfiguration;
use AsyncAws\Core\AbstractApi;
use AsyncAws\Core\AwsError\AwsErrorFactoryInterface;
use AsyncAws\Core\AwsError\JsonRpcAwsErrorFactory;
use AsyncAws\Core\Configuration;
use AsyncAws\Core\RequestContext;

class AthenaClient extends AbstractApi
{
    /**
     * Returns information about a single execution of a query if you have access to the workgroup in which the query ran.
     * Each time a query executes, information about the query execution is saved with a unique ID.
     *
     * @see https://docs.aws.amazon.com/athena/latest/APIReference/Welcome.html/API_GetQueryExecution.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-athena-2017-05-18.html#getqueryexecution
     *
     * @param array{
     *   QueryExecutionId: string,
     *   @region?: string,
     * }|GetQueryExecutionInput $input
     *
     * @throws InternalServerException
     * @throws InvalidRequestException
     */
    public function getQueryExecution($input): GetQueryExecutionOutput
    {
        $input = GetQueryExecutionInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'GetQueryExecution', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'InternalServerException' => InternalServerException::class,
            'InvalidRequestException' => InvalidRequestException::class,
        ]]));

        return new GetQueryExecutionOutput($response);
    }

    /**
     * Runs the SQL query statements contained in the `Query`. Requires you to have access to the workgroup in which the
     * query ran. Running queries against an external catalog requires GetDataCatalog permission to the catalog. For code
     * samples using the AWS SDK for Java, see Examples and Code Samples in the *Amazon Athena User Guide*.
     *
     * @see http://docs.aws.amazon.com/athena/latest/ug/code-samples.html
     * @see https://docs.aws.amazon.com/athena/latest/APIReference/Welcome.html/API_StartQueryExecution.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-athena-2017-05-18.html#startqueryexecution
     *
     * @param array{
     *   QueryString: string,
     *   ClientRequestToken?: string,
     *   QueryExecutionContext?: QueryExecutionContext|array,
     *   ResultConfiguration?: ResultConfiguration|array,
     *   WorkGroup?: string,
     *   @region?: string,
     * }|StartQueryExecutionInput $input
     *
     * @throws InternalServerException
     * @throws InvalidRequestException
     * @throws TooManyRequestsException
     */
    public function startQueryExecution($input): StartQueryExecutionOutput
    {
        $input = StartQueryExecutionInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'StartQueryExecution', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'InternalServerException' => InternalServerException::class,
            'InvalidRequestException' => InvalidRequestException::class,
            'TooManyRequestsException' => TooManyRequestsException::class,
        ]]));

        return new StartQueryExecutionOutput($response);
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
                    'endpoint' => "https://athena.$region.amazonaws.com.cn",
                    'signRegion' => $region,
                    'signService' => 'athena',
                    'signVersions' => ['v4'],
                ];
            case 'us-gov-east-1':
            case 'us-gov-west-1':
                return [
                    'endpoint' => "https://athena.$region.amazonaws.com",
                    'signRegion' => $region,
                    'signService' => 'athena',
                    'signVersions' => ['v4'],
                ];
            case 'fips-us-east-1':
                return [
                    'endpoint' => 'https://athena-fips.us-east-1.amazonaws.com',
                    'signRegion' => 'us-east-1',
                    'signService' => 'athena',
                    'signVersions' => ['v4'],
                ];
            case 'fips-us-east-2':
                return [
                    'endpoint' => 'https://athena-fips.us-east-2.amazonaws.com',
                    'signRegion' => 'us-east-2',
                    'signService' => 'athena',
                    'signVersions' => ['v4'],
                ];
            case 'fips-us-gov-east-1':
                return [
                    'endpoint' => 'https://athena-fips.us-gov-east-1.amazonaws.com',
                    'signRegion' => 'us-gov-east-1',
                    'signService' => 'athena',
                    'signVersions' => ['v4'],
                ];
            case 'fips-us-gov-west-1':
                return [
                    'endpoint' => 'https://athena-fips.us-gov-west-1.amazonaws.com',
                    'signRegion' => 'us-gov-west-1',
                    'signService' => 'athena',
                    'signVersions' => ['v4'],
                ];
            case 'fips-us-west-1':
                return [
                    'endpoint' => 'https://athena-fips.us-west-1.amazonaws.com',
                    'signRegion' => 'us-west-1',
                    'signService' => 'athena',
                    'signVersions' => ['v4'],
                ];
            case 'fips-us-west-2':
                return [
                    'endpoint' => 'https://athena-fips.us-west-2.amazonaws.com',
                    'signRegion' => 'us-west-2',
                    'signService' => 'athena',
                    'signVersions' => ['v4'],
                ];
        }

        return [
            'endpoint' => "https://athena.$region.amazonaws.com",
            'signRegion' => $region,
            'signService' => 'athena',
            'signVersions' => ['v4'],
        ];
    }
}
