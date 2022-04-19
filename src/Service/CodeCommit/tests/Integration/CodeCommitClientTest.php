<?php

namespace AsyncAws\CodeCommit\Tests\Integration;

use AsyncAws\CodeCommit\CodeCommitClient;
use AsyncAws\CodeCommit\Input\GetBlobInput;
use AsyncAws\CodeCommit\Input\GetBranchInput;
use AsyncAws\CodeCommit\Input\GetDifferencesInput;
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

    private function getClient(): CodeCommitClient
    {
        self::fail('Not implemented');

        return new CodeCommitClient([
            'endpoint' => 'http://localhost',
        ], new NullProvider());
    }
}
