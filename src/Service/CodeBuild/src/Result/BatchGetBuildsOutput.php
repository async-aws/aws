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

    private function populateResultBuild(array $json): Build
    {
        return new Build([
            'id' => isset($json['id']) ? (string) $json['id'] : null,
            'arn' => isset($json['arn']) ? (string) $json['arn'] : null,
            'buildNumber' => isset($json['buildNumber']) ? (int) $json['buildNumber'] : null,
            'startTime' => (isset($json['startTime']) && ($d = \DateTimeImmutable::createFromFormat('U.u', sprintf('%.6F', $json['startTime'])))) ? $d : null,
            'endTime' => (isset($json['endTime']) && ($d = \DateTimeImmutable::createFromFormat('U.u', sprintf('%.6F', $json['endTime'])))) ? $d : null,
            'currentPhase' => isset($json['currentPhase']) ? (string) $json['currentPhase'] : null,
            'buildStatus' => isset($json['buildStatus']) ? (string) $json['buildStatus'] : null,
            'sourceVersion' => isset($json['sourceVersion']) ? (string) $json['sourceVersion'] : null,
            'resolvedSourceVersion' => isset($json['resolvedSourceVersion']) ? (string) $json['resolvedSourceVersion'] : null,
            'projectName' => isset($json['projectName']) ? (string) $json['projectName'] : null,
            'phases' => !isset($json['phases']) ? null : $this->populateResultBuildPhases($json['phases']),
            'source' => empty($json['source']) ? null : $this->populateResultProjectSource($json['source']),
            'secondarySources' => !isset($json['secondarySources']) ? null : $this->populateResultProjectSources($json['secondarySources']),
            'secondarySourceVersions' => !isset($json['secondarySourceVersions']) ? null : $this->populateResultProjectSecondarySourceVersions($json['secondarySourceVersions']),
            'artifacts' => empty($json['artifacts']) ? null : $this->populateResultBuildArtifacts($json['artifacts']),
            'secondaryArtifacts' => !isset($json['secondaryArtifacts']) ? null : $this->populateResultBuildArtifactsList($json['secondaryArtifacts']),
            'cache' => empty($json['cache']) ? null : $this->populateResultProjectCache($json['cache']),
            'environment' => empty($json['environment']) ? null : $this->populateResultProjectEnvironment($json['environment']),
            'serviceRole' => isset($json['serviceRole']) ? (string) $json['serviceRole'] : null,
            'logs' => empty($json['logs']) ? null : $this->populateResultLogsLocation($json['logs']),
            'timeoutInMinutes' => isset($json['timeoutInMinutes']) ? (int) $json['timeoutInMinutes'] : null,
            'queuedTimeoutInMinutes' => isset($json['queuedTimeoutInMinutes']) ? (int) $json['queuedTimeoutInMinutes'] : null,
            'buildComplete' => isset($json['buildComplete']) ? filter_var($json['buildComplete'], \FILTER_VALIDATE_BOOLEAN) : null,
            'initiator' => isset($json['initiator']) ? (string) $json['initiator'] : null,
            'vpcConfig' => empty($json['vpcConfig']) ? null : $this->populateResultVpcConfig($json['vpcConfig']),
            'networkInterface' => empty($json['networkInterface']) ? null : $this->populateResultNetworkInterface($json['networkInterface']),
            'encryptionKey' => isset($json['encryptionKey']) ? (string) $json['encryptionKey'] : null,
            'exportedEnvironmentVariables' => !isset($json['exportedEnvironmentVariables']) ? null : $this->populateResultExportedEnvironmentVariables($json['exportedEnvironmentVariables']),
            'reportArns' => !isset($json['reportArns']) ? null : $this->populateResultBuildReportArns($json['reportArns']),
            'fileSystemLocations' => !isset($json['fileSystemLocations']) ? null : $this->populateResultProjectFileSystemLocations($json['fileSystemLocations']),
            'debugSession' => empty($json['debugSession']) ? null : $this->populateResultDebugSession($json['debugSession']),
            'buildBatchArn' => isset($json['buildBatchArn']) ? (string) $json['buildBatchArn'] : null,
        ]);
    }

    private function populateResultBuildArtifacts(array $json): BuildArtifacts
    {
        return new BuildArtifacts([
            'location' => isset($json['location']) ? (string) $json['location'] : null,
            'sha256sum' => isset($json['sha256sum']) ? (string) $json['sha256sum'] : null,
            'md5sum' => isset($json['md5sum']) ? (string) $json['md5sum'] : null,
            'overrideArtifactName' => isset($json['overrideArtifactName']) ? filter_var($json['overrideArtifactName'], \FILTER_VALIDATE_BOOLEAN) : null,
            'encryptionDisabled' => isset($json['encryptionDisabled']) ? filter_var($json['encryptionDisabled'], \FILTER_VALIDATE_BOOLEAN) : null,
            'artifactIdentifier' => isset($json['artifactIdentifier']) ? (string) $json['artifactIdentifier'] : null,
            'bucketOwnerAccess' => isset($json['bucketOwnerAccess']) ? (string) $json['bucketOwnerAccess'] : null,
        ]);
    }

    /**
     * @return BuildArtifacts[]
     */
    private function populateResultBuildArtifactsList(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = $this->populateResultBuildArtifacts($item);
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

    private function populateResultBuildPhase(array $json): BuildPhase
    {
        return new BuildPhase([
            'phaseType' => isset($json['phaseType']) ? (string) $json['phaseType'] : null,
            'phaseStatus' => isset($json['phaseStatus']) ? (string) $json['phaseStatus'] : null,
            'startTime' => (isset($json['startTime']) && ($d = \DateTimeImmutable::createFromFormat('U.u', sprintf('%.6F', $json['startTime'])))) ? $d : null,
            'endTime' => (isset($json['endTime']) && ($d = \DateTimeImmutable::createFromFormat('U.u', sprintf('%.6F', $json['endTime'])))) ? $d : null,
            'durationInSeconds' => isset($json['durationInSeconds']) ? (int) $json['durationInSeconds'] : null,
            'contexts' => !isset($json['contexts']) ? null : $this->populateResultPhaseContexts($json['contexts']),
        ]);
    }

    /**
     * @return BuildPhase[]
     */
    private function populateResultBuildPhases(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = $this->populateResultBuildPhase($item);
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

    private function populateResultBuildStatusConfig(array $json): BuildStatusConfig
    {
        return new BuildStatusConfig([
            'context' => isset($json['context']) ? (string) $json['context'] : null,
            'targetUrl' => isset($json['targetUrl']) ? (string) $json['targetUrl'] : null,
        ]);
    }

    /**
     * @return Build[]
     */
    private function populateResultBuilds(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = $this->populateResultBuild($item);
        }

        return $items;
    }

    private function populateResultCloudWatchLogsConfig(array $json): CloudWatchLogsConfig
    {
        return new CloudWatchLogsConfig([
            'status' => (string) $json['status'],
            'groupName' => isset($json['groupName']) ? (string) $json['groupName'] : null,
            'streamName' => isset($json['streamName']) ? (string) $json['streamName'] : null,
        ]);
    }

    private function populateResultDebugSession(array $json): DebugSession
    {
        return new DebugSession([
            'sessionEnabled' => isset($json['sessionEnabled']) ? filter_var($json['sessionEnabled'], \FILTER_VALIDATE_BOOLEAN) : null,
            'sessionTarget' => isset($json['sessionTarget']) ? (string) $json['sessionTarget'] : null,
        ]);
    }

    private function populateResultEnvironmentVariable(array $json): EnvironmentVariable
    {
        return new EnvironmentVariable([
            'name' => (string) $json['name'],
            'value' => (string) $json['value'],
            'type' => isset($json['type']) ? (string) $json['type'] : null,
        ]);
    }

    /**
     * @return EnvironmentVariable[]
     */
    private function populateResultEnvironmentVariables(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = $this->populateResultEnvironmentVariable($item);
        }

        return $items;
    }

    private function populateResultExportedEnvironmentVariable(array $json): ExportedEnvironmentVariable
    {
        return new ExportedEnvironmentVariable([
            'name' => isset($json['name']) ? (string) $json['name'] : null,
            'value' => isset($json['value']) ? (string) $json['value'] : null,
        ]);
    }

    /**
     * @return ExportedEnvironmentVariable[]
     */
    private function populateResultExportedEnvironmentVariables(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = $this->populateResultExportedEnvironmentVariable($item);
        }

        return $items;
    }

    private function populateResultGitSubmodulesConfig(array $json): GitSubmodulesConfig
    {
        return new GitSubmodulesConfig([
            'fetchSubmodules' => filter_var($json['fetchSubmodules'], \FILTER_VALIDATE_BOOLEAN),
        ]);
    }

    private function populateResultLogsLocation(array $json): LogsLocation
    {
        return new LogsLocation([
            'groupName' => isset($json['groupName']) ? (string) $json['groupName'] : null,
            'streamName' => isset($json['streamName']) ? (string) $json['streamName'] : null,
            'deepLink' => isset($json['deepLink']) ? (string) $json['deepLink'] : null,
            's3DeepLink' => isset($json['s3DeepLink']) ? (string) $json['s3DeepLink'] : null,
            'cloudWatchLogsArn' => isset($json['cloudWatchLogsArn']) ? (string) $json['cloudWatchLogsArn'] : null,
            's3LogsArn' => isset($json['s3LogsArn']) ? (string) $json['s3LogsArn'] : null,
            'cloudWatchLogs' => empty($json['cloudWatchLogs']) ? null : $this->populateResultCloudWatchLogsConfig($json['cloudWatchLogs']),
            's3Logs' => empty($json['s3Logs']) ? null : $this->populateResultS3LogsConfig($json['s3Logs']),
        ]);
    }

    private function populateResultNetworkInterface(array $json): NetworkInterface
    {
        return new NetworkInterface([
            'subnetId' => isset($json['subnetId']) ? (string) $json['subnetId'] : null,
            'networkInterfaceId' => isset($json['networkInterfaceId']) ? (string) $json['networkInterfaceId'] : null,
        ]);
    }

    private function populateResultPhaseContext(array $json): PhaseContext
    {
        return new PhaseContext([
            'statusCode' => isset($json['statusCode']) ? (string) $json['statusCode'] : null,
            'message' => isset($json['message']) ? (string) $json['message'] : null,
        ]);
    }

    /**
     * @return PhaseContext[]
     */
    private function populateResultPhaseContexts(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = $this->populateResultPhaseContext($item);
        }

        return $items;
    }

    private function populateResultProjectCache(array $json): ProjectCache
    {
        return new ProjectCache([
            'type' => (string) $json['type'],
            'location' => isset($json['location']) ? (string) $json['location'] : null,
            'modes' => !isset($json['modes']) ? null : $this->populateResultProjectCacheModes($json['modes']),
        ]);
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

    private function populateResultProjectEnvironment(array $json): ProjectEnvironment
    {
        return new ProjectEnvironment([
            'type' => (string) $json['type'],
            'image' => (string) $json['image'],
            'computeType' => (string) $json['computeType'],
            'environmentVariables' => !isset($json['environmentVariables']) ? null : $this->populateResultEnvironmentVariables($json['environmentVariables']),
            'privilegedMode' => isset($json['privilegedMode']) ? filter_var($json['privilegedMode'], \FILTER_VALIDATE_BOOLEAN) : null,
            'certificate' => isset($json['certificate']) ? (string) $json['certificate'] : null,
            'registryCredential' => empty($json['registryCredential']) ? null : $this->populateResultRegistryCredential($json['registryCredential']),
            'imagePullCredentialsType' => isset($json['imagePullCredentialsType']) ? (string) $json['imagePullCredentialsType'] : null,
        ]);
    }

    private function populateResultProjectFileSystemLocation(array $json): ProjectFileSystemLocation
    {
        return new ProjectFileSystemLocation([
            'type' => isset($json['type']) ? (string) $json['type'] : null,
            'location' => isset($json['location']) ? (string) $json['location'] : null,
            'mountPoint' => isset($json['mountPoint']) ? (string) $json['mountPoint'] : null,
            'identifier' => isset($json['identifier']) ? (string) $json['identifier'] : null,
            'mountOptions' => isset($json['mountOptions']) ? (string) $json['mountOptions'] : null,
        ]);
    }

    /**
     * @return ProjectFileSystemLocation[]
     */
    private function populateResultProjectFileSystemLocations(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = $this->populateResultProjectFileSystemLocation($item);
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
            $items[] = $this->populateResultProjectSourceVersion($item);
        }

        return $items;
    }

    private function populateResultProjectSource(array $json): ProjectSource
    {
        return new ProjectSource([
            'type' => (string) $json['type'],
            'location' => isset($json['location']) ? (string) $json['location'] : null,
            'gitCloneDepth' => isset($json['gitCloneDepth']) ? (int) $json['gitCloneDepth'] : null,
            'gitSubmodulesConfig' => empty($json['gitSubmodulesConfig']) ? null : $this->populateResultGitSubmodulesConfig($json['gitSubmodulesConfig']),
            'buildspec' => isset($json['buildspec']) ? (string) $json['buildspec'] : null,
            'auth' => empty($json['auth']) ? null : $this->populateResultSourceAuth($json['auth']),
            'reportBuildStatus' => isset($json['reportBuildStatus']) ? filter_var($json['reportBuildStatus'], \FILTER_VALIDATE_BOOLEAN) : null,
            'buildStatusConfig' => empty($json['buildStatusConfig']) ? null : $this->populateResultBuildStatusConfig($json['buildStatusConfig']),
            'insecureSsl' => isset($json['insecureSsl']) ? filter_var($json['insecureSsl'], \FILTER_VALIDATE_BOOLEAN) : null,
            'sourceIdentifier' => isset($json['sourceIdentifier']) ? (string) $json['sourceIdentifier'] : null,
        ]);
    }

    private function populateResultProjectSourceVersion(array $json): ProjectSourceVersion
    {
        return new ProjectSourceVersion([
            'sourceIdentifier' => (string) $json['sourceIdentifier'],
            'sourceVersion' => (string) $json['sourceVersion'],
        ]);
    }

    /**
     * @return ProjectSource[]
     */
    private function populateResultProjectSources(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = $this->populateResultProjectSource($item);
        }

        return $items;
    }

    private function populateResultRegistryCredential(array $json): RegistryCredential
    {
        return new RegistryCredential([
            'credential' => (string) $json['credential'],
            'credentialProvider' => (string) $json['credentialProvider'],
        ]);
    }

    private function populateResultS3LogsConfig(array $json): S3LogsConfig
    {
        return new S3LogsConfig([
            'status' => (string) $json['status'],
            'location' => isset($json['location']) ? (string) $json['location'] : null,
            'encryptionDisabled' => isset($json['encryptionDisabled']) ? filter_var($json['encryptionDisabled'], \FILTER_VALIDATE_BOOLEAN) : null,
            'bucketOwnerAccess' => isset($json['bucketOwnerAccess']) ? (string) $json['bucketOwnerAccess'] : null,
        ]);
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

    private function populateResultSourceAuth(array $json): SourceAuth
    {
        return new SourceAuth([
            'type' => (string) $json['type'],
            'resource' => isset($json['resource']) ? (string) $json['resource'] : null,
        ]);
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

    private function populateResultVpcConfig(array $json): VpcConfig
    {
        return new VpcConfig([
            'vpcId' => isset($json['vpcId']) ? (string) $json['vpcId'] : null,
            'subnets' => !isset($json['subnets']) ? null : $this->populateResultSubnets($json['subnets']),
            'securityGroupIds' => !isset($json['securityGroupIds']) ? null : $this->populateResultSecurityGroupIds($json['securityGroupIds']),
        ]);
    }
}
