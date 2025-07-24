<?php

namespace AsyncAws\CodeDeploy\ValueObject;

use AsyncAws\CodeDeploy\Enum\RevisionLocationType;
use AsyncAws\Core\Exception\InvalidArgument;

/**
 * Information about the location of an application revision.
 */
final class RevisionLocation
{
    /**
     * The type of application revision:
     *
     * - S3: An application revision stored in Amazon S3.
     * - GitHub: An application revision stored in GitHub (EC2/On-premises deployments only).
     * - String: A YAML-formatted or JSON-formatted string (Lambda deployments only).
     * - AppSpecContent: An `AppSpecContent` object that contains the contents of an AppSpec file for an Lambda or Amazon
     *   ECS deployment. The content is formatted as JSON or YAML stored as a RawString.
     *
     * @var RevisionLocationType::*|string|null
     */
    private $revisionType;

    /**
     * Information about the location of a revision stored in Amazon S3.
     *
     * @var S3Location|null
     */
    private $s3Location;

    /**
     * Information about the location of application artifacts stored in GitHub.
     *
     * @var GitHubLocation|null
     */
    private $gitHubLocation;

    /**
     * Information about the location of an Lambda deployment revision stored as a RawString.
     *
     * @var RawString|null
     */
    private $string;

    /**
     * The content of an AppSpec file for an Lambda or Amazon ECS deployment. The content is formatted as JSON or YAML and
     * stored as a RawString.
     *
     * @var AppSpecContent|null
     */
    private $appSpecContent;

    /**
     * @param array{
     *   revisionType?: null|RevisionLocationType::*|string,
     *   s3Location?: null|S3Location|array,
     *   gitHubLocation?: null|GitHubLocation|array,
     *   string?: null|RawString|array,
     *   appSpecContent?: null|AppSpecContent|array,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->revisionType = $input['revisionType'] ?? null;
        $this->s3Location = isset($input['s3Location']) ? S3Location::create($input['s3Location']) : null;
        $this->gitHubLocation = isset($input['gitHubLocation']) ? GitHubLocation::create($input['gitHubLocation']) : null;
        $this->string = isset($input['string']) ? RawString::create($input['string']) : null;
        $this->appSpecContent = isset($input['appSpecContent']) ? AppSpecContent::create($input['appSpecContent']) : null;
    }

    /**
     * @param array{
     *   revisionType?: null|RevisionLocationType::*|string,
     *   s3Location?: null|S3Location|array,
     *   gitHubLocation?: null|GitHubLocation|array,
     *   string?: null|RawString|array,
     *   appSpecContent?: null|AppSpecContent|array,
     * }|RevisionLocation $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getAppSpecContent(): ?AppSpecContent
    {
        return $this->appSpecContent;
    }

    public function getGitHubLocation(): ?GitHubLocation
    {
        return $this->gitHubLocation;
    }

    /**
     * @return RevisionLocationType::*|string|null
     */
    public function getRevisionType(): ?string
    {
        return $this->revisionType;
    }

    public function getS3Location(): ?S3Location
    {
        return $this->s3Location;
    }

    public function getString(): ?RawString
    {
        return $this->string;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->revisionType) {
            if (!RevisionLocationType::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "revisionType" for "%s". The value "%s" is not a valid "RevisionLocationType".', __CLASS__, $v));
            }
            $payload['revisionType'] = $v;
        }
        if (null !== $v = $this->s3Location) {
            $payload['s3Location'] = $v->requestBody();
        }
        if (null !== $v = $this->gitHubLocation) {
            $payload['gitHubLocation'] = $v->requestBody();
        }
        if (null !== $v = $this->string) {
            $payload['string'] = $v->requestBody();
        }
        if (null !== $v = $this->appSpecContent) {
            $payload['appSpecContent'] = $v->requestBody();
        }

        return $payload;
    }
}
