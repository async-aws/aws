<?php

namespace AsyncAws\Athena\ValueObject;

/**
 * The location in Amazon S3 where query results were stored and the encryption option, if any, used for query results.
 * These are known as "client-side settings". If workgroup settings override client-side settings, then the query uses
 * the location for the query results and the encryption configuration that are specified for the workgroup.
 */
final class ResultConfiguration
{
    /**
     * The location in Amazon S3 where your query results are stored, such as `s3://path/to/query/bucket/`. To run the
     * query, you must specify the query results location using one of the ways: either for individual queries using either
     * this setting (client-side), or in the workgroup, using WorkGroupConfiguration. If none of them is set, Athena issues
     * an error that no output location is provided. For more information, see Query Results. If workgroup settings override
     * client-side settings, then the query uses the settings specified for the workgroup. See
     * WorkGroupConfiguration$EnforceWorkGroupConfiguration.
     *
     * @see https://docs.aws.amazon.com/athena/latest/ug/querying.html
     */
    private $outputLocation;

    /**
     * If query results are encrypted in Amazon S3, indicates the encryption option used (for example, `SSE-KMS` or
     * `CSE-KMS`) and key information. This is a client-side setting. If workgroup settings override client-side settings,
     * then the query uses the encryption configuration that is specified for the workgroup, and also uses the location for
     * storing query results specified in the workgroup. See WorkGroupConfiguration$EnforceWorkGroupConfiguration and
     * Workgroup Settings Override Client-Side Settings.
     *
     * @see https://docs.aws.amazon.com/athena/latest/ug/workgroups-settings-override.html
     */
    private $encryptionConfiguration;

    /**
     * @param array{
     *   OutputLocation?: null|string,
     *   EncryptionConfiguration?: null|EncryptionConfiguration|array,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->outputLocation = $input['OutputLocation'] ?? null;
        $this->encryptionConfiguration = isset($input['EncryptionConfiguration']) ? EncryptionConfiguration::create($input['EncryptionConfiguration']) : null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getEncryptionConfiguration(): ?EncryptionConfiguration
    {
        return $this->encryptionConfiguration;
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

        return $payload;
    }
}
