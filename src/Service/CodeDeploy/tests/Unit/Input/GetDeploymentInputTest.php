<?php

namespace AsyncAws\CodeDeploy\Tests\Unit\Input;

use AsyncAws\CodeDeploy\Input\GetDeploymentInput;
use AsyncAws\Core\Test\TestCase;

class GetDeploymentInputTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new GetDeploymentInput([
            'deploymentId' => '123',
        ]);

        // see https://docs.aws.amazon.com/codedeploy/latest/APIReference/API_GetDeployment.html
        $expected = '
POST / HTTP/1.0
Content-Type: application/x-amz-json-1.1
x-amz-target: CodeDeploy_20141006.GetDeployment

{
    "deploymentId": "123"
}
';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
