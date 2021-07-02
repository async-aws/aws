<?php

namespace AsyncAws\SecretsManager;

use AsyncAws\Core\AbstractApi;
use AsyncAws\Core\AwsError\AwsErrorFactoryInterface;
use AsyncAws\Core\AwsError\JsonRpcAwsErrorFactory;
use AsyncAws\Core\Configuration;
use AsyncAws\Core\RequestContext;
use AsyncAws\SecretsManager\Enum\SortOrderType;
use AsyncAws\SecretsManager\Exception\DecryptionFailureException;
use AsyncAws\SecretsManager\Exception\EncryptionFailureException;
use AsyncAws\SecretsManager\Exception\InternalServiceErrorException;
use AsyncAws\SecretsManager\Exception\InvalidNextTokenException;
use AsyncAws\SecretsManager\Exception\InvalidParameterException;
use AsyncAws\SecretsManager\Exception\InvalidRequestException;
use AsyncAws\SecretsManager\Exception\LimitExceededException;
use AsyncAws\SecretsManager\Exception\MalformedPolicyDocumentException;
use AsyncAws\SecretsManager\Exception\PreconditionNotMetException;
use AsyncAws\SecretsManager\Exception\ResourceExistsException;
use AsyncAws\SecretsManager\Exception\ResourceNotFoundException;
use AsyncAws\SecretsManager\Input\CreateSecretRequest;
use AsyncAws\SecretsManager\Input\DeleteSecretRequest;
use AsyncAws\SecretsManager\Input\GetSecretValueRequest;
use AsyncAws\SecretsManager\Input\ListSecretsRequest;
use AsyncAws\SecretsManager\Input\PutSecretValueRequest;
use AsyncAws\SecretsManager\Input\UpdateSecretRequest;
use AsyncAws\SecretsManager\Result\CreateSecretResponse;
use AsyncAws\SecretsManager\Result\DeleteSecretResponse;
use AsyncAws\SecretsManager\Result\GetSecretValueResponse;
use AsyncAws\SecretsManager\Result\ListSecretsResponse;
use AsyncAws\SecretsManager\Result\PutSecretValueResponse;
use AsyncAws\SecretsManager\Result\UpdateSecretResponse;
use AsyncAws\SecretsManager\ValueObject\Filter;
use AsyncAws\SecretsManager\ValueObject\ReplicaRegionType;
use AsyncAws\SecretsManager\ValueObject\Tag;

class SecretsManagerClient extends AbstractApi
{
    /**
     * Creates a new secret. A secret in Secrets Manager consists of both the protected secret data and the important
     * information needed to manage the secret.
     *
     * @see https://docs.aws.amazon.com/secretsmanager/latest/apireference/API_CreateSecret.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-secretsmanager-2017-10-17.html#createsecret
     *
     * @param array{
     *   Name: string,
     *   ClientRequestToken?: string,
     *   Description?: string,
     *   KmsKeyId?: string,
     *   SecretBinary?: string,
     *   SecretString?: string,
     *   Tags?: Tag[],
     *   AddReplicaRegions?: ReplicaRegionType[],
     *   ForceOverwriteReplicaSecret?: bool,
     *   @region?: string,
     * }|CreateSecretRequest $input
     *
     * @throws InvalidParameterException
     * @throws InvalidRequestException
     * @throws LimitExceededException
     * @throws EncryptionFailureException
     * @throws ResourceExistsException
     * @throws ResourceNotFoundException
     * @throws MalformedPolicyDocumentException
     * @throws InternalServiceErrorException
     * @throws PreconditionNotMetException
     */
    public function createSecret($input): CreateSecretResponse
    {
        $input = CreateSecretRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'CreateSecret', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'InvalidParameterException' => InvalidParameterException::class,
            'InvalidRequestException' => InvalidRequestException::class,
            'LimitExceededException' => LimitExceededException::class,
            'EncryptionFailure' => EncryptionFailureException::class,
            'ResourceExistsException' => ResourceExistsException::class,
            'ResourceNotFoundException' => ResourceNotFoundException::class,
            'MalformedPolicyDocumentException' => MalformedPolicyDocumentException::class,
            'InternalServiceError' => InternalServiceErrorException::class,
            'PreconditionNotMetException' => PreconditionNotMetException::class,
        ]]));

        return new CreateSecretResponse($response);
    }

    /**
     * Deletes an entire secret and all of the versions. You can optionally include a recovery window during which you can
     * restore the secret. If you don't specify a recovery window value, the operation defaults to 30 days. Secrets Manager
     * attaches a `DeletionDate` stamp to the secret that specifies the end of the recovery window. At the end of the
     * recovery window, Secrets Manager deletes the secret permanently.
     *
     * @see https://docs.aws.amazon.com/secretsmanager/latest/apireference/API_DeleteSecret.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-secretsmanager-2017-10-17.html#deletesecret
     *
     * @param array{
     *   SecretId: string,
     *   RecoveryWindowInDays?: string,
     *   ForceDeleteWithoutRecovery?: bool,
     *   @region?: string,
     * }|DeleteSecretRequest $input
     *
     * @throws ResourceNotFoundException
     * @throws InvalidParameterException
     * @throws InvalidRequestException
     * @throws InternalServiceErrorException
     */
    public function deleteSecret($input): DeleteSecretResponse
    {
        $input = DeleteSecretRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'DeleteSecret', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'ResourceNotFoundException' => ResourceNotFoundException::class,
            'InvalidParameterException' => InvalidParameterException::class,
            'InvalidRequestException' => InvalidRequestException::class,
            'InternalServiceError' => InternalServiceErrorException::class,
        ]]));

        return new DeleteSecretResponse($response);
    }

    /**
     * Retrieves the contents of the encrypted fields `SecretString` or `SecretBinary` from the specified version of a
     * secret, whichever contains content.
     *
     * @see https://docs.aws.amazon.com/secretsmanager/latest/apireference/API_GetSecretValue.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-secretsmanager-2017-10-17.html#getsecretvalue
     *
     * @param array{
     *   SecretId: string,
     *   VersionId?: string,
     *   VersionStage?: string,
     *   @region?: string,
     * }|GetSecretValueRequest $input
     *
     * @throws ResourceNotFoundException
     * @throws InvalidParameterException
     * @throws InvalidRequestException
     * @throws DecryptionFailureException
     * @throws InternalServiceErrorException
     */
    public function getSecretValue($input): GetSecretValueResponse
    {
        $input = GetSecretValueRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'GetSecretValue', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'ResourceNotFoundException' => ResourceNotFoundException::class,
            'InvalidParameterException' => InvalidParameterException::class,
            'InvalidRequestException' => InvalidRequestException::class,
            'DecryptionFailure' => DecryptionFailureException::class,
            'InternalServiceError' => InternalServiceErrorException::class,
        ]]));

        return new GetSecretValueResponse($response);
    }

    /**
     * Lists all of the secrets that are stored by Secrets Manager in the AWS account. To list the versions currently stored
     * for a specific secret, use ListSecretVersionIds. The encrypted fields `SecretString` and `SecretBinary` are not
     * included in the output. To get that information, call the GetSecretValue operation.
     *
     * @see https://docs.aws.amazon.com/secretsmanager/latest/apireference/API_ListSecrets.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-secretsmanager-2017-10-17.html#listsecrets
     *
     * @param array{
     *   MaxResults?: int,
     *   NextToken?: string,
     *   Filters?: Filter[],
     *   SortOrder?: SortOrderType::*,
     *   @region?: string,
     * }|ListSecretsRequest $input
     *
     * @throws InvalidParameterException
     * @throws InvalidNextTokenException
     * @throws InternalServiceErrorException
     */
    public function listSecrets($input = []): ListSecretsResponse
    {
        $input = ListSecretsRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'ListSecrets', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'InvalidParameterException' => InvalidParameterException::class,
            'InvalidNextTokenException' => InvalidNextTokenException::class,
            'InternalServiceError' => InternalServiceErrorException::class,
        ]]));

        return new ListSecretsResponse($response, $this, $input);
    }

    /**
     * Stores a new encrypted secret value in the specified secret. To do this, the operation creates a new version and
     * attaches it to the secret. The version can contain a new `SecretString` value or a new `SecretBinary` value. You can
     * also specify the staging labels that are initially attached to the new version.
     *
     * @see https://docs.aws.amazon.com/secretsmanager/latest/apireference/API_PutSecretValue.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-secretsmanager-2017-10-17.html#putsecretvalue
     *
     * @param array{
     *   SecretId: string,
     *   ClientRequestToken?: string,
     *   SecretBinary?: string,
     *   SecretString?: string,
     *   VersionStages?: string[],
     *   @region?: string,
     * }|PutSecretValueRequest $input
     *
     * @throws InvalidParameterException
     * @throws InvalidRequestException
     * @throws LimitExceededException
     * @throws EncryptionFailureException
     * @throws ResourceExistsException
     * @throws ResourceNotFoundException
     * @throws InternalServiceErrorException
     */
    public function putSecretValue($input): PutSecretValueResponse
    {
        $input = PutSecretValueRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'PutSecretValue', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'InvalidParameterException' => InvalidParameterException::class,
            'InvalidRequestException' => InvalidRequestException::class,
            'LimitExceededException' => LimitExceededException::class,
            'EncryptionFailure' => EncryptionFailureException::class,
            'ResourceExistsException' => ResourceExistsException::class,
            'ResourceNotFoundException' => ResourceNotFoundException::class,
            'InternalServiceError' => InternalServiceErrorException::class,
        ]]));

        return new PutSecretValueResponse($response);
    }

    /**
     * Modifies many of the details of the specified secret. If you include a `ClientRequestToken` and
     * *either*`SecretString` or `SecretBinary` then it also creates a new version attached to the secret.
     *
     * @see https://docs.aws.amazon.com/secretsmanager/latest/apireference/API_UpdateSecret.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-secretsmanager-2017-10-17.html#updatesecret
     *
     * @param array{
     *   SecretId: string,
     *   ClientRequestToken?: string,
     *   Description?: string,
     *   KmsKeyId?: string,
     *   SecretBinary?: string,
     *   SecretString?: string,
     *   @region?: string,
     * }|UpdateSecretRequest $input
     *
     * @throws InvalidParameterException
     * @throws InvalidRequestException
     * @throws LimitExceededException
     * @throws EncryptionFailureException
     * @throws ResourceExistsException
     * @throws ResourceNotFoundException
     * @throws MalformedPolicyDocumentException
     * @throws InternalServiceErrorException
     * @throws PreconditionNotMetException
     */
    public function updateSecret($input): UpdateSecretResponse
    {
        $input = UpdateSecretRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'UpdateSecret', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'InvalidParameterException' => InvalidParameterException::class,
            'InvalidRequestException' => InvalidRequestException::class,
            'LimitExceededException' => LimitExceededException::class,
            'EncryptionFailure' => EncryptionFailureException::class,
            'ResourceExistsException' => ResourceExistsException::class,
            'ResourceNotFoundException' => ResourceNotFoundException::class,
            'MalformedPolicyDocumentException' => MalformedPolicyDocumentException::class,
            'InternalServiceError' => InternalServiceErrorException::class,
            'PreconditionNotMetException' => PreconditionNotMetException::class,
        ]]));

        return new UpdateSecretResponse($response);
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
                    'endpoint' => "https://secretsmanager.$region.amazonaws.com.cn",
                    'signRegion' => $region,
                    'signService' => 'secretsmanager',
                    'signVersions' => ['v4'],
                ];
            case 'us-gov-east-1':
            case 'us-gov-west-1':
                return [
                    'endpoint' => "https://secretsmanager.$region.amazonaws.com",
                    'signRegion' => $region,
                    'signService' => 'secretsmanager',
                    'signVersions' => ['v4'],
                ];
            case 'us-iso-east-1':
                return [
                    'endpoint' => "https://secretsmanager.$region.c2s.ic.gov",
                    'signRegion' => $region,
                    'signService' => 'secretsmanager',
                    'signVersions' => ['v4'],
                ];
            case 'us-east-1-fips':
                return [
                    'endpoint' => 'https://secretsmanager-fips.us-east-1.amazonaws.com',
                    'signRegion' => 'us-east-1',
                    'signService' => 'secretsmanager',
                    'signVersions' => ['v4'],
                ];
            case 'us-east-2-fips':
                return [
                    'endpoint' => 'https://secretsmanager-fips.us-east-2.amazonaws.com',
                    'signRegion' => 'us-east-2',
                    'signService' => 'secretsmanager',
                    'signVersions' => ['v4'],
                ];
            case 'us-gov-east-1-fips':
                return [
                    'endpoint' => 'https://secretsmanager-fips.us-gov-east-1.amazonaws.com',
                    'signRegion' => 'us-gov-east-1',
                    'signService' => 'secretsmanager',
                    'signVersions' => ['v4'],
                ];
            case 'us-gov-west-1-fips':
                return [
                    'endpoint' => 'https://secretsmanager-fips.us-gov-west-1.amazonaws.com',
                    'signRegion' => 'us-gov-west-1',
                    'signService' => 'secretsmanager',
                    'signVersions' => ['v4'],
                ];
            case 'us-west-1-fips':
                return [
                    'endpoint' => 'https://secretsmanager-fips.us-west-1.amazonaws.com',
                    'signRegion' => 'us-west-1',
                    'signService' => 'secretsmanager',
                    'signVersions' => ['v4'],
                ];
            case 'us-west-2-fips':
                return [
                    'endpoint' => 'https://secretsmanager-fips.us-west-2.amazonaws.com',
                    'signRegion' => 'us-west-2',
                    'signService' => 'secretsmanager',
                    'signVersions' => ['v4'],
                ];
        }

        return [
            'endpoint' => "https://secretsmanager.$region.amazonaws.com",
            'signRegion' => $region,
            'signService' => 'secretsmanager',
            'signVersions' => ['v4'],
        ];
    }
}
