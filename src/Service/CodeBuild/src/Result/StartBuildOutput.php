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

class StartBuildOutput extends Result
{
    /**
     * Information about the build to be run.
     */
    private $build;

    public function getBuild(): ?Build
    {
        $this->initialize();

        return $this->build;
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->build = empty($data['build']) ? null : new Build([
            'id' => isset($data['build']['id']) ? (string) $data['build']['id'] : null,
            'arn' => isset($data['build']['arn']) ? (string) $data['build']['arn'] : null,
            'buildNumber' => isset($data['build']['buildNumber']) ? (string) $data['build']['buildNumber'] : null,
            'startTime' => (isset($data['build']['startTime']) && ($d = \DateTimeImmutable::createFromFormat('U.u', sprintf('%.6F', $data['build']['startTime'])))) ? $d : null,
            'endTime' => (isset($data['build']['endTime']) && ($d = \DateTimeImmutable::createFromFormat('U.u', sprintf('%.6F', $data['build']['endTime'])))) ? $d : null,
            'currentPhase' => isset($data['build']['currentPhase']) ? (string) $data['build']['currentPhase'] : null,
            'buildStatus' => isset($data['build']['buildStatus']) ? (string) $data['build']['buildStatus'] : null,
            'sourceVersion' => isset($data['build']['sourceVersion']) ? (string) $data['build']['sourceVersion'] : null,
            'resolvedSourceVersion' => isset($data['build']['resolvedSourceVersion']) ? (string) $data['build']['resolvedSourceVersion'] : null,
            'projectName' => isset($data['build']['projectName']) ? (string) $data['build']['projectName'] : null,
            'phases' => !isset($data['build']['phases']) ? null : $this->populateResultBuildPhases($data['build']['phases']),
            'source' => empty($data['build']['source']) ? null : new ProjectSource([
                'type' => (string) $data['build']['source']['type'],
                'location' => isset($data['build']['source']['location']) ? (string) $data['build']['source']['location'] : null,
                'gitCloneDepth' => isset($data['build']['source']['gitCloneDepth']) ? (int) $data['build']['source']['gitCloneDepth'] : null,
                'gitSubmodulesConfig' => empty($data['build']['source']['gitSubmodulesConfig']) ? null : new GitSubmodulesConfig([
                    'fetchSubmodules' => filter_var($data['build']['source']['gitSubmodulesConfig']['fetchSubmodules'], \FILTER_VALIDATE_BOOLEAN),
                ]),
                'buildspec' => isset($data['build']['source']['buildspec']) ? (string) $data['build']['source']['buildspec'] : null,
                'auth' => empty($data['build']['source']['auth']) ? null : new SourceAuth([
                    'type' => (string) $data['build']['source']['auth']['type'],
                    'resource' => isset($data['build']['source']['auth']['resource']) ? (string) $data['build']['source']['auth']['resource'] : null,
                ]),
                'reportBuildStatus' => isset($data['build']['source']['reportBuildStatus']) ? filter_var($data['build']['source']['reportBuildStatus'], \FILTER_VALIDATE_BOOLEAN) : null,
                'buildStatusConfig' => empty($data['build']['source']['buildStatusConfig']) ? null : new BuildStatusConfig([
                    'context' => isset($data['build']['source']['buildStatusConfig']['context']) ? (string) $data['build']['source']['buildStatusConfig']['context'] : null,
                    'targetUrl' => isset($data['build']['source']['buildStatusConfig']['targetUrl']) ? (string) $data['build']['source']['buildStatusConfig']['targetUrl'] : null,
                ]),
                'insecureSsl' => isset($data['build']['source']['insecureSsl']) ? filter_var($data['build']['source']['insecureSsl'], \FILTER_VALIDATE_BOOLEAN) : null,
                'sourceIdentifier' => isset($data['build']['source']['sourceIdentifier']) ? (string) $data['build']['source']['sourceIdentifier'] : null,
            ]),
            'secondarySources' => !isset($data['build']['secondarySources']) ? null : $this->populateResultProjectSources($data['build']['secondarySources']),
            'secondarySourceVersions' => !isset($data['build']['secondarySourceVersions']) ? null : $this->populateResultProjectSecondarySourceVersions($data['build']['secondarySourceVersions']),
            'artifacts' => empty($data['build']['artifacts']) ? null : new BuildArtifacts([
                'location' => isset($data['build']['artifacts']['location']) ? (string) $data['build']['artifacts']['location'] : null,
                'sha256sum' => isset($data['build']['artifacts']['sha256sum']) ? (string) $data['build']['artifacts']['sha256sum'] : null,
                'md5sum' => isset($data['build']['artifacts']['md5sum']) ? (string) $data['build']['artifacts']['md5sum'] : null,
                'overrideArtifactName' => isset($data['build']['artifacts']['overrideArtifactName']) ? filter_var($data['build']['artifacts']['overrideArtifactName'], \FILTER_VALIDATE_BOOLEAN) : null,
                'encryptionDisabled' => isset($data['build']['artifacts']['encryptionDisabled']) ? filter_var($data['build']['artifacts']['encryptionDisabled'], \FILTER_VALIDATE_BOOLEAN) : null,
                'artifactIdentifier' => isset($data['build']['artifacts']['artifactIdentifier']) ? (string) $data['build']['artifacts']['artifactIdentifier'] : null,
                'bucketOwnerAccess' => isset($data['build']['artifacts']['bucketOwnerAccess']) ? (string) $data['build']['artifacts']['bucketOwnerAccess'] : null,
            ]),
            'secondaryArtifacts' => !isset($data['build']['secondaryArtifacts']) ? null : $this->populateResultBuildArtifactsList($data['build']['secondaryArtifacts']),
            'cache' => empty($data['build']['cache']) ? null : new ProjectCache([
                'type' => (string) $data['build']['cache']['type'],
                'location' => isset($data['build']['cache']['location']) ? (string) $data['build']['cache']['location'] : null,
                'modes' => !isset($data['build']['cache']['modes']) ? null : $this->populateResultProjectCacheModes($data['build']['cache']['modes']),
            ]),
            'environment' => empty($data['build']['environment']) ? null : new ProjectEnvironment([
                'type' => (string) $data['build']['environment']['type'],
                'image' => (string) $data['build']['environment']['image'],
                'computeType' => (string) $data['build']['environment']['computeType'],
                'environmentVariables' => !isset($data['build']['environment']['environmentVariables']) ? null : $this->populateResultEnvironmentVariables($data['build']['environment']['environmentVariables']),
                'privilegedMode' => isset($data['build']['environment']['privilegedMode']) ? filter_var($data['build']['environment']['privilegedMode'], \FILTER_VALIDATE_BOOLEAN) : null,
                'certificate' => isset($data['build']['environment']['certificate']) ? (string) $data['build']['environment']['certificate'] : null,
                'registryCredential' => empty($data['build']['environment']['registryCredential']) ? null : new RegistryCredential([
                    'credential' => (string) $data['build']['environment']['registryCredential']['credential'],
                    'credentialProvider' => (string) $data['build']['environment']['registryCredential']['credentialProvider'],
                ]),
                'imagePullCredentialsType' => isset($data['build']['environment']['imagePullCredentialsType']) ? (string) $data['build']['environment']['imagePullCredentialsType'] : null,
            ]),
            'serviceRole' => isset($data['build']['serviceRole']) ? (string) $data['build']['serviceRole'] : null,
            'logs' => empty($data['build']['logs']) ? null : new LogsLocation([
                'groupName' => isset($data['build']['logs']['groupName']) ? (string) $data['build']['logs']['groupName'] : null,
                'streamName' => isset($data['build']['logs']['streamName']) ? (string) $data['build']['logs']['streamName'] : null,
                'deepLink' => isset($data['build']['logs']['deepLink']) ? (string) $data['build']['logs']['deepLink'] : null,
                's3DeepLink' => isset($data['build']['logs']['s3DeepLink']) ? (string) $data['build']['logs']['s3DeepLink'] : null,
                'cloudWatchLogsArn' => isset($data['build']['logs']['cloudWatchLogsArn']) ? (string) $data['build']['logs']['cloudWatchLogsArn'] : null,
                's3LogsArn' => isset($data['build']['logs']['s3LogsArn']) ? (string) $data['build']['logs']['s3LogsArn'] : null,
                'cloudWatchLogs' => empty($data['build']['logs']['cloudWatchLogs']) ? null : new CloudWatchLogsConfig([
                    'status' => (string) $data['build']['logs']['cloudWatchLogs']['status'],
                    'groupName' => isset($data['build']['logs']['cloudWatchLogs']['groupName']) ? (string) $data['build']['logs']['cloudWatchLogs']['groupName'] : null,
                    'streamName' => isset($data['build']['logs']['cloudWatchLogs']['streamName']) ? (string) $data['build']['logs']['cloudWatchLogs']['streamName'] : null,
                ]),
                's3Logs' => empty($data['build']['logs']['s3Logs']) ? null : new S3LogsConfig([
                    'status' => (string) $data['build']['logs']['s3Logs']['status'],
                    'location' => isset($data['build']['logs']['s3Logs']['location']) ? (string) $data['build']['logs']['s3Logs']['location'] : null,
                    'encryptionDisabled' => isset($data['build']['logs']['s3Logs']['encryptionDisabled']) ? filter_var($data['build']['logs']['s3Logs']['encryptionDisabled'], \FILTER_VALIDATE_BOOLEAN) : null,
                    'bucketOwnerAccess' => isset($data['build']['logs']['s3Logs']['bucketOwnerAccess']) ? (string) $data['build']['logs']['s3Logs']['bucketOwnerAccess'] : null,
                ]),
            ]),
            'timeoutInMinutes' => isset($data['build']['timeoutInMinutes']) ? (int) $data['build']['timeoutInMinutes'] : null,
            'queuedTimeoutInMinutes' => isset($data['build']['queuedTimeoutInMinutes']) ? (int) $data['build']['queuedTimeoutInMinutes'] : null,
            'buildComplete' => isset($data['build']['buildComplete']) ? filter_var($data['build']['buildComplete'], \FILTER_VALIDATE_BOOLEAN) : null,
            'initiator' => isset($data['build']['initiator']) ? (string) $data['build']['initiator'] : null,
            'vpcConfig' => empty($data['build']['vpcConfig']) ? null : new VpcConfig([
                'vpcId' => isset($data['build']['vpcConfig']['vpcId']) ? (string) $data['build']['vpcConfig']['vpcId'] : null,
                'subnets' => !isset($data['build']['vpcConfig']['subnets']) ? null : $this->populateResultSubnets($data['build']['vpcConfig']['subnets']),
                'securityGroupIds' => !isset($data['build']['vpcConfig']['securityGroupIds']) ? null : $this->populateResultSecurityGroupIds($data['build']['vpcConfig']['securityGroupIds']),
            ]),
            'networkInterface' => empty($data['build']['networkInterface']) ? null : new NetworkInterface([
                'subnetId' => isset($data['build']['networkInterface']['subnetId']) ? (string) $data['build']['networkInterface']['subnetId'] : null,
                'networkInterfaceId' => isset($data['build']['networkInterface']['networkInterfaceId']) ? (string) $data['build']['networkInterface']['networkInterfaceId'] : null,
            ]),
            'encryptionKey' => isset($data['build']['encryptionKey']) ? (string) $data['build']['encryptionKey'] : null,
            'exportedEnvironmentVariables' => !isset($data['build']['exportedEnvironmentVariables']) ? null : $this->populateResultExportedEnvironmentVariables($data['build']['exportedEnvironmentVariables']),
            'reportArns' => !isset($data['build']['reportArns']) ? null : $this->populateResultBuildReportArns($data['build']['reportArns']),
            'fileSystemLocations' => !isset($data['build']['fileSystemLocations']) ? null : $this->populateResultProjectFileSystemLocations($data['build']['fileSystemLocations']),
            'debugSession' => empty($data['build']['debugSession']) ? null : new DebugSession([
                'sessionEnabled' => isset($data['build']['debugSession']['sessionEnabled']) ? filter_var($data['build']['debugSession']['sessionEnabled'], \FILTER_VALIDATE_BOOLEAN) : null,
                'sessionTarget' => isset($data['build']['debugSession']['sessionTarget']) ? (string) $data['build']['debugSession']['sessionTarget'] : null,
            ]),
            'buildBatchArn' => isset($data['build']['buildBatchArn']) ? (string) $data['build']['buildBatchArn'] : null,
        ]);
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
