<?php

namespace AsyncAws\CodeBuild\Result;

use AsyncAws\CodeBuild\Enum\CacheMode;
use AsyncAws\CodeBuild\ValueObject\Build;
use AsyncAws\CodeBuild\ValueObject\BuildArtifacts;
use AsyncAws\CodeBuild\ValueObject\BuildPhase;
use AsyncAws\CodeBuild\ValueObject\BuildStatusConfig;
use AsyncAws\CodeBuild\ValueObject\CloudWatchLogsConfig;
use AsyncAws\CodeBuild\ValueObject\DebugSession;
use AsyncAws\CodeBuild\ValueObject\EnvironmentVariable;
use AsyncAws\CodeBuild\ValueObject\ExportedEnvironmentVariable;
use AsyncAws\CodeBuild\ValueObject\GitSubmodulesConfig;
use AsyncAws\CodeBuild\ValueObject\LogsLocation;
use AsyncAws\CodeBuild\ValueObject\NetworkInterface;
use AsyncAws\CodeBuild\ValueObject\PhaseContext;
use AsyncAws\CodeBuild\ValueObject\ProjectCache;
use AsyncAws\CodeBuild\ValueObject\ProjectEnvironment;
use AsyncAws\CodeBuild\ValueObject\ProjectFileSystemLocation;
use AsyncAws\CodeBuild\ValueObject\ProjectSource;
use AsyncAws\CodeBuild\ValueObject\ProjectSourceVersion;
use AsyncAws\CodeBuild\ValueObject\RegistryCredential;
use AsyncAws\CodeBuild\ValueObject\S3LogsConfig;
use AsyncAws\CodeBuild\ValueObject\SourceAuth;
use AsyncAws\CodeBuild\ValueObject\VpcConfig;
use AsyncAws\Core\Response;
use AsyncAws\Core\Result;

class BatchGetBuildsOutput extends Result
{
    /**
     * Information about the requested builds.
     */
    private $builds;

    /**
     * The IDs of builds for which information could not be found.
     */
    private $buildsNotFound;

    /**
     * @return Build[]
     */
    public function getBuilds(): array
    {
        $this->initialize();

        return $this->builds;
    }

    /**
     * @return string[]
     */
    public function getBuildsNotFound(): array
    {
        $this->initialize();

        return $this->buildsNotFound;
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->builds = empty($data['builds']) ? [] : $this->populateResultBuilds($data['builds']);
        $this->buildsNotFound = empty($data['buildsNotFound']) ? [] : $this->populateResultBuildIds($data['buildsNotFound']);
    }

    /**
     * @return BuildArtifacts[]
     */
    private function populateResultBuildArtifactsList(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = new BuildArtifacts([
                'location' => isset($item['location']) ? (string) $item['location'] : null,
                'sha256sum' => isset($item['sha256sum']) ? (string) $item['sha256sum'] : null,
                'md5sum' => isset($item['md5sum']) ? (string) $item['md5sum'] : null,
                'overrideArtifactName' => isset($item['overrideArtifactName']) ? filter_var($item['overrideArtifactName'], \FILTER_VALIDATE_BOOLEAN) : null,
                'encryptionDisabled' => isset($item['encryptionDisabled']) ? filter_var($item['encryptionDisabled'], \FILTER_VALIDATE_BOOLEAN) : null,
                'artifactIdentifier' => isset($item['artifactIdentifier']) ? (string) $item['artifactIdentifier'] : null,
                'bucketOwnerAccess' => isset($item['bucketOwnerAccess']) ? (string) $item['bucketOwnerAccess'] : null,
            ]);
        }

        return $items;
    }

    /**
     * @return string[]
     */
    private function populateResultBuildIds(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $a = isset($item) ? (string) $item : null;
            if (null !== $a) {
                $items[] = $a;
            }
        }

        return $items;
    }

    /**
     * @return BuildPhase[]
     */
    private function populateResultBuildPhases(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = new BuildPhase([
                'phaseType' => isset($item['phaseType']) ? (string) $item['phaseType'] : null,
                'phaseStatus' => isset($item['phaseStatus']) ? (string) $item['phaseStatus'] : null,
                'startTime' => (isset($item['startTime']) && ($d = \DateTimeImmutable::createFromFormat('U.u', sprintf('%.6F', $item['startTime'])))) ? $d : null,
                'endTime' => (isset($item['endTime']) && ($d = \DateTimeImmutable::createFromFormat('U.u', sprintf('%.6F', $item['endTime'])))) ? $d : null,
                'durationInSeconds' => isset($item['durationInSeconds']) ? (string) $item['durationInSeconds'] : null,
                'contexts' => !isset($item['contexts']) ? null : $this->populateResultPhaseContexts($item['contexts']),
            ]);
        }

        return $items;
    }

    /**
     * @return string[]
     */
    private function populateResultBuildReportArns(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $a = isset($item) ? (string) $item : null;
            if (null !== $a) {
                $items[] = $a;
            }
        }

        return $items;
    }

    /**
     * @return Build[]
     */
    private function populateResultBuilds(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = new Build([
                'id' => isset($item['id']) ? (string) $item['id'] : null,
                'arn' => isset($item['arn']) ? (string) $item['arn'] : null,
                'buildNumber' => isset($item['buildNumber']) ? (string) $item['buildNumber'] : null,
                'startTime' => (isset($item['startTime']) && ($d = \DateTimeImmutable::createFromFormat('U.u', sprintf('%.6F', $item['startTime'])))) ? $d : null,
                'endTime' => (isset($item['endTime']) && ($d = \DateTimeImmutable::createFromFormat('U.u', sprintf('%.6F', $item['endTime'])))) ? $d : null,
                'currentPhase' => isset($item['currentPhase']) ? (string) $item['currentPhase'] : null,
                'buildStatus' => isset($item['buildStatus']) ? (string) $item['buildStatus'] : null,
                'sourceVersion' => isset($item['sourceVersion']) ? (string) $item['sourceVersion'] : null,
                'resolvedSourceVersion' => isset($item['resolvedSourceVersion']) ? (string) $item['resolvedSourceVersion'] : null,
                'projectName' => isset($item['projectName']) ? (string) $item['projectName'] : null,
                'phases' => !isset($item['phases']) ? null : $this->populateResultBuildPhases($item['phases']),
                'source' => empty($item['source']) ? null : new ProjectSource([
                    'type' => (string) $item['source']['type'],
                    'location' => isset($item['source']['location']) ? (string) $item['source']['location'] : null,
                    'gitCloneDepth' => isset($item['source']['gitCloneDepth']) ? (int) $item['source']['gitCloneDepth'] : null,
                    'gitSubmodulesConfig' => empty($item['source']['gitSubmodulesConfig']) ? null : new GitSubmodulesConfig([
                        'fetchSubmodules' => filter_var($item['source']['gitSubmodulesConfig']['fetchSubmodules'], \FILTER_VALIDATE_BOOLEAN),
                    ]),
                    'buildspec' => isset($item['source']['buildspec']) ? (string) $item['source']['buildspec'] : null,
                    'auth' => empty($item['source']['auth']) ? null : new SourceAuth([
                        'type' => (string) $item['source']['auth']['type'],
                        'resource' => isset($item['source']['auth']['resource']) ? (string) $item['source']['auth']['resource'] : null,
                    ]),
                    'reportBuildStatus' => isset($item['source']['reportBuildStatus']) ? filter_var($item['source']['reportBuildStatus'], \FILTER_VALIDATE_BOOLEAN) : null,
                    'buildStatusConfig' => empty($item['source']['buildStatusConfig']) ? null : new BuildStatusConfig([
                        'context' => isset($item['source']['buildStatusConfig']['context']) ? (string) $item['source']['buildStatusConfig']['context'] : null,
                        'targetUrl' => isset($item['source']['buildStatusConfig']['targetUrl']) ? (string) $item['source']['buildStatusConfig']['targetUrl'] : null,
                    ]),
                    'insecureSsl' => isset($item['source']['insecureSsl']) ? filter_var($item['source']['insecureSsl'], \FILTER_VALIDATE_BOOLEAN) : null,
                    'sourceIdentifier' => isset($item['source']['sourceIdentifier']) ? (string) $item['source']['sourceIdentifier'] : null,
                ]),
                'secondarySources' => !isset($item['secondarySources']) ? null : $this->populateResultProjectSources($item['secondarySources']),
                'secondarySourceVersions' => !isset($item['secondarySourceVersions']) ? null : $this->populateResultProjectSecondarySourceVersions($item['secondarySourceVersions']),
                'artifacts' => empty($item['artifacts']) ? null : new BuildArtifacts([
                    'location' => isset($item['artifacts']['location']) ? (string) $item['artifacts']['location'] : null,
                    'sha256sum' => isset($item['artifacts']['sha256sum']) ? (string) $item['artifacts']['sha256sum'] : null,
                    'md5sum' => isset($item['artifacts']['md5sum']) ? (string) $item['artifacts']['md5sum'] : null,
                    'overrideArtifactName' => isset($item['artifacts']['overrideArtifactName']) ? filter_var($item['artifacts']['overrideArtifactName'], \FILTER_VALIDATE_BOOLEAN) : null,
                    'encryptionDisabled' => isset($item['artifacts']['encryptionDisabled']) ? filter_var($item['artifacts']['encryptionDisabled'], \FILTER_VALIDATE_BOOLEAN) : null,
                    'artifactIdentifier' => isset($item['artifacts']['artifactIdentifier']) ? (string) $item['artifacts']['artifactIdentifier'] : null,
                    'bucketOwnerAccess' => isset($item['artifacts']['bucketOwnerAccess']) ? (string) $item['artifacts']['bucketOwnerAccess'] : null,
                ]),
                'secondaryArtifacts' => !isset($item['secondaryArtifacts']) ? null : $this->populateResultBuildArtifactsList($item['secondaryArtifacts']),
                'cache' => empty($item['cache']) ? null : new ProjectCache([
                    'type' => (string) $item['cache']['type'],
                    'location' => isset($item['cache']['location']) ? (string) $item['cache']['location'] : null,
                    'modes' => !isset($item['cache']['modes']) ? null : $this->populateResultProjectCacheModes($item['cache']['modes']),
                ]),
                'environment' => empty($item['environment']) ? null : new ProjectEnvironment([
                    'type' => (string) $item['environment']['type'],
                    'image' => (string) $item['environment']['image'],
                    'computeType' => (string) $item['environment']['computeType'],
                    'environmentVariables' => !isset($item['environment']['environmentVariables']) ? null : $this->populateResultEnvironmentVariables($item['environment']['environmentVariables']),
                    'privilegedMode' => isset($item['environment']['privilegedMode']) ? filter_var($item['environment']['privilegedMode'], \FILTER_VALIDATE_BOOLEAN) : null,
                    'certificate' => isset($item['environment']['certificate']) ? (string) $item['environment']['certificate'] : null,
                    'registryCredential' => empty($item['environment']['registryCredential']) ? null : new RegistryCredential([
                        'credential' => (string) $item['environment']['registryCredential']['credential'],
                        'credentialProvider' => (string) $item['environment']['registryCredential']['credentialProvider'],
                    ]),
                    'imagePullCredentialsType' => isset($item['environment']['imagePullCredentialsType']) ? (string) $item['environment']['imagePullCredentialsType'] : null,
                ]),
                'serviceRole' => isset($item['serviceRole']) ? (string) $item['serviceRole'] : null,
                'logs' => empty($item['logs']) ? null : new LogsLocation([
                    'groupName' => isset($item['logs']['groupName']) ? (string) $item['logs']['groupName'] : null,
                    'streamName' => isset($item['logs']['streamName']) ? (string) $item['logs']['streamName'] : null,
                    'deepLink' => isset($item['logs']['deepLink']) ? (string) $item['logs']['deepLink'] : null,
                    's3DeepLink' => isset($item['logs']['s3DeepLink']) ? (string) $item['logs']['s3DeepLink'] : null,
                    'cloudWatchLogsArn' => isset($item['logs']['cloudWatchLogsArn']) ? (string) $item['logs']['cloudWatchLogsArn'] : null,
                    's3LogsArn' => isset($item['logs']['s3LogsArn']) ? (string) $item['logs']['s3LogsArn'] : null,
                    'cloudWatchLogs' => empty($item['logs']['cloudWatchLogs']) ? null : new CloudWatchLogsConfig([
                        'status' => (string) $item['logs']['cloudWatchLogs']['status'],
                        'groupName' => isset($item['logs']['cloudWatchLogs']['groupName']) ? (string) $item['logs']['cloudWatchLogs']['groupName'] : null,
                        'streamName' => isset($item['logs']['cloudWatchLogs']['streamName']) ? (string) $item['logs']['cloudWatchLogs']['streamName'] : null,
                    ]),
                    's3Logs' => empty($item['logs']['s3Logs']) ? null : new S3LogsConfig([
                        'status' => (string) $item['logs']['s3Logs']['status'],
                        'location' => isset($item['logs']['s3Logs']['location']) ? (string) $item['logs']['s3Logs']['location'] : null,
                        'encryptionDisabled' => isset($item['logs']['s3Logs']['encryptionDisabled']) ? filter_var($item['logs']['s3Logs']['encryptionDisabled'], \FILTER_VALIDATE_BOOLEAN) : null,
                        'bucketOwnerAccess' => isset($item['logs']['s3Logs']['bucketOwnerAccess']) ? (string) $item['logs']['s3Logs']['bucketOwnerAccess'] : null,
                    ]),
                ]),
                'timeoutInMinutes' => isset($item['timeoutInMinutes']) ? (int) $item['timeoutInMinutes'] : null,
                'queuedTimeoutInMinutes' => isset($item['queuedTimeoutInMinutes']) ? (int) $item['queuedTimeoutInMinutes'] : null,
                'buildComplete' => isset($item['buildComplete']) ? filter_var($item['buildComplete'], \FILTER_VALIDATE_BOOLEAN) : null,
                'initiator' => isset($item['initiator']) ? (string) $item['initiator'] : null,
                'vpcConfig' => empty($item['vpcConfig']) ? null : new VpcConfig([
                    'vpcId' => isset($item['vpcConfig']['vpcId']) ? (string) $item['vpcConfig']['vpcId'] : null,
                    'subnets' => !isset($item['vpcConfig']['subnets']) ? null : $this->populateResultSubnets($item['vpcConfig']['subnets']),
                    'securityGroupIds' => !isset($item['vpcConfig']['securityGroupIds']) ? null : $this->populateResultSecurityGroupIds($item['vpcConfig']['securityGroupIds']),
                ]),
                'networkInterface' => empty($item['networkInterface']) ? null : new NetworkInterface([
                    'subnetId' => isset($item['networkInterface']['subnetId']) ? (string) $item['networkInterface']['subnetId'] : null,
                    'networkInterfaceId' => isset($item['networkInterface']['networkInterfaceId']) ? (string) $item['networkInterface']['networkInterfaceId'] : null,
                ]),
                'encryptionKey' => isset($item['encryptionKey']) ? (string) $item['encryptionKey'] : null,
                'exportedEnvironmentVariables' => !isset($item['exportedEnvironmentVariables']) ? null : $this->populateResultExportedEnvironmentVariables($item['exportedEnvironmentVariables']),
                'reportArns' => !isset($item['reportArns']) ? null : $this->populateResultBuildReportArns($item['reportArns']),
                'fileSystemLocations' => !isset($item['fileSystemLocations']) ? null : $this->populateResultProjectFileSystemLocations($item['fileSystemLocations']),
                'debugSession' => empty($item['debugSession']) ? null : new DebugSession([
                    'sessionEnabled' => isset($item['debugSession']['sessionEnabled']) ? filter_var($item['debugSession']['sessionEnabled'], \FILTER_VALIDATE_BOOLEAN) : null,
                    'sessionTarget' => isset($item['debugSession']['sessionTarget']) ? (string) $item['debugSession']['sessionTarget'] : null,
                ]),
                'buildBatchArn' => isset($item['buildBatchArn']) ? (string) $item['buildBatchArn'] : null,
            ]);
        }

        return $items;
    }

    /**
     * @return EnvironmentVariable[]
     */
    private function populateResultEnvironmentVariables(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = new EnvironmentVariable([
                'name' => (string) $item['name'],
                'value' => (string) $item['value'],
                'type' => isset($item['type']) ? (string) $item['type'] : null,
            ]);
        }

        return $items;
    }

    /**
     * @return ExportedEnvironmentVariable[]
     */
    private function populateResultExportedEnvironmentVariables(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = new ExportedEnvironmentVariable([
                'name' => isset($item['name']) ? (string) $item['name'] : null,
                'value' => isset($item['value']) ? (string) $item['value'] : null,
            ]);
        }

        return $items;
    }

    /**
     * @return PhaseContext[]
     */
    private function populateResultPhaseContexts(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = new PhaseContext([
                'statusCode' => isset($item['statusCode']) ? (string) $item['statusCode'] : null,
                'message' => isset($item['message']) ? (string) $item['message'] : null,
            ]);
        }

        return $items;
    }

    /**
     * @return list<CacheMode::*>
     */
    private function populateResultProjectCacheModes(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $a = isset($item) ? (string) $item : null;
            if (null !== $a) {
                $items[] = $a;
            }
        }

        return $items;
    }

    /**
     * @return ProjectFileSystemLocation[]
     */
    private function populateResultProjectFileSystemLocations(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = new ProjectFileSystemLocation([
                'type' => isset($item['type']) ? (string) $item['type'] : null,
                'location' => isset($item['location']) ? (string) $item['location'] : null,
                'mountPoint' => isset($item['mountPoint']) ? (string) $item['mountPoint'] : null,
                'identifier' => isset($item['identifier']) ? (string) $item['identifier'] : null,
                'mountOptions' => isset($item['mountOptions']) ? (string) $item['mountOptions'] : null,
            ]);
        }

        return $items;
    }

    /**
     * @return ProjectSourceVersion[]
     */
    private function populateResultProjectSecondarySourceVersions(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = new ProjectSourceVersion([
                'sourceIdentifier' => (string) $item['sourceIdentifier'],
                'sourceVersion' => (string) $item['sourceVersion'],
            ]);
        }

        return $items;
    }

    /**
     * @return ProjectSource[]
     */
    private function populateResultProjectSources(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = new ProjectSource([
                'type' => (string) $item['type'],
                'location' => isset($item['location']) ? (string) $item['location'] : null,
                'gitCloneDepth' => isset($item['gitCloneDepth']) ? (int) $item['gitCloneDepth'] : null,
                'gitSubmodulesConfig' => empty($item['gitSubmodulesConfig']) ? null : new GitSubmodulesConfig([
                    'fetchSubmodules' => filter_var($item['gitSubmodulesConfig']['fetchSubmodules'], \FILTER_VALIDATE_BOOLEAN),
                ]),
                'buildspec' => isset($item['buildspec']) ? (string) $item['buildspec'] : null,
                'auth' => empty($item['auth']) ? null : new SourceAuth([
                    'type' => (string) $item['auth']['type'],
                    'resource' => isset($item['auth']['resource']) ? (string) $item['auth']['resource'] : null,
                ]),
                'reportBuildStatus' => isset($item['reportBuildStatus']) ? filter_var($item['reportBuildStatus'], \FILTER_VALIDATE_BOOLEAN) : null,
                'buildStatusConfig' => empty($item['buildStatusConfig']) ? null : new BuildStatusConfig([
                    'context' => isset($item['buildStatusConfig']['context']) ? (string) $item['buildStatusConfig']['context'] : null,
                    'targetUrl' => isset($item['buildStatusConfig']['targetUrl']) ? (string) $item['buildStatusConfig']['targetUrl'] : null,
                ]),
                'insecureSsl' => isset($item['insecureSsl']) ? filter_var($item['insecureSsl'], \FILTER_VALIDATE_BOOLEAN) : null,
                'sourceIdentifier' => isset($item['sourceIdentifier']) ? (string) $item['sourceIdentifier'] : null,
            ]);
        }

        return $items;
    }

    /**
     * @return string[]
     */
    private function populateResultSecurityGroupIds(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $a = isset($item) ? (string) $item : null;
            if (null !== $a) {
                $items[] = $a;
            }
        }

        return $items;
    }

    /**
     * @return string[]
     */
    private function populateResultSubnets(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $a = isset($item) ? (string) $item : null;
            if (null !== $a) {
                $items[] = $a;
            }
        }

        return $items;
    }
}
