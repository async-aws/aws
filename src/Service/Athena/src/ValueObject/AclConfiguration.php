<?php

namespace AsyncAws\Athena\ValueObject;

use AsyncAws\Athena\Enum\S3AclOption;
use AsyncAws\Core\Exception\InvalidArgument;

/**
 * Indicates that an Amazon S3 canned ACL should be set to control ownership of stored query results, including data
 * files inserted by Athena as the result of statements like CTAS or INSERT INTO. When Athena stores query results in
 * Amazon S3, the canned ACL is set with the `x-amz-acl` request header. For more information about S3 Object Ownership,
 * see Object Ownership settings [^1] in the *Amazon S3 User Guide*.
 *
 * [^1]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/about-object-ownership.html#object-ownership-overview
 */
final class AclConfiguration
{
    /**
     * The Amazon S3 canned ACL that Athena should specify when storing query results, including data files inserted by
     * Athena as the result of statements like CTAS or INSERT INTO. Currently the only supported canned ACL is
     * `BUCKET_OWNER_FULL_CONTROL`. If a query runs in a workgroup and the workgroup overrides client-side settings, then
     * the Amazon S3 canned ACL specified in the workgroup's settings is used for all queries that run in the workgroup. For
     * more information about Amazon S3 canned ACLs, see Canned ACL [^1] in the *Amazon S3 User Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/acl-overview.html#canned-acl
     *
     * @var S3AclOption::*|string
     */
    private $s3AclOption;

    /**
     * @param array{
     *   S3AclOption: S3AclOption::*|string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->s3AclOption = $input['S3AclOption'] ?? $this->throwException(new InvalidArgument('Missing required field "S3AclOption".'));
    }

    /**
     * @param array{
     *   S3AclOption: S3AclOption::*|string,
     * }|AclConfiguration $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return S3AclOption::*|string
     */
    public function getS3AclOption(): string
    {
        return $this->s3AclOption;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        $v = $this->s3AclOption;
        if (!S3AclOption::exists($v)) {
            throw new InvalidArgument(\sprintf('Invalid parameter "S3AclOption" for "%s". The value "%s" is not a valid "S3AclOption".', __CLASS__, $v));
        }
        $payload['S3AclOption'] = $v;

        return $payload;
    }

    /**
     * @return never
     */
    private function throwException(\Throwable $exception)
    {
        throw $exception;
    }
}
