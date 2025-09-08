<?php

namespace AsyncAws\Athena\ValueObject;

/**
 * The location in Amazon S3 where query and calculation results are stored and the encryption option, if any, used for
 * query and calculation results. These are known as "client-side settings". If workgroup settings override client-side
 * settings, then the query uses the workgroup settings.
 */
final class ResultConfiguration
{
    /**
     * The location in Amazon S3 where your query and calculation results are stored, such as `s3://path/to/query/bucket/`.
     * To run the query, you must specify the query results location using one of the ways: either for individual queries
     * using either this setting (client-side), or in the workgroup, using WorkGroupConfiguration. If none of them is set,
     * Athena issues an error that no output location is provided. If workgroup settings override client-side settings, then
     * the query uses the settings specified for the workgroup. See WorkGroupConfiguration$EnforceWorkGroupConfiguration.
     *
     * @var string|null
     */
    private $outputLocation;

    /**
     * If query and calculation results are encrypted in Amazon S3, indicates the encryption option used (for example,
     * `SSE_KMS` or `CSE_KMS`) and key information. This is a client-side setting. If workgroup settings override
     * client-side settings, then the query uses the encryption configuration that is specified for the workgroup, and also
     * uses the location for storing query results specified in the workgroup. See
     * WorkGroupConfiguration$EnforceWorkGroupConfiguration and Workgroup Settings Override Client-Side Settings [^1].
     *
     * [^1]: https://docs.aws.amazon.com/athena/latest/ug/workgroups-settings-override.html
     *
     * @var EncryptionConfiguration|null
     */
    private $encryptionConfiguration;

    /**
     * The Amazon Web Services account ID that you expect to be the owner of the Amazon S3 bucket specified by
     * ResultConfiguration$OutputLocation. If set, Athena uses the value for `ExpectedBucketOwner` when it makes Amazon S3
     * calls to your specified output location. If the `ExpectedBucketOwner` Amazon Web Services account ID does not match
     * the actual owner of the Amazon S3 bucket, the call fails with a permissions error.
     *
     * This is a client-side setting. If workgroup settings override client-side settings, then the query uses the
     * `ExpectedBucketOwner` setting that is specified for the workgroup, and also uses the location for storing query
     * results specified in the workgroup. See WorkGroupConfiguration$EnforceWorkGroupConfiguration and Workgroup Settings
     * Override Client-Side Settings [^1].
     *
     * [^1]: https://docs.aws.amazon.com/athena/latest/ug/workgroups-settings-override.html
     *
     * @var string|null
     */
    private $expectedBucketOwner;

    /**
     * Indicates that an Amazon S3 canned ACL should be set to control ownership of stored query results. Currently the only
     * supported canned ACL is `BUCKET_OWNER_FULL_CONTROL`. This is a client-side setting. If workgroup settings override
     * client-side settings, then the query uses the ACL configuration that is specified for the workgroup, and also uses
     * the location for storing query results specified in the workgroup. For more information, see
     * WorkGroupConfiguration$EnforceWorkGroupConfiguration and Workgroup Settings Override Client-Side Settings [^1].
     *
     * [^1]: https://docs.aws.amazon.com/athena/latest/ug/workgroups-settings-override.html
     *
     * @var AclConfiguration|null
     */
    private $aclConfiguration;

    /**
     * @param array{
     *   OutputLocation?: string|null,
     *   EncryptionConfiguration?: EncryptionConfiguration|array|null,
     *   ExpectedBucketOwner?: string|null,
     *   AclConfiguration?: AclConfiguration|array|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->outputLocation = $input['OutputLocation'] ?? null;
        $this->encryptionConfiguration = isset($input['EncryptionConfiguration']) ? EncryptionConfiguration::create($input['EncryptionConfiguration']) : null;
        $this->expectedBucketOwner = $input['ExpectedBucketOwner'] ?? null;
        $this->aclConfiguration = isset($input['AclConfiguration']) ? AclConfiguration::create($input['AclConfiguration']) : null;
    }

    /**
     * @param array{
     *   OutputLocation?: string|null,
     *   EncryptionConfiguration?: EncryptionConfiguration|array|null,
     *   ExpectedBucketOwner?: string|null,
     *   AclConfiguration?: AclConfiguration|array|null,
     * }|ResultConfiguration $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getAclConfiguration(): ?AclConfiguration
    {
        return $this->aclConfiguration;
    }

    public function getEncryptionConfiguration(): ?EncryptionConfiguration
    {
        return $this->encryptionConfiguration;
    }

    public function getExpectedBucketOwner(): ?string
    {
        return $this->expectedBucketOwner;
    }

    public function getOutputLocation(): ?string
    {
        return $this->outputLocation;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->outputLocation) {
            $payload['OutputLocation'] = $v;
        }
        if (null !== $v = $this->encryptionConfiguration) {
            $payload['EncryptionConfiguration'] = $v->requestBody();
        }
        if (null !== $v = $this->expectedBucketOwner) {
            $payload['ExpectedBucketOwner'] = $v;
        }
        if (null !== $v = $this->aclConfiguration) {
            $payload['AclConfiguration'] = $v->requestBody();
        }

        return $payload;
    }
}
