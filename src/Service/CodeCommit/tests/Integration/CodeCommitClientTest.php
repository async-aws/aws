<?php

namespace AsyncAws\CodeCommit\Tests\Integration;

use AsyncAws\CodeCommit\CodeCommitClient;
use AsyncAws\CodeCommit\Input\GetBlobInput;
use AsyncAws\CodeCommit\Input\GetBranchInput;
use AsyncAws\CodeCommit\Input\GetDifferencesInput;
use AsyncAws\CodeCommit\Input\PutRepositoryTriggersInput;
use AsyncAws\CodeCommit\ValueObject\RepositoryTrigger;
use AsyncAws\Core\Credentials\NullProvider;
use AsyncAws\Core\Test\TestCase;

class CodeCommitClientTest extends TestCase
{
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
