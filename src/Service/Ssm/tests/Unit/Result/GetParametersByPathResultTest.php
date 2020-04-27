<?php

namespace AsyncAws\Ssm\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\Ssm\Input\GetParametersByPathRequest;
use AsyncAws\Ssm\Result\GetParametersByPathResult;
use AsyncAws\Ssm\SsmClient;
use AsyncAws\Ssm\ValueObject\Parameter;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class GetParametersByPathResultTest extends TestCase
{
    public function testGetParametersByPathResult(): void
    {
        // see https://docs.aws.amazon.com/ssm/latest/APIReference/API_GetParametersByPath.html
        $response = new SimpleMockedResponse('{
            "Parameters": [
                {
                    "Name": "/Branch312/Dev/Engineer1",
                    "Type": "String",
                    "Value": "Saanvi Sarkar",
                    "Version": 1
                },
                {
                    "Name": "/Branch312/Dev/Engineer2",
                    "Type": "String",
                    "Value": "Zhang Wei",
                    "Version": 1
                },
                {
                    "Name": "/Branch312/Dev/Engineer3",
                    "Type": "String",
                    "Value": "Alejandro Rosalez",
                    "Version": 1
                },
                {
                    "Name": "/Branch312/Dev/Intern",
                    "Type": "String",
                    "Value": "Nikhil Jayashankar",
                    "Version": 1
                },
                {
                    "Name": "/Branch312/Dev/TeamLead",
                    "Type": "String",
                    "Value": "Jane Roe",
                    "Version": 1
                }
            ]
        }');

        $client = new MockHttpClient($response);
        $result = new GetParametersByPathResult(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()), new SsmClient(), new GetParametersByPathRequest());

        self::assertCount(5, $result->getParameters());
        self::assertInstanceOf(Parameter::class, \iterator_to_array($result->getParameters())[0]);
        self::assertSame('/Branch312/Dev/Engineer1', iterator_to_array($result->getParameters())[0]->getName());
        self::assertCount(5, $result);
        self::assertInstanceOf(Parameter::class, iterator_to_array($result)[0]);
    }
}
