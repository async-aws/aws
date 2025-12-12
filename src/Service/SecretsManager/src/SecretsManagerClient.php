<?php

namespace AsyncAws\SecretsManager;

use AsyncAws\Core\AbstractApi;
use AsyncAws\Core\AwsError\AwsErrorFactoryInterface;
use AsyncAws\Core\AwsError\JsonRpcAwsErrorFactory;
use AsyncAws\Core\Configuration;
use AsyncAws\Core\RequestContext;
use AsyncAws\SecretsManager\Enum\SortByType;
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
     * Creates a new secret. A *secret* can be a password, a set of credentials such as a user name and password, an OAuth
     * token, or other secret information that you store in an encrypted form in Secrets Manager. The secret also includes
     * the connection information to access a database or other service, which Secrets Manager doesn't encrypt. A secret in
     * Secrets Manager consists of both the protected secret data and the important information needed to manage the secret.
     *
     * For secrets that use *managed rotation*, you need to create the secret through the managing service. For more
     * information, see Secrets Manager secrets managed by other Amazon Web Services services [^1].
     *
     * For information about creating a secret in the console, see Create a secret [^2].
     *
     * To create a secret, you can provide the secret value to be encrypted in either the `SecretString` parameter or the
     * `SecretBinary` parameter, but not both. If you include `SecretString` or `SecretBinary` then Secrets Manager creates
     * an initial secret version and automatically attaches the staging label `AWSCURRENT` to it.
     *
     * For database credentials you want to rotate, for Secrets Manager to be able to rotate the secret, you must make sure
     * the JSON you store in the `SecretString` matches the JSON structure of a database secret [^3].
     *
     * If you don't specify an KMS encryption key, Secrets Manager uses the Amazon Web Services managed key
     * `aws/secretsmanager`. If this key doesn't already exist in your account, then Secrets Manager creates it for you
     * automatically. All users and roles in the Amazon Web Services account automatically have access to use
     * `aws/secretsmanager`. Creating `aws/secretsmanager` can result in a one-time significant delay in returning the
     * result.
     *
     * If the secret is in a different Amazon Web Services account from the credentials calling the API, then you can't use
     * `aws/secretsmanager` to encrypt the secret, and you must create and use a customer managed KMS key.
     *
     * Secrets Manager generates a CloudTrail log entry when you call this action. Do not include sensitive information in
     * request parameters except `SecretBinary` or `SecretString` because it might be logged. For more information, see
     * Logging Secrets Manager events with CloudTrail [^4].
     *
     * **Required permissions: **`secretsmanager:CreateSecret`. If you include tags in the secret, you also need
     * `secretsmanager:TagResource`. To add replica Regions, you must also have `secretsmanager:ReplicateSecretToRegions`.
     * For more information, see IAM policy actions for Secrets Manager [^5] and Authentication and access control in
     * Secrets Manager [^6].
     *
     * To encrypt the secret with a KMS key other than `aws/secretsmanager`, you need `kms:GenerateDataKey` and
     * `kms:Decrypt` permission to the key.
     *
     * ! When you enter commands in a command shell, there is a risk of the command history being accessed or utilities
     * ! having access to your command parameters. This is a concern if the command includes the value of a secret. Learn
     * ! how to Mitigate the risks of using command-line tools to store Secrets Manager secrets [^7].
     *
     * [^1]: https://docs.aws.amazon.com/secretsmanager/latest/userguide/service-linked-secrets.html
     * [^2]: https://docs.aws.amazon.com/secretsmanager/latest/userguide/manage_create-basic-secret.html
     * [^3]: https://docs.aws.amazon.com/secretsmanager/latest/userguide/reference_secret_json_structure.html
     * [^4]: https://docs.aws.amazon.com/secretsmanager/latest/userguide/retrieve-ct-entries.html
     * [^5]: https://docs.aws.amazon.com/secretsmanager/latest/userguide/reference_iam-permissions.html#reference_iam-permissions_actions
     * [^6]: https://docs.aws.amazon.com/secretsmanager/latest/userguide/auth-and-access.html
     * [^7]: https://docs.aws.amazon.com/secretsmanager/latest/userguide/security_cli-exposure-risks.html
     *
     * @see https://docs.aws.amazon.com/secretsmanager/latest/apireference/API_CreateSecret.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-secretsmanager-2017-10-17.html#createsecret
     *
     * @param array{
     *   Name: string,
     *   ClientRequestToken?: string|null,
     *   Description?: string|null,
     *   KmsKeyId?: string|null,
     *   SecretBinary?: string|null,
     *   SecretString?: string|null,
     *   Tags?: array<Tag|array>|null,
     *   AddReplicaRegions?: array<ReplicaRegionType|array>|null,
     *   ForceOverwriteReplicaSecret?: bool|null,
     *   Type?: string|null,
     *   '@region'?: string|null,
     * }|CreateSecretRequest $input
     *
     * @throws DecryptionFailureException
     * @throws EncryptionFailureException
     * @throws InternalServiceErrorException
     * @throws InvalidParameterException
     * @throws InvalidRequestException
     * @throws LimitExceededException
     * @throws MalformedPolicyDocumentException
     * @throws PreconditionNotMetException
     * @throws ResourceExistsException
     * @throws ResourceNotFoundException
     */
    public function createSecret($input): CreateSecretResponse
    {
        $input = CreateSecretRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'CreateSecret', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'DecryptionFailure' => DecryptionFailureException::class,
            'EncryptionFailure' => EncryptionFailureException::class,
            'InternalServiceError' => InternalServiceErrorException::class,
            'InvalidParameterException' => InvalidParameterException::class,
            'InvalidRequestException' => InvalidRequestException::class,
            'LimitExceededException' => LimitExceededException::class,
            'MalformedPolicyDocumentException' => MalformedPolicyDocumentException::class,
            'PreconditionNotMetException' => PreconditionNotMetException::class,
            'ResourceExistsException' => ResourceExistsException::class,
            'ResourceNotFoundException' => ResourceNotFoundException::class,
        ]]));

        return new CreateSecretResponse($response);
    }

    /**
     * Deletes a secret and all of its versions. You can specify a recovery window during which you can restore the secret.
     * The minimum recovery window is 7 days. The default recovery window is 30 days. Secrets Manager attaches a
     * `DeletionDate` stamp to the secret that specifies the end of the recovery window. At the end of the recovery window,
     * Secrets Manager deletes the secret permanently.
     *
     * You can't delete a primary secret that is replicated to other Regions. You must first delete the replicas using
     * RemoveRegionsFromReplication, and then delete the primary secret. When you delete a replica, it is deleted
     * immediately.
     *
     * You can't directly delete a version of a secret. Instead, you remove all staging labels from the version using
     * UpdateSecretVersionStage. This marks the version as deprecated, and then Secrets Manager can automatically delete the
     * version in the background.
     *
     * To determine whether an application still uses a secret, you can create an Amazon CloudWatch alarm to alert you to
     * any attempts to access a secret during the recovery window. For more information, see Monitor secrets scheduled for
     * deletion [^1].
     *
     * Secrets Manager performs the permanent secret deletion at the end of the waiting period as a background task with low
     * priority. There is no guarantee of a specific time after the recovery window for the permanent delete to occur.
     *
     * At any time before recovery window ends, you can use RestoreSecret to remove the `DeletionDate` and cancel the
     * deletion of the secret.
     *
     * When a secret is scheduled for deletion, you cannot retrieve the secret value. You must first cancel the deletion
     * with RestoreSecret and then you can retrieve the secret.
     *
     * Secrets Manager generates a CloudTrail log entry when you call this action. Do not include sensitive information in
     * request parameters because it might be logged. For more information, see Logging Secrets Manager events with
     * CloudTrail [^2].
     *
     * **Required permissions: **`secretsmanager:DeleteSecret`. For more information, see IAM policy actions for Secrets
     * Manager [^3] and Authentication and access control in Secrets Manager [^4].
     *
     * [^1]: https://docs.aws.amazon.com/secretsmanager/latest/userguide/monitoring_cloudwatch_deleted-secrets.html
     * [^2]: https://docs.aws.amazon.com/secretsmanager/latest/userguide/retrieve-ct-entries.html
     * [^3]: https://docs.aws.amazon.com/secretsmanager/latest/userguide/reference_iam-permissions.html#reference_iam-permissions_actions
     * [^4]: https://docs.aws.amazon.com/secretsmanager/latest/userguide/auth-and-access.html
     *
     * @see https://docs.aws.amazon.com/secretsmanager/latest/apireference/API_DeleteSecret.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-secretsmanager-2017-10-17.html#deletesecret
     *
     * @param array{
     *   SecretId: string,
     *   RecoveryWindowInDays?: int|null,
     *   ForceDeleteWithoutRecovery?: bool|null,
     *   '@region'?: string|null,
     * }|DeleteSecretRequest $input
     *
     * @throws InternalServiceErrorException
     * @throws InvalidParameterException
     * @throws InvalidRequestException
     * @throws ResourceNotFoundException
     */
    public function deleteSecret($input): DeleteSecretResponse
    {
        $input = DeleteSecretRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'DeleteSecret', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'InternalServiceError' => InternalServiceErrorException::class,
            'InvalidParameterException' => InvalidParameterException::class,
            'InvalidRequestException' => InvalidRequestException::class,
            'ResourceNotFoundException' => ResourceNotFoundException::class,
        ]]));

        return new DeleteSecretResponse($response);
    }

    /**
     * Retrieves the contents of the encrypted fields `SecretString` or `SecretBinary` from the specified version of a
     * secret, whichever contains content.
     *
     * To retrieve the values for a group of secrets, call BatchGetSecretValue.
     *
     * We recommend that you cache your secret values by using client-side caching. Caching secrets improves speed and
     * reduces your costs. For more information, see Cache secrets for your applications [^1].
     *
     * To retrieve the previous version of a secret, use `VersionStage` and specify AWSPREVIOUS. To revert to the previous
     * version of a secret, call UpdateSecretVersionStage [^2].
     *
     * Secrets Manager generates a CloudTrail log entry when you call this action. Do not include sensitive information in
     * request parameters because it might be logged. For more information, see Logging Secrets Manager events with
     * CloudTrail [^3].
     *
     * **Required permissions: **`secretsmanager:GetSecretValue`. If the secret is encrypted using a customer-managed key
     * instead of the Amazon Web Services managed key `aws/secretsmanager`, then you also need `kms:Decrypt` permissions for
     * that key. For more information, see IAM policy actions for Secrets Manager [^4] and Authentication and access control
     * in Secrets Manager [^5].
     *
     * [^1]: https://docs.aws.amazon.com/secretsmanager/latest/userguide/retrieving-secrets.html
     * [^2]: https://docs.aws.amazon.com/cli/latest/reference/secretsmanager/update-secret-version-stage.html
     * [^3]: https://docs.aws.amazon.com/secretsmanager/latest/userguide/retrieve-ct-entries.html
     * [^4]: https://docs.aws.amazon.com/secretsmanager/latest/userguide/reference_iam-permissions.html#reference_iam-permissions_actions
     * [^5]: https://docs.aws.amazon.com/secretsmanager/latest/userguide/auth-and-access.html
     *
     * @see https://docs.aws.amazon.com/secretsmanager/latest/apireference/API_GetSecretValue.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-secretsmanager-2017-10-17.html#getsecretvalue
     *
     * @param array{
     *   SecretId: string,
     *   VersionId?: string|null,
     *   VersionStage?: string|null,
     *   '@region'?: string|null,
     * }|GetSecretValueRequest $input
     *
     * @throws DecryptionFailureException
     * @throws InternalServiceErrorException
     * @throws InvalidParameterException
     * @throws InvalidRequestException
     * @throws ResourceNotFoundException
     */
    public function getSecretValue($input): GetSecretValueResponse
    {
        $input = GetSecretValueRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'GetSecretValue', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'DecryptionFailure' => DecryptionFailureException::class,
            'InternalServiceError' => InternalServiceErrorException::class,
            'InvalidParameterException' => InvalidParameterException::class,
            'InvalidRequestException' => InvalidRequestException::class,
            'ResourceNotFoundException' => ResourceNotFoundException::class,
        ]]));

        return new GetSecretValueResponse($response);
    }

    /**
     * Lists the secrets that are stored by Secrets Manager in the Amazon Web Services account, not including secrets that
     * are marked for deletion. To see secrets marked for deletion, use the Secrets Manager console.
     *
     * All Secrets Manager operations are eventually consistent. ListSecrets might not reflect changes from the last five
     * minutes. You can get more recent information for a specific secret by calling DescribeSecret.
     *
     * To list the versions of a secret, use ListSecretVersionIds.
     *
     * To retrieve the values for the secrets, call BatchGetSecretValue or GetSecretValue.
     *
     * For information about finding secrets in the console, see Find secrets in Secrets Manager [^1].
     *
     * Secrets Manager generates a CloudTrail log entry when you call this action. Do not include sensitive information in
     * request parameters because it might be logged. For more information, see Logging Secrets Manager events with
     * CloudTrail [^2].
     *
     * **Required permissions: **`secretsmanager:ListSecrets`. For more information, see IAM policy actions for Secrets
     * Manager [^3] and Authentication and access control in Secrets Manager [^4].
     *
     * [^1]: https://docs.aws.amazon.com/secretsmanager/latest/userguide/manage_search-secret.html
     * [^2]: https://docs.aws.amazon.com/secretsmanager/latest/userguide/retrieve-ct-entries.html
     * [^3]: https://docs.aws.amazon.com/secretsmanager/latest/userguide/reference_iam-permissions.html#reference_iam-permissions_actions
     * [^4]: https://docs.aws.amazon.com/secretsmanager/latest/userguide/auth-and-access.html
     *
     * @see https://docs.aws.amazon.com/secretsmanager/latest/apireference/API_ListSecrets.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-secretsmanager-2017-10-17.html#listsecrets
     *
     * @param array{
     *   IncludePlannedDeletion?: bool|null,
     *   MaxResults?: int|null,
     *   NextToken?: string|null,
     *   Filters?: array<Filter|array>|null,
     *   SortOrder?: SortOrderType::*|null,
     *   SortBy?: SortByType::*|null,
     *   '@region'?: string|null,
     * }|ListSecretsRequest $input
     *
     * @throws InternalServiceErrorException
     * @throws InvalidNextTokenException
     * @throws InvalidParameterException
     * @throws InvalidRequestException
     */
    public function listSecrets($input = []): ListSecretsResponse
    {
        $input = ListSecretsRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'ListSecrets', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'InternalServiceError' => InternalServiceErrorException::class,
            'InvalidNextTokenException' => InvalidNextTokenException::class,
            'InvalidParameterException' => InvalidParameterException::class,
            'InvalidRequestException' => InvalidRequestException::class,
        ]]));

        return new ListSecretsResponse($response, $this, $input);
    }

    /**
     * Creates a new version of your secret by creating a new encrypted value and attaching it to the secret. version can
     * contain a new `SecretString` value or a new `SecretBinary` value.
     *
     * Do not call `PutSecretValue` at a sustained rate of more than once every 10 minutes. When you update the secret
     * value, Secrets Manager creates a new version of the secret. Secrets Manager keeps 100 of the most recent versions,
     * but it keeps *all* secret versions created in the last 24 hours. If you call `PutSecretValue` more than once every 10
     * minutes, you will create more versions than Secrets Manager removes, and you will reach the quota for secret
     * versions.
     *
     * You can specify the staging labels to attach to the new version in `VersionStages`. If you don't include
     * `VersionStages`, then Secrets Manager automatically moves the staging label `AWSCURRENT` to this version. If this
     * operation creates the first version for the secret, then Secrets Manager automatically attaches the staging label
     * `AWSCURRENT` to it. If this operation moves the staging label `AWSCURRENT` from another version to this version, then
     * Secrets Manager also automatically moves the staging label `AWSPREVIOUS` to the version that `AWSCURRENT` was removed
     * from.
     *
     * This operation is idempotent. If you call this operation with a `ClientRequestToken` that matches an existing
     * version's VersionId, and you specify the same secret data, the operation succeeds but does nothing. However, if the
     * secret data is different, then the operation fails because you can't modify an existing version; you can only create
     * new ones.
     *
     * Secrets Manager generates a CloudTrail log entry when you call this action. Do not include sensitive information in
     * request parameters except `SecretBinary`, `SecretString`, or `RotationToken` because it might be logged. For more
     * information, see Logging Secrets Manager events with CloudTrail [^1].
     *
     * **Required permissions: **`secretsmanager:PutSecretValue`. For more information, see IAM policy actions for Secrets
     * Manager [^2] and Authentication and access control in Secrets Manager [^3].
     *
     * ! When you enter commands in a command shell, there is a risk of the command history being accessed or utilities
     * ! having access to your command parameters. This is a concern if the command includes the value of a secret. Learn
     * ! how to Mitigate the risks of using command-line tools to store Secrets Manager secrets [^4].
     *
     * [^1]: https://docs.aws.amazon.com/secretsmanager/latest/userguide/retrieve-ct-entries.html
     * [^2]: https://docs.aws.amazon.com/secretsmanager/latest/userguide/reference_iam-permissions.html#reference_iam-permissions_actions
     * [^3]: https://docs.aws.amazon.com/secretsmanager/latest/userguide/auth-and-access.html
     * [^4]: https://docs.aws.amazon.com/secretsmanager/latest/userguide/security_cli-exposure-risks.html
     *
     * @see https://docs.aws.amazon.com/secretsmanager/latest/apireference/API_PutSecretValue.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-secretsmanager-2017-10-17.html#putsecretvalue
     *
     * @param array{
     *   SecretId: string,
     *   ClientRequestToken?: string|null,
     *   SecretBinary?: string|null,
     *   SecretString?: string|null,
     *   VersionStages?: string[]|null,
     *   RotationToken?: string|null,
     *   '@region'?: string|null,
     * }|PutSecretValueRequest $input
     *
     * @throws DecryptionFailureException
     * @throws EncryptionFailureException
     * @throws InternalServiceErrorException
     * @throws InvalidParameterException
     * @throws InvalidRequestException
     * @throws LimitExceededException
     * @throws ResourceExistsException
     * @throws ResourceNotFoundException
     */
    public function putSecretValue($input): PutSecretValueResponse
    {
        $input = PutSecretValueRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'PutSecretValue', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'DecryptionFailure' => DecryptionFailureException::class,
            'EncryptionFailure' => EncryptionFailureException::class,
            'InternalServiceError' => InternalServiceErrorException::class,
            'InvalidParameterException' => InvalidParameterException::class,
            'InvalidRequestException' => InvalidRequestException::class,
            'LimitExceededException' => LimitExceededException::class,
            'ResourceExistsException' => ResourceExistsException::class,
            'ResourceNotFoundException' => ResourceNotFoundException::class,
        ]]));

        return new PutSecretValueResponse($response);
    }

    /**
     * Modifies the details of a secret, including metadata and the secret value. To change the secret value, you can also
     * use PutSecretValue.
     *
     * To change the rotation configuration of a secret, use RotateSecret instead.
     *
     * To change a secret so that it is managed by another service, you need to recreate the secret in that service. See
     * Secrets Manager secrets managed by other Amazon Web Services services [^1].
     *
     * We recommend you avoid calling `UpdateSecret` at a sustained rate of more than once every 10 minutes. When you call
     * `UpdateSecret` to update the secret value, Secrets Manager creates a new version of the secret. Secrets Manager
     * removes outdated versions when there are more than 100, but it does not remove versions created less than 24 hours
     * ago. If you update the secret value more than once every 10 minutes, you create more versions than Secrets Manager
     * removes, and you will reach the quota for secret versions.
     *
     * If you include `SecretString` or `SecretBinary` to create a new secret version, Secrets Manager automatically moves
     * the staging label `AWSCURRENT` to the new version. Then it attaches the label `AWSPREVIOUS` to the version that
     * `AWSCURRENT` was removed from.
     *
     * If you call this operation with a `ClientRequestToken` that matches an existing version's `VersionId`, the operation
     * results in an error. You can't modify an existing version, you can only create a new version. To remove a version,
     * remove all staging labels from it. See UpdateSecretVersionStage.
     *
     * Secrets Manager generates a CloudTrail log entry when you call this action. Do not include sensitive information in
     * request parameters except `SecretBinary` or `SecretString` because it might be logged. For more information, see
     * Logging Secrets Manager events with CloudTrail [^2].
     *
     * **Required permissions: **`secretsmanager:UpdateSecret`. For more information, see IAM policy actions for Secrets
     * Manager [^3] and Authentication and access control in Secrets Manager [^4]. If you use a customer managed key, you
     * must also have `kms:GenerateDataKey`, `kms:Encrypt`, and `kms:Decrypt` permissions on the key. If you change the KMS
     * key and you don't have `kms:Encrypt` permission to the new key, Secrets Manager does not re-encrypt existing secret
     * versions with the new key. For more information, see Secret encryption and decryption [^5].
     *
     * ! When you enter commands in a command shell, there is a risk of the command history being accessed or utilities
     * ! having access to your command parameters. This is a concern if the command includes the value of a secret. Learn
     * ! how to Mitigate the risks of using command-line tools to store Secrets Manager secrets [^6].
     *
     * [^1]: https://docs.aws.amazon.com/secretsmanager/latest/userguide/service-linked-secrets.html
     * [^2]: https://docs.aws.amazon.com/secretsmanager/latest/userguide/retrieve-ct-entries.html
     * [^3]: https://docs.aws.amazon.com/secretsmanager/latest/userguide/reference_iam-permissions.html#reference_iam-permissions_actions
     * [^4]: https://docs.aws.amazon.com/secretsmanager/latest/userguide/auth-and-access.html
     * [^5]: https://docs.aws.amazon.com/secretsmanager/latest/userguide/security-encryption.html
     * [^6]: https://docs.aws.amazon.com/secretsmanager/latest/userguide/security_cli-exposure-risks.html
     *
     * @see https://docs.aws.amazon.com/secretsmanager/latest/apireference/API_UpdateSecret.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-secretsmanager-2017-10-17.html#updatesecret
     *
     * @param array{
     *   SecretId: string,
     *   ClientRequestToken?: string|null,
     *   Description?: string|null,
     *   KmsKeyId?: string|null,
     *   SecretBinary?: string|null,
     *   SecretString?: string|null,
     *   Type?: string|null,
     *   '@region'?: string|null,
     * }|UpdateSecretRequest $input
     *
     * @throws DecryptionFailureException
     * @throws EncryptionFailureException
     * @throws InternalServiceErrorException
     * @throws InvalidParameterException
     * @throws InvalidRequestException
     * @throws LimitExceededException
     * @throws MalformedPolicyDocumentException
     * @throws PreconditionNotMetException
     * @throws ResourceExistsException
     * @throws ResourceNotFoundException
     */
    public function updateSecret($input): UpdateSecretResponse
    {
        $input = UpdateSecretRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'UpdateSecret', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'DecryptionFailure' => DecryptionFailureException::class,
            'EncryptionFailure' => EncryptionFailureException::class,
            'InternalServiceError' => InternalServiceErrorException::class,
            'InvalidParameterException' => InvalidParameterException::class,
            'InvalidRequestException' => InvalidRequestException::class,
            'LimitExceededException' => LimitExceededException::class,
            'MalformedPolicyDocumentException' => MalformedPolicyDocumentException::class,
            'PreconditionNotMetException' => PreconditionNotMetException::class,
            'ResourceExistsException' => ResourceExistsException::class,
            'ResourceNotFoundException' => ResourceNotFoundException::class,
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
            case 'eusc-de-east-1':
                return [
                    'endpoint' => 'https://secretsmanager.eusc-de-east-1.amazonaws.eu',
                    'signRegion' => 'eusc-de-east-1',
                    'signService' => 'secretsmanager',
                    'signVersions' => ['v4'],
                ];
            case 'us-iso-east-1':
            case 'us-iso-east-1-fips':
            case 'us-iso-west-1':
            case 'us-iso-west-1-fips':
                return [
                    'endpoint' => "https://secretsmanager.$region.c2s.ic.gov",
                    'signRegion' => $region,
                    'signService' => 'secretsmanager',
                    'signVersions' => ['v4'],
                ];
            case 'us-isob-east-1':
            case 'us-isob-east-1-fips':
            case 'us-isob-west-1':
                return [
                    'endpoint' => "https://secretsmanager.$region.sc2s.sgov.gov",
                    'signRegion' => $region,
                    'signService' => 'secretsmanager',
                    'signVersions' => ['v4'],
                ];
            case 'us-isof-east-1':
            case 'us-isof-south-1':
                return [
                    'endpoint' => "https://secretsmanager.$region.csp.hci.ic.gov",
                    'signRegion' => $region,
                    'signService' => 'secretsmanager',
                    'signVersions' => ['v4'],
                ];
            case 'eu-isoe-west-1':
                return [
                    'endpoint' => 'https://secretsmanager.eu-isoe-west-1.cloud.adc-e.uk',
                    'signRegion' => 'eu-isoe-west-1',
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
