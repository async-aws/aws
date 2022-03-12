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
     * Creates a new secret. A *secret* is a set of credentials, such as a user name and password, that you store in an
     * encrypted form in Secrets Manager. The secret also includes the connection information to access a database or other
     * service, which Secrets Manager doesn't encrypt. A secret in Secrets Manager consists of both the protected secret
     * data and the important information needed to manage the secret.
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
     * @throws DecryptionFailureException
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
            'DecryptionFailure' => DecryptionFailureException::class,
        ]]));

        return new CreateSecretResponse($response);
    }

    /**
     * Deletes a secret and all of its versions. You can specify a recovery window during which you can restore the secret.
     * The minimum recovery window is 7 days. The default recovery window is 30 days. Secrets Manager attaches a
     * `DeletionDate` stamp to the secret that specifies the end of the recovery window. At the end of the recovery window,
     * Secrets Manager deletes the secret permanently.
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
     * Lists the secrets that are stored by Secrets Manager in the Amazon Web Services account, not including secrets that
     * are marked for deletion. To see secrets marked for deletion, use the Secrets Manager console.
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
     * Creates a new version with a new encrypted secret value and attaches it to the secret. The version can contain a new
     * `SecretString` value or a new `SecretBinary` value.
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
     * @throws DecryptionFailureException
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
            'DecryptionFailure' => DecryptionFailureException::class,
        ]]));

        return new PutSecretValueResponse($response);
    }

    /**
     * Modifies the details of a secret, including metadata and the secret value. To change the secret value, you can also
     * use PutSecretValue.
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
     * @throws DecryptionFailureException
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
            'DecryptionFailure' => DecryptionFailureException::class,
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
            case 'us-iso-east-1':
                return [
                    'endpoint' => 'https://secretsmanager.us-iso-east-1.c2s.ic.gov',
                    'signRegion' => 'us-iso-east-1',
                    'signService' => 'secretsmanager',
                    'signVersions' => ['v4'],
                ];
            case 'ca-central-1-fips':
                return [
                    'endpoint' => 'https://secretsmanager-fips.ca-central-1.amazonaws.com',
                    'signRegion' => 'ca-central-1',
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
