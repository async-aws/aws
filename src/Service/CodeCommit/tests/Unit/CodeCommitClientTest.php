<?php

namespace AsyncAws\CodeCommit\Tests\Unit;

use AsyncAws\CodeCommit\CodeCommitClient;
use AsyncAws\CodeCommit\Input\GetBlobInput;
use AsyncAws\CodeCommit\Input\GetBranchInput;
use AsyncAws\CodeCommit\Input\GetDifferencesInput;
use AsyncAws\CodeCommit\Input\PutRepositoryTriggersInput;
use AsyncAws\CodeCommit\Result\GetBlobOutput;
use AsyncAws\CodeCommit\Result\GetBranchOutput;
use AsyncAws\CodeCommit\Result\GetDifferencesOutput;
use AsyncAws\CodeCommit\Result\PutRepositoryTriggersOutput;
use AsyncAws\CodeCommit\ValueObject\RepositoryTrigger;
use AsyncAws\Core\Credentials\NullProvider;
use AsyncAws\Core\Test\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;

class CodeCommitClientTest extends TestCase
{
    public function testGetBlob(): void
    {
        $client = new CodeCommitClient([], new NullProvider(), new MockHttpClient());

        $input = new GetBlobInput([
            'repositoryName' => 'change me',
            'blobId' => 'change me',
        ]);
        $result = $client->getBlob($input);

        self::assertInstanceOf(GetBlobOutput::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testGetBranch(): void
    {
        $client = new CodeCommitClient([], new NullProvider(), new MockHttpClient());

        $input = new GetBranchInput([

        ]);
        $result = $client->getBranch($input);

        self::assertInstanceOf(GetBranchOutput::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testGetDifferences(): void
    {
        $client = new CodeCommitClient([], new NullProvider(), new MockHttpClient());

        $input = new GetDifferencesInput([
            'repositoryName' => 'my-super-code-repository',

            'afterCommitSpecifier' => 'change me',

        ]);
        $result = $client->getDifferences($input);

        self::assertInstanceOf(GetDifferencesOutput::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testPutRepositoryTriggers(): void
    {
        $client = new CodeCommitClient([], new NullProvider(), new MockHttpClient());

        $input = new PutRepositoryTriggersInput([
            'repositoryName' => 'my-super-code-repository',
            'triggers' => [new RepositoryTrigger([
                'name' => 'NotifyMeOfCodeChanges',
                'destinationArn' => 'arn:aws:lambda:eu-west-1:123456789012:function:my-function',

                'events' => ['createReference', 'deleteReference', 'updateReference'],
            ])],
        ]);
        $result = $client->putRepositoryTriggers($input);

        self::assertInstanceOf(PutRepositoryTriggersOutput::class, $result);
        self::assertFalse($result->info()['resolved']);
    }
}
