<?php

namespace AsyncAws\CodeCommit\Tests\Unit\Input;

use AsyncAws\CodeCommit\Input\PutRepositoryTriggersInput;
use AsyncAws\CodeCommit\ValueObject\RepositoryTrigger;
use AsyncAws\Core\Test\TestCase;

class PutRepositoryTriggersInputTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new PutRepositoryTriggersInput([
            'repositoryName' => 'my-super-code-repository',
            'triggers' => [new RepositoryTrigger([
                'name' => 'NotifyMeOfCodeChanges',
                'destinationArn' => 'arn:aws:lambda:eu-west-1:123456789012:function:my-function',
                'customData' => 'Any custom data associated with the trigger to be included in the information sent to the target of the trigger.',
                'branches' => ['main', 'release', 'development', 'git-flow'],
                'events' => ['createReference', 'deleteReference', 'updateReference'],
            ])],
        ]);

        // see https://docs.aws.amazon.com/codecommit/latest/APIReference/API_PutRepositoryTriggers.html
        $expected = '
            POST / HTTP/1.0
            Content-Type: application/x-amz-json-1.1
            x-amz-target: CodeCommit_20150413.PutRepositoryTriggers

        {
            "repositoryName": "my-super-code-repository",
            "triggers": [
                {
                    "branches": [
                        "main",
                        "release",
                        "development",
                        "git-flow"
                    ],
                    "customData": "Any custom data associated with the trigger to be included in the information sent to the target of the trigger.",
                    "destinationArn": "arn:aws:lambda:eu-west-1:123456789012:function:my-function",
                    "events": [
                        "createReference",
                        "deleteReference",
                        "updateReference"
                    ],
                    "name": "NotifyMeOfCodeChanges"
                }
            ]
        }

                ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
