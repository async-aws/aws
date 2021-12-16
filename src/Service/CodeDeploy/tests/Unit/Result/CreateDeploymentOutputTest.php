<?php

namespace AsyncAws\CodeDeploy\Tests\Unit\Result;

use AsyncAws\CodeDeploy\Result\CreateDeploymentOutput;
use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class CreateDeploymentOutputTest extends TestCase
{
    public function testCreateDeploymentOutput(): void
    {
        // see https://docs.aws.amazon.com/codedeploy/latest/APIReference/API_CreateDeployment.html
        $response = new SimpleMockedResponse('{
           "deploymentId": "deployment-id"
        }');

        $client = new MockHttpClient($response);
        $result = new CreateDeploymentOutput(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertSame('deployment-id', $result->getdeploymentId());
    }
}
