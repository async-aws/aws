<?php

namespace AsyncAws\CodeCommit;

use AsyncAws\CodeCommit\Enum\OrderEnum;
use AsyncAws\CodeCommit\Enum\SortByEnum;
use AsyncAws\CodeCommit\Exception\BlobIdDoesNotExistException;
use AsyncAws\CodeCommit\Exception\BlobIdRequiredException;
use AsyncAws\CodeCommit\Exception\BranchDoesNotExistException;
use AsyncAws\CodeCommit\Exception\BranchNameRequiredException;
use AsyncAws\CodeCommit\Exception\CommitDoesNotExistException;
use AsyncAws\CodeCommit\Exception\CommitIdDoesNotExistException;
use AsyncAws\CodeCommit\Exception\CommitIdRequiredException;
use AsyncAws\CodeCommit\Exception\CommitRequiredException;
use AsyncAws\CodeCommit\Exception\EncryptionIntegrityChecksFailedException;
use AsyncAws\CodeCommit\Exception\EncryptionKeyAccessDeniedException;
use AsyncAws\CodeCommit\Exception\EncryptionKeyDisabledException;
use AsyncAws\CodeCommit\Exception\EncryptionKeyNotFoundException;
use AsyncAws\CodeCommit\Exception\EncryptionKeyUnavailableException;
use AsyncAws\CodeCommit\Exception\FileTooLargeException;
use AsyncAws\CodeCommit\Exception\InvalidBlobIdException;
use AsyncAws\CodeCommit\Exception\InvalidBranchNameException;
use AsyncAws\CodeCommit\Exception\InvalidCommitException;
use AsyncAws\CodeCommit\Exception\InvalidCommitIdException;
use AsyncAws\CodeCommit\Exception\InvalidContinuationTokenException;
use AsyncAws\CodeCommit\Exception\InvalidMaxResultsException;
use AsyncAws\CodeCommit\Exception\InvalidOrderException;
use AsyncAws\CodeCommit\Exception\InvalidPathException;
use AsyncAws\CodeCommit\Exception\InvalidRepositoryDescriptionException;
use AsyncAws\CodeCommit\Exception\InvalidRepositoryNameException;
use AsyncAws\CodeCommit\Exception\InvalidRepositoryTriggerBranchNameException;
use AsyncAws\CodeCommit\Exception\InvalidRepositoryTriggerCustomDataException;
use AsyncAws\CodeCommit\Exception\InvalidRepositoryTriggerDestinationArnException;
use AsyncAws\CodeCommit\Exception\InvalidRepositoryTriggerEventsException;
use AsyncAws\CodeCommit\Exception\InvalidRepositoryTriggerNameException;
use AsyncAws\CodeCommit\Exception\InvalidRepositoryTriggerRegionException;
use AsyncAws\CodeCommit\Exception\InvalidSortByException;
use AsyncAws\CodeCommit\Exception\InvalidSystemTagUsageException;
use AsyncAws\CodeCommit\Exception\InvalidTagsMapException;
use AsyncAws\CodeCommit\Exception\MaximumBranchesExceededException;
use AsyncAws\CodeCommit\Exception\MaximumRepositoryTriggersExceededException;
use AsyncAws\CodeCommit\Exception\PathDoesNotExistException;
use AsyncAws\CodeCommit\Exception\RepositoryDoesNotExistException;
use AsyncAws\CodeCommit\Exception\RepositoryLimitExceededException;
use AsyncAws\CodeCommit\Exception\RepositoryNameExistsException;
use AsyncAws\CodeCommit\Exception\RepositoryNameRequiredException;
use AsyncAws\CodeCommit\Exception\RepositoryTriggerBranchNameListRequiredException;
use AsyncAws\CodeCommit\Exception\RepositoryTriggerDestinationArnRequiredException;
use AsyncAws\CodeCommit\Exception\RepositoryTriggerEventsListRequiredException;
use AsyncAws\CodeCommit\Exception\RepositoryTriggerNameRequiredException;
use AsyncAws\CodeCommit\Exception\RepositoryTriggersListRequiredException;
use AsyncAws\CodeCommit\Exception\TagPolicyException;
use AsyncAws\CodeCommit\Exception\TooManyTagsException;
use AsyncAws\CodeCommit\Input\CreateRepositoryInput;
use AsyncAws\CodeCommit\Input\DeleteRepositoryInput;
use AsyncAws\CodeCommit\Input\GetBlobInput;
use AsyncAws\CodeCommit\Input\GetBranchInput;
use AsyncAws\CodeCommit\Input\GetCommitInput;
use AsyncAws\CodeCommit\Input\GetDifferencesInput;
use AsyncAws\CodeCommit\Input\ListRepositoriesInput;
use AsyncAws\CodeCommit\Input\PutRepositoryTriggersInput;
use AsyncAws\CodeCommit\Result\CreateRepositoryOutput;
use AsyncAws\CodeCommit\Result\DeleteRepositoryOutput;
use AsyncAws\CodeCommit\Result\GetBlobOutput;
use AsyncAws\CodeCommit\Result\GetBranchOutput;
use AsyncAws\CodeCommit\Result\GetCommitOutput;
use AsyncAws\CodeCommit\Result\GetDifferencesOutput;
use AsyncAws\CodeCommit\Result\ListRepositoriesOutput;
use AsyncAws\CodeCommit\Result\PutRepositoryTriggersOutput;
use AsyncAws\CodeCommit\ValueObject\RepositoryTrigger;
use AsyncAws\Core\AbstractApi;
use AsyncAws\Core\AwsError\AwsErrorFactoryInterface;
use AsyncAws\Core\AwsError\JsonRpcAwsErrorFactory;
use AsyncAws\Core\Configuration;
use AsyncAws\Core\RequestContext;

class CodeCommitClient extends AbstractApi
{
    /**
     * Creates a new, empty repository.
     *
     * @see https://docs.aws.amazon.com/codecommit/latest/APIReference/API_CreateRepository.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-codecommit-2015-04-13.html#createrepository
     *
     * @param array{
     *   repositoryName: string,
     *   repositoryDescription?: string,
     *   tags?: array<string, string>,
     *   '@region'?: string|null,
     * }|CreateRepositoryInput $input
     *
     * @throws RepositoryNameExistsException
     * @throws RepositoryNameRequiredException
     * @throws InvalidRepositoryNameException
     * @throws InvalidRepositoryDescriptionException
     * @throws RepositoryLimitExceededException
     * @throws EncryptionIntegrityChecksFailedException
     * @throws EncryptionKeyAccessDeniedException
     * @throws EncryptionKeyDisabledException
     * @throws EncryptionKeyNotFoundException
     * @throws EncryptionKeyUnavailableException
     * @throws InvalidTagsMapException
     * @throws TooManyTagsException
     * @throws InvalidSystemTagUsageException
     * @throws TagPolicyException
     */
    public function createRepository($input): CreateRepositoryOutput
    {
        $input = CreateRepositoryInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'CreateRepository', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'RepositoryNameExistsException' => RepositoryNameExistsException::class,
            'RepositoryNameRequiredException' => RepositoryNameRequiredException::class,
            'InvalidRepositoryNameException' => InvalidRepositoryNameException::class,
            'InvalidRepositoryDescriptionException' => InvalidRepositoryDescriptionException::class,
            'RepositoryLimitExceededException' => RepositoryLimitExceededException::class,
            'EncryptionIntegrityChecksFailedException' => EncryptionIntegrityChecksFailedException::class,
            'EncryptionKeyAccessDeniedException' => EncryptionKeyAccessDeniedException::class,
            'EncryptionKeyDisabledException' => EncryptionKeyDisabledException::class,
            'EncryptionKeyNotFoundException' => EncryptionKeyNotFoundException::class,
            'EncryptionKeyUnavailableException' => EncryptionKeyUnavailableException::class,
            'InvalidTagsMapException' => InvalidTagsMapException::class,
            'TooManyTagsException' => TooManyTagsException::class,
            'InvalidSystemTagUsageException' => InvalidSystemTagUsageException::class,
            'TagPolicyException' => TagPolicyException::class,
        ]]));

        return new CreateRepositoryOutput($response);
    }

    /**
     * Deletes a repository. If a specified repository was already deleted, a null repository ID is returned.
     *
     * ! Deleting a repository also deletes all associated objects and metadata. After a repository is deleted, all future
     * ! push calls to the deleted repository fail.
     *
     * @see https://docs.aws.amazon.com/codecommit/latest/APIReference/API_DeleteRepository.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-codecommit-2015-04-13.html#deleterepository
     *
     * @param array{
     *   repositoryName: string,
     *   '@region'?: string|null,
     * }|DeleteRepositoryInput $input
     *
     * @throws RepositoryNameRequiredException
     * @throws InvalidRepositoryNameException
     * @throws EncryptionIntegrityChecksFailedException
     * @throws EncryptionKeyAccessDeniedException
     * @throws EncryptionKeyDisabledException
     * @throws EncryptionKeyNotFoundException
     * @throws EncryptionKeyUnavailableException
     */
    public function deleteRepository($input): DeleteRepositoryOutput
    {
        $input = DeleteRepositoryInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'DeleteRepository', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'RepositoryNameRequiredException' => RepositoryNameRequiredException::class,
            'InvalidRepositoryNameException' => InvalidRepositoryNameException::class,
            'EncryptionIntegrityChecksFailedException' => EncryptionIntegrityChecksFailedException::class,
            'EncryptionKeyAccessDeniedException' => EncryptionKeyAccessDeniedException::class,
            'EncryptionKeyDisabledException' => EncryptionKeyDisabledException::class,
            'EncryptionKeyNotFoundException' => EncryptionKeyNotFoundException::class,
            'EncryptionKeyUnavailableException' => EncryptionKeyUnavailableException::class,
        ]]));

        return new DeleteRepositoryOutput($response);
    }

    /**
     * Returns the base-64 encoded content of an individual blob in a repository.
     *
     * @see https://docs.aws.amazon.com/codecommit/latest/APIReference/API_GetBlob.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-codecommit-2015-04-13.html#getblob
     *
     * @param array{
     *   repositoryName: string,
     *   blobId: string,
     *   '@region'?: string|null,
     * }|GetBlobInput $input
     *
     * @throws RepositoryNameRequiredException
     * @throws InvalidRepositoryNameException
     * @throws RepositoryDoesNotExistException
     * @throws BlobIdRequiredException
     * @throws InvalidBlobIdException
     * @throws BlobIdDoesNotExistException
     * @throws EncryptionIntegrityChecksFailedException
     * @throws EncryptionKeyAccessDeniedException
     * @throws EncryptionKeyDisabledException
     * @throws EncryptionKeyNotFoundException
     * @throws EncryptionKeyUnavailableException
     * @throws FileTooLargeException
     */
    public function getBlob($input): GetBlobOutput
    {
        $input = GetBlobInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'GetBlob', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'RepositoryNameRequiredException' => RepositoryNameRequiredException::class,
            'InvalidRepositoryNameException' => InvalidRepositoryNameException::class,
            'RepositoryDoesNotExistException' => RepositoryDoesNotExistException::class,
            'BlobIdRequiredException' => BlobIdRequiredException::class,
            'InvalidBlobIdException' => InvalidBlobIdException::class,
            'BlobIdDoesNotExistException' => BlobIdDoesNotExistException::class,
            'EncryptionIntegrityChecksFailedException' => EncryptionIntegrityChecksFailedException::class,
            'EncryptionKeyAccessDeniedException' => EncryptionKeyAccessDeniedException::class,
            'EncryptionKeyDisabledException' => EncryptionKeyDisabledException::class,
            'EncryptionKeyNotFoundException' => EncryptionKeyNotFoundException::class,
            'EncryptionKeyUnavailableException' => EncryptionKeyUnavailableException::class,
            'FileTooLargeException' => FileTooLargeException::class,
        ]]));

        return new GetBlobOutput($response);
    }

    /**
     * Returns information about a repository branch, including its name and the last commit ID.
     *
     * @see https://docs.aws.amazon.com/codecommit/latest/APIReference/API_GetBranch.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-codecommit-2015-04-13.html#getbranch
     *
     * @param array{
     *   repositoryName?: string,
     *   branchName?: string,
     *   '@region'?: string|null,
     * }|GetBranchInput $input
     *
     * @throws RepositoryNameRequiredException
     * @throws RepositoryDoesNotExistException
     * @throws InvalidRepositoryNameException
     * @throws BranchNameRequiredException
     * @throws InvalidBranchNameException
     * @throws BranchDoesNotExistException
     * @throws EncryptionIntegrityChecksFailedException
     * @throws EncryptionKeyAccessDeniedException
     * @throws EncryptionKeyDisabledException
     * @throws EncryptionKeyNotFoundException
     * @throws EncryptionKeyUnavailableException
     */
    public function getBranch($input = []): GetBranchOutput
    {
        $input = GetBranchInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'GetBranch', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'RepositoryNameRequiredException' => RepositoryNameRequiredException::class,
            'RepositoryDoesNotExistException' => RepositoryDoesNotExistException::class,
            'InvalidRepositoryNameException' => InvalidRepositoryNameException::class,
            'BranchNameRequiredException' => BranchNameRequiredException::class,
            'InvalidBranchNameException' => InvalidBranchNameException::class,
            'BranchDoesNotExistException' => BranchDoesNotExistException::class,
            'EncryptionIntegrityChecksFailedException' => EncryptionIntegrityChecksFailedException::class,
            'EncryptionKeyAccessDeniedException' => EncryptionKeyAccessDeniedException::class,
            'EncryptionKeyDisabledException' => EncryptionKeyDisabledException::class,
            'EncryptionKeyNotFoundException' => EncryptionKeyNotFoundException::class,
            'EncryptionKeyUnavailableException' => EncryptionKeyUnavailableException::class,
        ]]));

        return new GetBranchOutput($response);
    }

    /**
     * Returns information about a commit, including commit message and committer information.
     *
     * @see https://docs.aws.amazon.com/codecommit/latest/APIReference/API_GetCommit.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-codecommit-2015-04-13.html#getcommit
     *
     * @param array{
     *   repositoryName: string,
     *   commitId: string,
     *   '@region'?: string|null,
     * }|GetCommitInput $input
     *
     * @throws RepositoryNameRequiredException
     * @throws InvalidRepositoryNameException
     * @throws RepositoryDoesNotExistException
     * @throws CommitIdRequiredException
     * @throws InvalidCommitIdException
     * @throws CommitIdDoesNotExistException
     * @throws EncryptionIntegrityChecksFailedException
     * @throws EncryptionKeyAccessDeniedException
     * @throws EncryptionKeyDisabledException
     * @throws EncryptionKeyNotFoundException
     * @throws EncryptionKeyUnavailableException
     */
    public function getCommit($input): GetCommitOutput
    {
        $input = GetCommitInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'GetCommit', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'RepositoryNameRequiredException' => RepositoryNameRequiredException::class,
            'InvalidRepositoryNameException' => InvalidRepositoryNameException::class,
            'RepositoryDoesNotExistException' => RepositoryDoesNotExistException::class,
            'CommitIdRequiredException' => CommitIdRequiredException::class,
            'InvalidCommitIdException' => InvalidCommitIdException::class,
            'CommitIdDoesNotExistException' => CommitIdDoesNotExistException::class,
            'EncryptionIntegrityChecksFailedException' => EncryptionIntegrityChecksFailedException::class,
            'EncryptionKeyAccessDeniedException' => EncryptionKeyAccessDeniedException::class,
            'EncryptionKeyDisabledException' => EncryptionKeyDisabledException::class,
            'EncryptionKeyNotFoundException' => EncryptionKeyNotFoundException::class,
            'EncryptionKeyUnavailableException' => EncryptionKeyUnavailableException::class,
        ]]));

        return new GetCommitOutput($response);
    }

    /**
     * Returns information about the differences in a valid commit specifier (such as a branch, tag, HEAD, commit ID, or
     * other fully qualified reference). Results can be limited to a specified path.
     *
     * @see https://docs.aws.amazon.com/codecommit/latest/APIReference/API_GetDifferences.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-codecommit-2015-04-13.html#getdifferences
     *
     * @param array{
     *   repositoryName: string,
     *   beforeCommitSpecifier?: string,
     *   afterCommitSpecifier: string,
     *   beforePath?: string,
     *   afterPath?: string,
     *   MaxResults?: int,
     *   NextToken?: string,
     *   '@region'?: string|null,
     * }|GetDifferencesInput $input
     *
     * @throws RepositoryNameRequiredException
     * @throws RepositoryDoesNotExistException
     * @throws InvalidRepositoryNameException
     * @throws InvalidContinuationTokenException
     * @throws InvalidMaxResultsException
     * @throws InvalidCommitIdException
     * @throws CommitRequiredException
     * @throws InvalidCommitException
     * @throws CommitDoesNotExistException
     * @throws InvalidPathException
     * @throws PathDoesNotExistException
     * @throws EncryptionIntegrityChecksFailedException
     * @throws EncryptionKeyAccessDeniedException
     * @throws EncryptionKeyDisabledException
     * @throws EncryptionKeyNotFoundException
     * @throws EncryptionKeyUnavailableException
     */
    public function getDifferences($input): GetDifferencesOutput
    {
        $input = GetDifferencesInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'GetDifferences', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'RepositoryNameRequiredException' => RepositoryNameRequiredException::class,
            'RepositoryDoesNotExistException' => RepositoryDoesNotExistException::class,
            'InvalidRepositoryNameException' => InvalidRepositoryNameException::class,
            'InvalidContinuationTokenException' => InvalidContinuationTokenException::class,
            'InvalidMaxResultsException' => InvalidMaxResultsException::class,
            'InvalidCommitIdException' => InvalidCommitIdException::class,
            'CommitRequiredException' => CommitRequiredException::class,
            'InvalidCommitException' => InvalidCommitException::class,
            'CommitDoesNotExistException' => CommitDoesNotExistException::class,
            'InvalidPathException' => InvalidPathException::class,
            'PathDoesNotExistException' => PathDoesNotExistException::class,
            'EncryptionIntegrityChecksFailedException' => EncryptionIntegrityChecksFailedException::class,
            'EncryptionKeyAccessDeniedException' => EncryptionKeyAccessDeniedException::class,
            'EncryptionKeyDisabledException' => EncryptionKeyDisabledException::class,
            'EncryptionKeyNotFoundException' => EncryptionKeyNotFoundException::class,
            'EncryptionKeyUnavailableException' => EncryptionKeyUnavailableException::class,
        ]]));

        return new GetDifferencesOutput($response, $this, $input);
    }

    /**
     * Gets information about one or more repositories.
     *
     * @see https://docs.aws.amazon.com/codecommit/latest/APIReference/API_ListRepositories.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-codecommit-2015-04-13.html#listrepositories
     *
     * @param array{
     *   nextToken?: string,
     *   sortBy?: SortByEnum::*,
     *   order?: OrderEnum::*,
     *   '@region'?: string|null,
     * }|ListRepositoriesInput $input
     *
     * @throws InvalidSortByException
     * @throws InvalidOrderException
     * @throws InvalidContinuationTokenException
     */
    public function listRepositories($input = []): ListRepositoriesOutput
    {
        $input = ListRepositoriesInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'ListRepositories', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'InvalidSortByException' => InvalidSortByException::class,
            'InvalidOrderException' => InvalidOrderException::class,
            'InvalidContinuationTokenException' => InvalidContinuationTokenException::class,
        ]]));

        return new ListRepositoriesOutput($response, $this, $input);
    }

    /**
     * Replaces all triggers for a repository. Used to create or delete triggers.
     *
     * @see https://docs.aws.amazon.com/codecommit/latest/APIReference/API_PutRepositoryTriggers.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-codecommit-2015-04-13.html#putrepositorytriggers
     *
     * @param array{
     *   repositoryName: string,
     *   triggers: RepositoryTrigger[],
     *   '@region'?: string|null,
     * }|PutRepositoryTriggersInput $input
     *
     * @throws RepositoryDoesNotExistException
     * @throws RepositoryNameRequiredException
     * @throws InvalidRepositoryNameException
     * @throws RepositoryTriggersListRequiredException
     * @throws MaximumRepositoryTriggersExceededException
     * @throws InvalidRepositoryTriggerNameException
     * @throws InvalidRepositoryTriggerDestinationArnException
     * @throws InvalidRepositoryTriggerRegionException
     * @throws InvalidRepositoryTriggerCustomDataException
     * @throws MaximumBranchesExceededException
     * @throws InvalidRepositoryTriggerBranchNameException
     * @throws InvalidRepositoryTriggerEventsException
     * @throws RepositoryTriggerNameRequiredException
     * @throws RepositoryTriggerDestinationArnRequiredException
     * @throws RepositoryTriggerBranchNameListRequiredException
     * @throws RepositoryTriggerEventsListRequiredException
     * @throws EncryptionIntegrityChecksFailedException
     * @throws EncryptionKeyAccessDeniedException
     * @throws EncryptionKeyDisabledException
     * @throws EncryptionKeyNotFoundException
     * @throws EncryptionKeyUnavailableException
     */
    public function putRepositoryTriggers($input): PutRepositoryTriggersOutput
    {
        $input = PutRepositoryTriggersInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'PutRepositoryTriggers', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'RepositoryDoesNotExistException' => RepositoryDoesNotExistException::class,
            'RepositoryNameRequiredException' => RepositoryNameRequiredException::class,
            'InvalidRepositoryNameException' => InvalidRepositoryNameException::class,
            'RepositoryTriggersListRequiredException' => RepositoryTriggersListRequiredException::class,
            'MaximumRepositoryTriggersExceededException' => MaximumRepositoryTriggersExceededException::class,
            'InvalidRepositoryTriggerNameException' => InvalidRepositoryTriggerNameException::class,
            'InvalidRepositoryTriggerDestinationArnException' => InvalidRepositoryTriggerDestinationArnException::class,
            'InvalidRepositoryTriggerRegionException' => InvalidRepositoryTriggerRegionException::class,
            'InvalidRepositoryTriggerCustomDataException' => InvalidRepositoryTriggerCustomDataException::class,
            'MaximumBranchesExceededException' => MaximumBranchesExceededException::class,
            'InvalidRepositoryTriggerBranchNameException' => InvalidRepositoryTriggerBranchNameException::class,
            'InvalidRepositoryTriggerEventsException' => InvalidRepositoryTriggerEventsException::class,
            'RepositoryTriggerNameRequiredException' => RepositoryTriggerNameRequiredException::class,
            'RepositoryTriggerDestinationArnRequiredException' => RepositoryTriggerDestinationArnRequiredException::class,
            'RepositoryTriggerBranchNameListRequiredException' => RepositoryTriggerBranchNameListRequiredException::class,
            'RepositoryTriggerEventsListRequiredException' => RepositoryTriggerEventsListRequiredException::class,
            'EncryptionIntegrityChecksFailedException' => EncryptionIntegrityChecksFailedException::class,
            'EncryptionKeyAccessDeniedException' => EncryptionKeyAccessDeniedException::class,
            'EncryptionKeyDisabledException' => EncryptionKeyDisabledException::class,
            'EncryptionKeyNotFoundException' => EncryptionKeyNotFoundException::class,
            'EncryptionKeyUnavailableException' => EncryptionKeyUnavailableException::class,
        ]]));

        return new PutRepositoryTriggersOutput($response);
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
                    'endpoint' => "https://codecommit.$region.amazonaws.com.cn",
                    'signRegion' => $region,
                    'signService' => 'codecommit',
                    'signVersions' => ['v4'],
                ];
            case 'fips':
            case 'us-gov-west-1-fips':
                return [
                    'endpoint' => 'https://codecommit-fips.us-gov-west-1.amazonaws.com',
                    'signRegion' => 'us-gov-west-1',
                    'signService' => 'codecommit',
                    'signVersions' => ['v4'],
                ];
            case 'ca-central-1-fips':
                return [
                    'endpoint' => 'https://codecommit-fips.ca-central-1.amazonaws.com',
                    'signRegion' => 'ca-central-1',
                    'signService' => 'codecommit',
                    'signVersions' => ['v4'],
                ];
            case 'us-east-1-fips':
                return [
                    'endpoint' => 'https://codecommit-fips.us-east-1.amazonaws.com',
                    'signRegion' => 'us-east-1',
                    'signService' => 'codecommit',
                    'signVersions' => ['v4'],
                ];
            case 'us-east-2-fips':
                return [
                    'endpoint' => 'https://codecommit-fips.us-east-2.amazonaws.com',
                    'signRegion' => 'us-east-2',
                    'signService' => 'codecommit',
                    'signVersions' => ['v4'],
                ];
            case 'us-west-1-fips':
                return [
                    'endpoint' => 'https://codecommit-fips.us-west-1.amazonaws.com',
                    'signRegion' => 'us-west-1',
                    'signService' => 'codecommit',
                    'signVersions' => ['v4'],
                ];
            case 'us-west-2-fips':
                return [
                    'endpoint' => 'https://codecommit-fips.us-west-2.amazonaws.com',
                    'signRegion' => 'us-west-2',
                    'signService' => 'codecommit',
                    'signVersions' => ['v4'],
                ];
            case 'us-gov-east-1-fips':
                return [
                    'endpoint' => 'https://codecommit-fips.us-gov-east-1.amazonaws.com',
                    'signRegion' => 'us-gov-east-1',
                    'signService' => 'codecommit',
                    'signVersions' => ['v4'],
                ];
        }

        return [
            'endpoint' => "https://codecommit.$region.amazonaws.com",
            'signRegion' => $region,
            'signService' => 'codecommit',
            'signVersions' => ['v4'],
        ];
    }
}
