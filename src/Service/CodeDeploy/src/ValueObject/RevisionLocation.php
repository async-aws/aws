<?php

namespace AsyncAws\CodeDeploy\ValueObject;

use AsyncAws\CodeDeploy\Enum\RevisionLocationType;
use AsyncAws\Core\Exception\InvalidArgument;

/**
 * The type and location of the revision to deploy.
 */
final class RevisionLocation
{
    /**
     * The type of application revision:.
     */
    private $revisionType;

    /**
     * Information about the location of a revision stored in Amazon S3.
     */
    private $s3Location;

    /**
     * Information about the location of application artifacts stored in GitHub.
     */
    private $gitHubLocation;

    /**
     * Information about the location of an AWS Lambda deployment revision stored as a RawString.
     */
    private $string;

    /**
     * The content of an AppSpec file for an AWS Lambda or Amazon ECS deployment. The content is formatted as JSON or YAML
     * and stored as a RawString.
     */
    private $appSpecContent;

    /**
     * @param array{
     *   revisionType?: null|RevisionLocationType::*,
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
     * @return RevisionLocationType::*|null
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
                throw new InvalidArgument(sprintf('Invalid parameter "revisionType" for "%s". The value "%s" is not a valid "RevisionLocationType".', __CLASS__, $v));
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
