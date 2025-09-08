<?php

namespace AsyncAws\CodeDeploy\Input;

use AsyncAws\CodeDeploy\Enum\FileExistsBehavior;
use AsyncAws\CodeDeploy\ValueObject\Alarm;
use AsyncAws\CodeDeploy\ValueObject\AlarmConfiguration;
use AsyncAws\CodeDeploy\ValueObject\AutoRollbackConfiguration;
use AsyncAws\CodeDeploy\ValueObject\RevisionLocation;
use AsyncAws\CodeDeploy\ValueObject\TargetInstances;
use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

/**
 * Represents the input of a `CreateDeployment` operation.
 */
final class CreateDeploymentInput extends Input
{
    /**
     * The name of an CodeDeploy application associated with the user or Amazon Web Services account.
     *
     * @required
     *
     * @var string|null
     */
    private $applicationName;

    /**
     * The name of the deployment group.
     *
     * @var string|null
     */
    private $deploymentGroupName;

    /**
     * The type and location of the revision to deploy.
     *
     * @var RevisionLocation|null
     */
    private $revision;

    /**
     * The name of a deployment configuration associated with the user or Amazon Web Services account.
     *
     * If not specified, the value configured in the deployment group is used as the default. If the deployment group does
     * not have a deployment configuration associated with it, `CodeDeployDefault`.`OneAtATime` is used by default.
     *
     * @var string|null
     */
    private $deploymentConfigName;

    /**
     * A comment about the deployment.
     *
     * @var string|null
     */
    private $description;

    /**
     * If true, then if an `ApplicationStop`, `BeforeBlockTraffic`, or `AfterBlockTraffic` deployment lifecycle event to an
     * instance fails, then the deployment continues to the next deployment lifecycle event. For example, if
     * `ApplicationStop` fails, the deployment continues with `DownloadBundle`. If `BeforeBlockTraffic` fails, the
     * deployment continues with `BlockTraffic`. If `AfterBlockTraffic` fails, the deployment continues with
     * `ApplicationStop`.
     *
     * If false or not specified, then if a lifecycle event fails during a deployment to an instance, that deployment fails.
     * If deployment to that instance is part of an overall deployment and the number of healthy hosts is not less than the
     * minimum number of healthy hosts, then a deployment to the next instance is attempted.
     *
     * During a deployment, the CodeDeploy agent runs the scripts specified for `ApplicationStop`, `BeforeBlockTraffic`, and
     * `AfterBlockTraffic` in the AppSpec file from the previous successful deployment. (All other scripts are run from the
     * AppSpec file in the current deployment.) If one of these scripts contains an error and does not run successfully, the
     * deployment can fail.
     *
     * If the cause of the failure is a script from the last successful deployment that will never run successfully, create
     * a new deployment and use `ignoreApplicationStopFailures` to specify that the `ApplicationStop`, `BeforeBlockTraffic`,
     * and `AfterBlockTraffic` failures should be ignored.
     *
     * @var bool|null
     */
    private $ignoreApplicationStopFailures;

    /**
     * Information about the instances that belong to the replacement environment in a blue/green deployment.
     *
     * @var TargetInstances|null
     */
    private $targetInstances;

    /**
     * Configuration information for an automatic rollback that is added when a deployment is created.
     *
     * @var AutoRollbackConfiguration|null
     */
    private $autoRollbackConfiguration;

    /**
     * Indicates whether to deploy to all instances or only to instances that are not running the latest application
     * revision.
     *
     * @var bool|null
     */
    private $updateOutdatedInstancesOnly;

    /**
     * Information about how CodeDeploy handles files that already exist in a deployment target location but weren't part of
     * the previous successful deployment.
     *
     * The `fileExistsBehavior` parameter takes any of the following values:
     *
     * - DISALLOW: The deployment fails. This is also the default behavior if no option is specified.
     * - OVERWRITE: The version of the file from the application revision currently being deployed replaces the version
     *   already on the instance.
     * - RETAIN: The version of the file already on the instance is kept and used as part of the new deployment.
     *
     * @var FileExistsBehavior::*|null
     */
    private $fileExistsBehavior;

    /**
     * Allows you to specify information about alarms associated with a deployment. The alarm configuration that you specify
     * here will override the alarm configuration at the deployment group level. Consider overriding the alarm configuration
     * if you have set up alarms at the deployment group level that are causing deployment failures. In this case, you would
     * call `CreateDeployment` to create a new deployment that uses a previous application revision that is known to work,
     * and set its alarm configuration to turn off alarm polling. Turning off alarm polling ensures that the new deployment
     * proceeds without being blocked by the alarm that was generated by the previous, failed, deployment.
     *
     * > If you specify an `overrideAlarmConfiguration`, you need the `UpdateDeploymentGroup` IAM permission when calling
     * > `CreateDeployment`.
     *
     * @var AlarmConfiguration|null
     */
    private $overrideAlarmConfiguration;

    /**
     * @param array{
     *   applicationName?: string,
     *   deploymentGroupName?: string|null,
     *   revision?: RevisionLocation|array|null,
     *   deploymentConfigName?: string|null,
     *   description?: string|null,
     *   ignoreApplicationStopFailures?: bool|null,
     *   targetInstances?: TargetInstances|array|null,
     *   autoRollbackConfiguration?: AutoRollbackConfiguration|array|null,
     *   updateOutdatedInstancesOnly?: bool|null,
     *   fileExistsBehavior?: FileExistsBehavior::*|null,
     *   overrideAlarmConfiguration?: AlarmConfiguration|array|null,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->applicationName = $input['applicationName'] ?? null;
        $this->deploymentGroupName = $input['deploymentGroupName'] ?? null;
        $this->revision = isset($input['revision']) ? RevisionLocation::create($input['revision']) : null;
        $this->deploymentConfigName = $input['deploymentConfigName'] ?? null;
        $this->description = $input['description'] ?? null;
        $this->ignoreApplicationStopFailures = $input['ignoreApplicationStopFailures'] ?? null;
        $this->targetInstances = isset($input['targetInstances']) ? TargetInstances::create($input['targetInstances']) : null;
        $this->autoRollbackConfiguration = isset($input['autoRollbackConfiguration']) ? AutoRollbackConfiguration::create($input['autoRollbackConfiguration']) : null;
        $this->updateOutdatedInstancesOnly = $input['updateOutdatedInstancesOnly'] ?? null;
        $this->fileExistsBehavior = $input['fileExistsBehavior'] ?? null;
        $this->overrideAlarmConfiguration = isset($input['overrideAlarmConfiguration']) ? AlarmConfiguration::create($input['overrideAlarmConfiguration']) : null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   applicationName?: string,
     *   deploymentGroupName?: string|null,
     *   revision?: RevisionLocation|array|null,
     *   deploymentConfigName?: string|null,
     *   description?: string|null,
     *   ignoreApplicationStopFailures?: bool|null,
     *   targetInstances?: TargetInstances|array|null,
     *   autoRollbackConfiguration?: AutoRollbackConfiguration|array|null,
     *   updateOutdatedInstancesOnly?: bool|null,
     *   fileExistsBehavior?: FileExistsBehavior::*|null,
     *   overrideAlarmConfiguration?: AlarmConfiguration|array|null,
     *   '@region'?: string|null,
     * }|CreateDeploymentInput $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getApplicationName(): ?string
    {
        return $this->applicationName;
    }

    public function getAutoRollbackConfiguration(): ?AutoRollbackConfiguration
    {
        return $this->autoRollbackConfiguration;
    }

    public function getDeploymentConfigName(): ?string
    {
        return $this->deploymentConfigName;
    }

    public function getDeploymentGroupName(): ?string
    {
        return $this->deploymentGroupName;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @return FileExistsBehavior::*|null
     */
    public function getFileExistsBehavior(): ?string
    {
        return $this->fileExistsBehavior;
    }

    public function getIgnoreApplicationStopFailures(): ?bool
    {
        return $this->ignoreApplicationStopFailures;
    }

    public function getOverrideAlarmConfiguration(): ?AlarmConfiguration
    {
        return $this->overrideAlarmConfiguration;
    }

    public function getRevision(): ?RevisionLocation
    {
        return $this->revision;
    }

    public function getTargetInstances(): ?TargetInstances
    {
        return $this->targetInstances;
    }

    public function getUpdateOutdatedInstancesOnly(): ?bool
    {
        return $this->updateOutdatedInstancesOnly;
    }

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = [
            'Content-Type' => 'application/x-amz-json-1.1',
            'X-Amz-Target' => 'CodeDeploy_20141006.CreateDeployment',
            'Accept' => 'application/json',
        ];

        // Prepare query
        $query = [];

        // Prepare URI
        $uriString = '/';

        // Prepare Body
        $bodyPayload = $this->requestBody();
        $body = empty($bodyPayload) ? '{}' : json_encode($bodyPayload, 4194304);

        // Return the Request
        return new Request('POST', $uriString, $query, $headers, StreamFactory::create($body));
    }

    public function setApplicationName(?string $value): self
    {
        $this->applicationName = $value;

        return $this;
    }

    public function setAutoRollbackConfiguration(?AutoRollbackConfiguration $value): self
    {
        $this->autoRollbackConfiguration = $value;

        return $this;
    }

    public function setDeploymentConfigName(?string $value): self
    {
        $this->deploymentConfigName = $value;

        return $this;
    }

    public function setDeploymentGroupName(?string $value): self
    {
        $this->deploymentGroupName = $value;

        return $this;
    }

    public function setDescription(?string $value): self
    {
        $this->description = $value;

        return $this;
    }

    /**
     * @param FileExistsBehavior::*|null $value
     */
    public function setFileExistsBehavior(?string $value): self
    {
        $this->fileExistsBehavior = $value;

        return $this;
    }

    public function setIgnoreApplicationStopFailures(?bool $value): self
    {
        $this->ignoreApplicationStopFailures = $value;

        return $this;
    }

    public function setOverrideAlarmConfiguration(?AlarmConfiguration $value): self
    {
        $this->overrideAlarmConfiguration = $value;

        return $this;
    }

    public function setRevision(?RevisionLocation $value): self
    {
        $this->revision = $value;

        return $this;
    }

    public function setTargetInstances(?TargetInstances $value): self
    {
        $this->targetInstances = $value;

        return $this;
    }

    public function setUpdateOutdatedInstancesOnly(?bool $value): self
    {
        $this->updateOutdatedInstancesOnly = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->applicationName) {
            throw new InvalidArgument(\sprintf('Missing parameter "applicationName" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['applicationName'] = $v;
        if (null !== $v = $this->deploymentGroupName) {
            $payload['deploymentGroupName'] = $v;
        }
        if (null !== $v = $this->revision) {
            $payload['revision'] = $v->requestBody();
        }
        if (null !== $v = $this->deploymentConfigName) {
            $payload['deploymentConfigName'] = $v;
        }
        if (null !== $v = $this->description) {
            $payload['description'] = $v;
        }
        if (null !== $v = $this->ignoreApplicationStopFailures) {
            $payload['ignoreApplicationStopFailures'] = (bool) $v;
        }
        if (null !== $v = $this->targetInstances) {
            $payload['targetInstances'] = $v->requestBody();
        }
        if (null !== $v = $this->autoRollbackConfiguration) {
            $payload['autoRollbackConfiguration'] = $v->requestBody();
        }
        if (null !== $v = $this->updateOutdatedInstancesOnly) {
            $payload['updateOutdatedInstancesOnly'] = (bool) $v;
        }
        if (null !== $v = $this->fileExistsBehavior) {
            if (!FileExistsBehavior::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "fileExistsBehavior" for "%s". The value "%s" is not a valid "FileExistsBehavior".', __CLASS__, $v));
            }
            $payload['fileExistsBehavior'] = $v;
        }
        if (null !== $v = $this->overrideAlarmConfiguration) {
            $payload['overrideAlarmConfiguration'] = $v->requestBody();
        }

        return $payload;
    }
}
