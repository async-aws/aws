<?php

namespace AsyncAws\CloudWatchLogs\Input;

use AsyncAws\CloudWatchLogs\Enum\LogGroupClass;
use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

final class DescribeLogGroupsRequest extends Input
{
    /**
     * When `includeLinkedAccounts` is set to `true`, use this parameter to specify the list of accounts to search. You can
     * specify as many as 20 account IDs in the array.
     *
     * @var string[]|null
     */
    private $accountIdentifiers;

    /**
     * The prefix to match.
     *
     * > `logGroupNamePrefix` and `logGroupNamePattern` are mutually exclusive. Only one of these parameters can be passed.
     *
     * @var string|null
     */
    private $logGroupNamePrefix;

    /**
     * If you specify a string for this parameter, the operation returns only log groups that have names that match the
     * string based on a case-sensitive substring search. For example, if you specify `DataLogs`, log groups named
     * `DataLogs`, `aws/DataLogs`, and `GroupDataLogs` would match, but `datalogs`, `Data/log/s` and `Groupdata` would not
     * match.
     *
     * If you specify `logGroupNamePattern` in your request, then only `arn`, `creationTime`, and `logGroupName` are
     * included in the response.
     *
     * > `logGroupNamePattern` and `logGroupNamePrefix` are mutually exclusive. Only one of these parameters can be passed.
     *
     * @var string|null
     */
    private $logGroupNamePattern;

    /**
     * The token for the next set of items to return. (You received this token from a previous call.).
     *
     * @var string|null
     */
    private $nextToken;

    /**
     * The maximum number of items returned. If you don't specify a value, the default is up to 50 items.
     *
     * @var int|null
     */
    private $limit;

    /**
     * If you are using a monitoring account, set this to `true` to have the operation return log groups in the accounts
     * listed in `accountIdentifiers`.
     *
     * If this parameter is set to `true` and `accountIdentifiers` contains a null value, the operation returns all log
     * groups in the monitoring account and all log groups in all source accounts that are linked to the monitoring account.
     *
     * The default for this parameter is `false`.
     *
     * @var bool|null
     */
    private $includeLinkedAccounts;

    /**
     * Use this parameter to limit the results to only those log groups in the specified log group class. If you omit this
     * parameter, log groups of all classes can be returned.
     *
     * Specifies the log group class for this log group. There are three classes:
     *
     * - The `Standard` log class supports all CloudWatch Logs features.
     * - The `Infrequent Access` log class supports a subset of CloudWatch Logs features and incurs lower costs.
     * - Use the `Delivery` log class only for delivering Lambda logs to store in Amazon S3 or Amazon Data Firehose. Log
     *   events in log groups in the Delivery class are kept in CloudWatch Logs for only one day. This log class doesn't
     *   offer rich CloudWatch Logs capabilities such as CloudWatch Logs Insights queries.
     *
     * For details about the features supported by each class, see Log classes [^1]
     *
     * [^1]: https://docs.aws.amazon.com/AmazonCloudWatch/latest/logs/CloudWatch_Logs_Log_Classes.html
     *
     * @var LogGroupClass::*|null
     */
    private $logGroupClass;

    /**
     * Use this array to filter the list of log groups returned. If you specify this parameter, the only other filter that
     * you can choose to specify is `includeLinkedAccounts`.
     *
     * If you are using this operation in a monitoring account, you can specify the ARNs of log groups in source accounts
     * and in the monitoring account itself. If you are using this operation in an account that is not a cross-account
     * monitoring account, you can specify only log group names in the same account as the operation.
     *
     * @var string[]|null
     */
    private $logGroupIdentifiers;

    /**
     * @param array{
     *   accountIdentifiers?: string[]|null,
     *   logGroupNamePrefix?: string|null,
     *   logGroupNamePattern?: string|null,
     *   nextToken?: string|null,
     *   limit?: int|null,
     *   includeLinkedAccounts?: bool|null,
     *   logGroupClass?: LogGroupClass::*|null,
     *   logGroupIdentifiers?: string[]|null,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->accountIdentifiers = $input['accountIdentifiers'] ?? null;
        $this->logGroupNamePrefix = $input['logGroupNamePrefix'] ?? null;
        $this->logGroupNamePattern = $input['logGroupNamePattern'] ?? null;
        $this->nextToken = $input['nextToken'] ?? null;
        $this->limit = $input['limit'] ?? null;
        $this->includeLinkedAccounts = $input['includeLinkedAccounts'] ?? null;
        $this->logGroupClass = $input['logGroupClass'] ?? null;
        $this->logGroupIdentifiers = $input['logGroupIdentifiers'] ?? null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   accountIdentifiers?: string[]|null,
     *   logGroupNamePrefix?: string|null,
     *   logGroupNamePattern?: string|null,
     *   nextToken?: string|null,
     *   limit?: int|null,
     *   includeLinkedAccounts?: bool|null,
     *   logGroupClass?: LogGroupClass::*|null,
     *   logGroupIdentifiers?: string[]|null,
     *   '@region'?: string|null,
     * }|DescribeLogGroupsRequest $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return string[]
     */
    public function getAccountIdentifiers(): array
    {
        return $this->accountIdentifiers ?? [];
    }

    public function getIncludeLinkedAccounts(): ?bool
    {
        return $this->includeLinkedAccounts;
    }

    public function getLimit(): ?int
    {
        return $this->limit;
    }

    /**
     * @return LogGroupClass::*|null
     */
    public function getLogGroupClass(): ?string
    {
        return $this->logGroupClass;
    }

    /**
     * @return string[]
     */
    public function getLogGroupIdentifiers(): array
    {
        return $this->logGroupIdentifiers ?? [];
    }

    public function getLogGroupNamePattern(): ?string
    {
        return $this->logGroupNamePattern;
    }

    public function getLogGroupNamePrefix(): ?string
    {
        return $this->logGroupNamePrefix;
    }

    public function getNextToken(): ?string
    {
        return $this->nextToken;
    }

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = [
            'Content-Type' => 'application/x-amz-json-1.1',
            'X-Amz-Target' => 'Logs_20140328.DescribeLogGroups',
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

    /**
     * @param string[] $value
     */
    public function setAccountIdentifiers(array $value): self
    {
        $this->accountIdentifiers = $value;

        return $this;
    }

    public function setIncludeLinkedAccounts(?bool $value): self
    {
        $this->includeLinkedAccounts = $value;

        return $this;
    }

    public function setLimit(?int $value): self
    {
        $this->limit = $value;

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

    /**
     * @param string[] $value
     */
    public function setLogGroupIdentifiers(array $value): self
    {
        $this->logGroupIdentifiers = $value;

        return $this;
    }

    public function setLogGroupNamePattern(?string $value): self
    {
        $this->logGroupNamePattern = $value;

        return $this;
    }

    public function setLogGroupNamePrefix(?string $value): self
    {
        $this->logGroupNamePrefix = $value;

        return $this;
    }

    public function setNextToken(?string $value): self
    {
        $this->nextToken = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->accountIdentifiers) {
            $index = -1;
            $payload['accountIdentifiers'] = [];
            foreach ($v as $listValue) {
                ++$index;
                $payload['accountIdentifiers'][$index] = $listValue;
            }
        }
        if (null !== $v = $this->logGroupNamePrefix) {
            $payload['logGroupNamePrefix'] = $v;
        }
        if (null !== $v = $this->logGroupNamePattern) {
            $payload['logGroupNamePattern'] = $v;
        }
        if (null !== $v = $this->nextToken) {
            $payload['nextToken'] = $v;
        }
        if (null !== $v = $this->limit) {
            $payload['limit'] = $v;
        }
        if (null !== $v = $this->includeLinkedAccounts) {
            $payload['includeLinkedAccounts'] = (bool) $v;
        }
        if (null !== $v = $this->logGroupClass) {
            if (!LogGroupClass::exists($v)) {
                /** @psalm-suppress NoValue */
                throw new InvalidArgument(\sprintf('Invalid parameter "logGroupClass" for "%s". The value "%s" is not a valid "LogGroupClass".', __CLASS__, $v));
            }
            $payload['logGroupClass'] = $v;
        }
        if (null !== $v = $this->logGroupIdentifiers) {
            $index = -1;
            $payload['logGroupIdentifiers'] = [];
            foreach ($v as $listValue) {
                ++$index;
                $payload['logGroupIdentifiers'][$index] = $listValue;
            }
        }

        return $payload;
    }
}
