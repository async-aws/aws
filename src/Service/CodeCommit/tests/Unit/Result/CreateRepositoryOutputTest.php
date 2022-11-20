<?php

namespace AsyncAws\CodeCommit\Tests\Unit\Result;

use AsyncAws\CodeCommit\Result\CreateRepositoryOutput;
use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class CreateRepositoryOutputTest extends TestCase
{
    public function testCreateRepositoryOutput(): void
    {
        // see https://docs.aws.amazon.com/codecommit/latest/APIReference/API_CreateRepository.html
        $response = new SimpleMockedResponse('{
            "repositoryMetadata": {
                "repositoryName": "MyDemoRepo",
                "cloneUrlSsh": "ssh://git-codecommit.us-east-1.amazonaws.com/v1/repos/MyDemoRepo",
                "lastModifiedDate": 1446071622.494,
                "repositoryDescription": "My demonstration repository",
                "cloneUrlHttp": "https://git-codecommit.us-east-1.amazonaws.com/v1/repos/MyDemoRepo",
                "creationDate": 1446071622.494,
                "repositoryId": "f7579e13-b83e-4027-aaef-650c0EXAMPLE",
                "Arn": "arn:aws:codecommit:us-east-1:123456789012EXAMPLE:MyDemoRepo",
                "accountId": "123456789012"
            }
        }');

        $client = new MockHttpClient($response);
        $result = new CreateRepositoryOutput(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertSame('MyDemoRepo', $result->getrepositoryMetadata()->getRepositoryName());
        self::assertSame('ssh://git-codecommit.us-east-1.amazonaws.com/v1/repos/MyDemoRepo', $result->getrepositoryMetadata()->getCloneUrlSsh());
        self::assertEquals(\DateTimeImmutable::createFromFormat('U.u', '1446071622.494'), $result->getrepositoryMetadata()->getCreationDate());
        self::assertSame('My demonstration repository', $result->getrepositoryMetadata()->getRepositoryDescription());
        self::assertSame('https://git-codecommit.us-east-1.amazonaws.com/v1/repos/MyDemoRepo', $result->getrepositoryMetadata()->getCloneUrlHttp());
        self::assertEquals(\DateTimeImmutable::createFromFormat('U.u', '1446071622.494'), $result->getrepositoryMetadata()->getLastModifiedDate());
        self::assertSame('f7579e13-b83e-4027-aaef-650c0EXAMPLE', $result->getrepositoryMetadata()->getRepositoryId());
        self::assertSame('arn:aws:codecommit:us-east-1:123456789012EXAMPLE:MyDemoRepo', $result->getrepositoryMetadata()->getArn());
        self::assertSame('123456789012', $result->getrepositoryMetadata()->getAccountId());
    }
}
