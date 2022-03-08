<?php

namespace AsyncAws\CodeBuild\Tests\Unit\Result;

use AsyncAws\CodeBuild\Result\BatchGetBuildsOutput;
use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class BatchGetBuildsOutputTest extends TestCase
{
    public function testBatchGetBuildsOutput(): void
    {
        // see https://docs.aws.amazon.com/codebuild/latest/APIReference/API_BatchGetBuilds.html
        $response = new SimpleMockedResponse('{
           "builds": [
              {
                 "arn": "build-arn",
                 "artifacts": {
                    "artifactIdentifier": "string",
                    "bucketOwnerAccess": "string",
                    "encryptionDisabled": false,
                    "location": "string",
                    "md5sum": "string",
                    "overrideArtifactName": false,
                    "sha256sum": "string"
                 },
                 "buildBatchArn": "string",
                 "buildComplete": false,
                 "buildNumber": 42,
                 "buildStatus": "string",
                 "cache": {
                    "location": "string",
                    "modes": [ "string" ],
                    "type": "string"
                 },
                 "currentPhase": "string",
                 "debugSession": {
                    "sessionEnabled": false,
                    "sessionTarget": "string"
                 },
                 "encryptionKey": "string",
                 "endTime": 42,
                 "environment": {
                    "certificate": "string",
                    "computeType": "string",
                    "environmentVariables": [
                       {
                          "name": "string",
                          "type": "string",
                          "value": "string"
                       }
                    ],
                    "image": "string",
                    "imagePullCredentialsType": "string",
                    "privilegedMode": false,
                    "registryCredential": {
                       "credential": "string",
                       "credentialProvider": "string"
                    },
                    "type": "string"
                 },
                 "exportedEnvironmentVariables": [
                    {
                       "name": "string",
                       "value": "string"
                    }
                 ],
                 "fileSystemLocations": [
                    {
                       "identifier": "string",
                       "location": "string",
                       "mountOptions": "string",
                       "mountPoint": "string",
                       "type": "string"
                    }
                 ],
                 "id": "string",
                 "initiator": "string",
                 "logs": {
                    "cloudWatchLogs": {
                       "groupName": "string",
                       "status": "string",
                       "streamName": "string"
                    },
                    "cloudWatchLogsArn": "string",
                    "deepLink": "string",
                    "groupName": "string",
                    "s3DeepLink": "string",
                    "s3Logs": {
                       "bucketOwnerAccess": "string",
                       "encryptionDisabled": false,
                       "location": "string",
                       "status": "string"
                    },
                    "s3LogsArn": "string",
                    "streamName": "string"
                 },
                 "networkInterface": {
                    "networkInterfaceId": "string",
                    "subnetId": "string"
                 },
                 "phases": [
                    {
                       "contexts": [
                          {
                             "message": "string",
                             "statusCode": "string"
                          }
                       ],
                       "durationInSeconds": 42,
                       "endTime": 42,
                       "phaseStatus": "string",
                       "phaseType": "string",
                       "startTime": 42
                    }
                 ],
                 "projectName": "string",
                 "queuedTimeoutInMinutes": 42,
                 "reportArns": [ "string" ],
                 "resolvedSourceVersion": "string",
                 "secondaryArtifacts": [
                    {
                       "artifactIdentifier": "string",
                       "bucketOwnerAccess": "string",
                       "encryptionDisabled": false,
                       "location": "string",
                       "md5sum": "string",
                       "overrideArtifactName": false,
                       "sha256sum": "string"
                    }
                 ],
                 "secondarySources": [
                    {
                       "auth": {
                          "resource": "string",
                          "type": "string"
                       },
                       "buildspec": "string",
                       "buildStatusConfig": {
                          "context": "string",
                          "targetUrl": "string"
                       },
                       "gitCloneDepth": 42,
                       "gitSubmodulesConfig": {
                          "fetchSubmodules": false
                       },
                       "insecureSsl": false,
                       "location": "string",
                       "reportBuildStatus": false,
                       "sourceIdentifier": "string",
                       "type": "string"
                    }
                 ],
                 "secondarySourceVersions": [
                    {
                       "sourceIdentifier": "string",
                       "sourceVersion": "string"
                    }
                 ],
                 "serviceRole": "string",
                 "source": {
                    "auth": {
                       "resource": "string",
                       "type": "string"
                    },
                    "buildspec": "string",
                    "buildStatusConfig": {
                       "context": "string",
                       "targetUrl": "string"
                    },
                    "gitCloneDepth": 42,
                    "gitSubmodulesConfig": {
                       "fetchSubmodules": false
                    },
                    "insecureSsl": false,
                    "location": "string",
                    "reportBuildStatus": false,
                    "sourceIdentifier": "string",
                    "type": "string"
                 },
                 "sourceVersion": "string",
                 "startTime": 42,
                 "timeoutInMinutes": 42,
                 "vpcConfig": {
                    "securityGroupIds": [ "string" ],
                    "subnets": [ "string" ],
                    "vpcId": "string"
                 }
              }
           ],
           "buildsNotFound": [ "build21" ]
        }');

        $client = new MockHttpClient($response);
        $result = new BatchGetBuildsOutput(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertCount(1, $result->getbuilds());
        self::assertSame('build-arn', $result->getbuilds()[0]->getArn());
        self::assertSame(['build21'], $result->getBuildsNotFound());
    }
}
