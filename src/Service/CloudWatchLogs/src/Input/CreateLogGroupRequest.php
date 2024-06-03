<?php

namespace AsyncAws\CloudWatchLogs\Input;

use AsyncAws\CloudWatchLogs\Enum\LogGroupClass;
use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

final class CreateLogGroupRequest extends Input
{
    /**
     * A name for the log group.
     *
     * @required
     *
     * @var string|null
     */
    private $logGroupName;

    /**
     * The Amazon Resource Name (ARN) of the KMS key to use when encrypting log data. For more information, see Amazon
     * Resource Names [^1].
     *
     * [^1]: https://docs.aws.amazon.com/general/latest/gr/aws-arns-and-namespaces.html#arn-syntax-kms
     *
     * @var string|null
     */
    private $kmsKeyId;

    /**
     * The key-value pairs to use for the tags.
     *
     * You can grant users access to certain log groups while preventing them from accessing other log groups. To do so, tag
     * your groups and use IAM policies that refer to those tags. To assign tags when you create a log group, you must have
     * either the `logs:TagResource` or `logs:TagLogGroup` permission. For more information about tagging, see Tagging
     * Amazon Web Services resources [^1]. For more information about using tags to control access, see Controlling access
     * to Amazon Web Services resources using tags [^2].
     *
     * [^1]: https://docs.aws.amazon.com/general/latest/gr/aws_tagging.html
     * [^2]: https://docs.aws.amazon.com/IAM/latest/UserGuide/access_tags.html
     *
     * @var array<string, string>|null
     */
    private $tags;

    /**
     * Use this parameter to specify the log group class for this log group. There are two classes:
     *
     * - The `Standard` log class supports all CloudWatch Logs features.
     * - The `Infrequent Access` log class supports a subset of CloudWatch Logs features and incurs lower costs.
     *
     * If you omit this parameter, the default of `STANDARD` is used.
     *
     * ! The value of `logGroupClass` can't be changed after a log group is created.
     *
     * For details about the features supported by each class, see Log classes [^1]
     *
     * [^1]: https://docs.aws.amazon.com/AmazonCloudWatch/latest/logs/CloudWatch_Logs_Log_Classes.html
     *
     * @var LogGroupClass::*|null
     */
    private $logGroupClass;

    /**
     * @param array{
     *   logGroupName?: string,
     *   kmsKeyId?: null|string,
     *   tags?: null|array<string, string>,
     *   logGroupClass?: null|LogGroupClass::*,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->logGroupName = $input['logGroupName'] ?? null;
        $this->kmsKeyId = $input['kmsKeyId'] ?? null;
        $this->tags = $input['tags'] ?? null;
        $this->logGroupClass = $input['logGroupClass'] ?? null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   logGroupName?: string,
     *   kmsKeyId?: null|string,
     *   tags?: null|array<string, string>,
     *   logGroupClass?: null|LogGroupClass::*,
     *   '@region'?: string|null,
     * }|CreateLogGroupRequest $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getKmsKeyId(): ?string
    {
        return $this->kmsKeyId;
    }

    /**
     * @return LogGroupClass::*|null
     */
    public function getLogGroupClass(): ?string
    {
        return $this->logGroupClass;
    }

    public function getLogGroupName(): ?string
    {
        return $this->logGroupName;
    }

    /**
     * @return array<string, string>
     */
    public function getTags(): array
    {
        return $this->tags ?? [];
    }

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = [
            'Content-Type' => 'application/x-amz-json-1.1',
            'X-Amz-Target' => 'Logs_20140328.CreateLogGroup',
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

    public function setKmsKeyId(?string $value): self
    {
        $this->kmsKeyId = $value;

        return $this;
    }

    /**
     * @param LogGroupClass::*|null $value
     */
    public function setLogGroupClass(?string $value): self
    {
        $this->logGroupClass = $value;

        return $this;
    }

    public function setLogGroupName(?string $value): self
    {
        $this->logGroupName = $value;

        return $this;
    }

    /**
     * @param array<string, string> $value
     */
    public function setTags(array $value): self
    {
        $this->tags = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->logGroupName) {
            throw new InvalidArgument(sprintf('Missing parameter "logGroupName" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['logGroupName'] = $v;
        if (null !== $v = $this->kmsKeyId) {
            $payload['kmsKeyId'] = $v;
        }
        if (null !== $v = $this->tags) {
            if (empty($v)) {
                $payload['tags'] = new \stdClass();
            } else {
                $payload['tags'] = [];
                foreach ($v as $name => $mv) {
                    $payload['tags'][$name] = $mv;
                }
            }
        }
        if (null !== $v = $this->logGroupClass) {
            if (!LogGroupClass::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "logGroupClass" for "%s". The value "%s" is not a valid "LogGroupClass".', __CLASS__, $v));
            }
            $payload['logGroupClass'] = $v;
        }

        return $payload;
    }
}
