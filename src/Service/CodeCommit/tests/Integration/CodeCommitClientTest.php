<?php

namespace AsyncAws\CodeCommit\Tests\Integration;

use AsyncAws\CodeCommit\CodeCommitClient;
use AsyncAws\CodeCommit\Input\CreateRepositoryInput;
use AsyncAws\CodeCommit\Input\DeleteRepositoryInput;
use AsyncAws\CodeCommit\Input\GetBlobInput;
use AsyncAws\CodeCommit\Input\GetBranchInput;
use AsyncAws\CodeCommit\Input\GetCommitInput;
use AsyncAws\CodeCommit\Input\GetDifferencesInput;
use AsyncAws\CodeCommit\Input\ListRepositoriesInput;
use AsyncAws\CodeCommit\Input\PutRepositoryTriggersInput;
use AsyncAws\CodeCommit\ValueObject\RepositoryTrigger;
use AsyncAws\Core\Credentials\NullProvider;
use AsyncAws\Core\Test\TestCase;

class CodeCommitClientTest extends TestCase
{
    public function testCreateRepository(): void
    {
        $client = $this->getClient();

        $input = new CreateRepositoryInput([
            'repositoryName' => 'super-repo',
            'repositoryDescription' => 'mY cOdE iS ThE AwESomE',
            'tags' => ['tag1' => 'i can search by this in AWS console'],
        ]);
        $result = $client->createRepository($input);

        $result->resolve();

        self::assertSame('super-repo', $result->getrepositoryMetadata()->getRepositoryName());
        self::assertSame('repositoryDescription', $result->getrepositoryMetadata()->getRepositoryDescription());
        self::assertNotNull($result->getRepositoryMetadata()->getAccountId());
        self::assertNotNull($result->getRepositoryMetadata()->getRepositoryId());
        self::assertNotNull($result->getRepositoryMetadata()->getArn());
        self::assertNotNull($result->getRepositoryMetadata()->getCreationDate());
        self::assertNotNull($result->getRepositoryMetadata()->getLastModifiedDate());
        self::assertNotNull($result->getRepositoryMetadata()->getCloneUrlHttp());
        self::assertNotNull($result->getRepositoryMetadata()->getCloneUrlSsh());
        self::assertNotNull($result->getRepositoryMetadata()->getDefaultBranch());
    }

    public function testDeleteRepository(): void
    {
        $client = $this->getClient();

        $input = new DeleteRepositoryInput([
            'repositoryName' => 'this-code-wasnt-TEH-AWESOME',
        ]);
        $result = $client->deleteRepository($input);

        $result->resolve();

        self::assertNotNull($result->getrepositoryId());
    }

    public function testGetBlob(): void
    {
        $client = $this->getClient();

        $input = new GetBlobInput([
            'repositoryName' => 'change me',
            'blobId' => 'change me',
        ]);
        $result = $client->getBlob($input);

        $result->resolve();

        // self::assertTODO(expected, $result->getcontent());
    }

    public function testGetBranch(): void
    {
        $client = $this->getClient();

        $input = new GetBranchInput([
            'repositoryName' => 'change me',
            'branchName' => 'change me',
        ]);
        $result = $client->getBranch($input);

        $result->resolve();

        // self::assertTODO(expected, $result->getbranch());
    }

    public function testGetCommit(): void
    {
        $client = $this->getClient();

        $input = new GetCommitInput([
            'repositoryName' => 'change me',
            'commitId' => 'change me',
        ]);
        $result = $client->getCommit($input);

        $result->resolve();

        // self::assertTODO(expected, $result->getcommit());
    }

    public function testGetDifferences(): void
    {
        $client = $this->getClient();

        $input = new GetDifferencesInput([
            'repositoryName' => 'change me',
            'beforeCommitSpecifier' => 'change me',
            'afterCommitSpecifier' => 'change me',
            'beforePath' => 'change me',
            'afterPath' => 'change me',
            'MaxResults' => 1337,
            'NextToken' => 'change me',
        ]);
        $result = $client->getDifferences($input);

        $result->resolve();

        // self::assertTODO(expected, $result->getdifferences());
        self::assertSame('changeIt', $result->getNextToken());
    }

    public function testListRepositories(): void
    {
        $client = $this->getClient();

        $input = new ListRepositoriesInput([
            'nextToken' => 'NEXT_TOK',
            'sortBy' => 'repositoryName',
            'order' => 'ascending',
        ]);
        $result = $client->listRepositories($input);

        $result->resolve();

        self::assertSame('NEXT_TOK', $result->getnextToken());
    }

    public function testPutRepositoryTriggers(): void
    {
        $client = $this->getClient();

        $input = new PutRepositoryTriggersInput([
            'repositoryName' => 'async-aws-monorepo',
            'triggers' => [new RepositoryTrigger([
                'name' => 'NotifyOfCodeChangesInMainBranch',
                'destinationArn' => 'arn:aws:lambda:eu-west-1:123456789012:function:my-function',
                'customData' => 'any additional data you want for the context',
                'branches' => ['main'],
                'events' => ['createReference', 'deleteReference', 'updateReference'],
            ])],
        ]);
        $result = $client->putRepositoryTriggers($input);

        $result->resolve();

        self::assertSame('changeIt', $result->getconfigurationId());
    }

    private function getClient(): CodeCommitClient
    {
        self::markTestSkipped('Localstack does not support CodeCommit in the free version');

        return new CodeCommitClient([
            'endpoint' => 'http://localhost',
        ], new NullProvider());
    }
}
