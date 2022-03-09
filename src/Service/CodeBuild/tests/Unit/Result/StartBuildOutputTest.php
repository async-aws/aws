<?php

namespace AsyncAws\CodeBuild\Tests\Unit\Result;

use AsyncAws\CodeBuild\Result\StartBuildOutput;
use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class StartBuildOutputTest extends TestCase
{
    public function testStartBuildOutput(): void
    {
        // see https://docs.aws.amazon.com/codebuild/latest/APIReference/API_StartBuild.html
        $response = new SimpleMockedResponse('{
           "build": {
              "arn": "build-arn",
              "artifacts": {
                 "artifactIdentifier": "string",
                 "bucketOwnerAccess": "string",
                 "encryptionDisabled": true,
                 "location": "string",
                 "md5sum": "string",
                 "overrideArtifactName": true,
                 "sha256sum": "string"
              },
              "buildBatchArn": "string",
              "buildComplete": true,
              "buildNumber": 42,
              "buildStatus": "string",
              "cache": {
                 "location": "string",
                 "modes": [ "string" ],
                 "type": "string"
              },
              "currentPhase": "string",
              "debugSession": {
                 "sessionEnabled": true,
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
                 "privilegedMode": true,
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
                    "encryptionDisabled": true,
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
                    "encryptionDisabled": true,
                    "location": "string",
                    "md5sum": "string",
                    "overrideArtifactName": true,
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
                       "fetchSubmodules": true
                    },
                    "insecureSsl": true,
                    "location": "string",
                    "reportBuildStatus": true,
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
                    "fetchSubmodules": true
                 },
                 "insecureSsl": true,
                 "location": "string",
                 "reportBuildStatus": true,
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
        }');

        $client = new MockHttpClient($response);
        $result = new StartBuildOutput(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertSame('build-arn', $result->getBuild()->getArn());
        self::assertCount(1, $result->getBuild()->getSecondaryArtifacts());
    }
}
