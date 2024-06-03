<?php

namespace AsyncAws\Athena\Tests\Unit\Input;

use AsyncAws\Athena\Input\StartSessionRequest;
use AsyncAws\Athena\ValueObject\EngineConfiguration;
use AsyncAws\Core\Test\TestCase;

class StartSessionRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new StartSessionRequest([
            'Description' => 'iad international asyncAws',
            'WorkGroup' => 'iadinternational',
            'EngineConfiguration' => new EngineConfiguration([
                'CoordinatorDpuSize' => 1337,
                'MaxConcurrentDpus' => 1337,
                'DefaultExecutorDpuSize' => 1337,
                'AdditionalConfigs' => ['contrib' => 'iad'],
            ]),
            'NotebookVersion' => 'v12',
            'SessionIdleTimeoutInMinutes' => 1337,
            'ClientRequestToken' => 'i@d-1452266',
        ]);

        // see https://docs.aws.amazon.com/athena/latest/APIReference/API_StartSession.html
        $expected = '
POST / HTTP/1.0
Content-Type: application/x-amz-json-1.1
x-amz-target: AmazonAthena.StartSession
Accept: application/json

{
    "Description": "iad international asyncAws",
    "WorkGroup": "iadinternational",
    "EngineConfiguration": {
       "CoordinatorDpuSize": 1337,
       "DefaultExecutorDpuSize": 1337,
       "MaxConcurrentDpus": 1337,
       "AdditionalConfigs": {
          "contrib" : "iad"
       }
    },
    "NotebookVersion": "v12",
    "SessionIdleTimeoutInMinutes": 1337,
    "ClientRequestToken": "i@d-1452266"
}
                ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
