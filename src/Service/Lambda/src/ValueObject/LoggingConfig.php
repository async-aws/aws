<?php

namespace AsyncAws\Lambda\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Lambda\Enum\ApplicationLogLevel;
use AsyncAws\Lambda\Enum\LogFormat;
use AsyncAws\Lambda\Enum\SystemLogLevel;

/**
 * The function's Amazon CloudWatch Logs configuration settings.
 */
final class LoggingConfig
{
    /**
     * The format in which Lambda sends your function's application and system logs to CloudWatch. Select between plain text
     * and structured JSON.
     *
     * @var LogFormat::*|null
     */
    private $logFormat;

    /**
     * Set this property to filter the application logs for your function that Lambda sends to CloudWatch. Lambda only sends
     * application logs at the selected level and lower.
     *
     * @var ApplicationLogLevel::*|null
     */
    private $applicationLogLevel;

    /**
     * Set this property to filter the system logs for your function that Lambda sends to CloudWatch. Lambda only sends
     * system logs at the selected level and lower.
     *
     * @var SystemLogLevel::*|null
     */
    private $systemLogLevel;

    /**
     * The name of the Amazon CloudWatch log group the function sends logs to. By default, Lambda functions send logs to a
     * default log group named `/aws/lambda/<function name>`. To use a different log group, enter an existing log
     * group or enter a new log group name.
     *
     * @var string|null
     */
    private $logGroup;

    /**
     * @param array{
     *   LogFormat?: null|LogFormat::*,
     *   ApplicationLogLevel?: null|ApplicationLogLevel::*,
     *   SystemLogLevel?: null|SystemLogLevel::*,
     *   LogGroup?: null|string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->logFormat = $input['LogFormat'] ?? null;
        $this->applicationLogLevel = $input['ApplicationLogLevel'] ?? null;
        $this->systemLogLevel = $input['SystemLogLevel'] ?? null;
        $this->logGroup = $input['LogGroup'] ?? null;
    }

    /**
     * @param array{
     *   LogFormat?: null|LogFormat::*,
     *   ApplicationLogLevel?: null|ApplicationLogLevel::*,
     *   SystemLogLevel?: null|SystemLogLevel::*,
     *   LogGroup?: null|string,
     * }|LoggingConfig $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return ApplicationLogLevel::*|null
     */
    public function getApplicationLogLevel(): ?string
    {
        return $this->applicationLogLevel;
    }

    /**
     * @return LogFormat::*|null
     */
    public function getLogFormat(): ?string
    {
        return $this->logFormat;
    }

    public function getLogGroup(): ?string
    {
        return $this->logGroup;
    }

    /**
     * @return SystemLogLevel::*|null
     */
    public function getSystemLogLevel(): ?string
    {
        return $this->systemLogLevel;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->logFormat) {
            if (!LogFormat::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "LogFormat" for "%s". The value "%s" is not a valid "LogFormat".', __CLASS__, $v));
            }
            $payload['LogFormat'] = $v;
        }
        if (null !== $v = $this->applicationLogLevel) {
            if (!ApplicationLogLevel::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "ApplicationLogLevel" for "%s". The value "%s" is not a valid "ApplicationLogLevel".', __CLASS__, $v));
            }
            $payload['ApplicationLogLevel'] = $v;
        }
        if (null !== $v = $this->systemLogLevel) {
            if (!SystemLogLevel::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "SystemLogLevel" for "%s". The value "%s" is not a valid "SystemLogLevel".', __CLASS__, $v));
            }
            $payload['SystemLogLevel'] = $v;
        }
        if (null !== $v = $this->logGroup) {
            $payload['LogGroup'] = $v;
        }

        return $payload;
    }
}
