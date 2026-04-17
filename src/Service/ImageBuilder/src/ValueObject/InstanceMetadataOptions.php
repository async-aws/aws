<?php

namespace AsyncAws\ImageBuilder\ValueObject;

/**
 * The instance metadata options that apply to the HTTP requests that pipeline builds use to launch EC2 build and test
 * instances. For more information about instance metadata options, see Configure the instance metadata options [^1] in
 * the **Amazon EC2 User Guide** for Linux instances, or Configure the instance metadata options [^2] in the **Amazon
 * EC2 Windows Guide** for Windows instances.
 *
 * [^1]: https://docs.aws.amazon.com/AWSEC2/latest/UserGuide/configuring-instance-metadata-options.html
 * [^2]: https://docs.aws.amazon.com/AWSEC2/latest/WindowsGuide/configuring-instance-metadata-options.html
 */
final class InstanceMetadataOptions
{
    /**
     * Indicates whether a signed token header is required for instance metadata retrieval requests. The values affect the
     * response as follows:
     *
     * - **required** – When you retrieve the IAM role credentials, version 2.0 credentials are returned in all cases.
     * - **optional** – You can include a signed token header in your request to retrieve instance metadata, or you can
     *   leave it out. If you include it, version 2.0 credentials are returned for the IAM role. Otherwise, version 1.0
     *   credentials are returned.
     *
     * The default setting is **optional**.
     *
     * @var string|null
     */
    private $httpTokens;

    /**
     * Limit the number of hops that an instance metadata request can traverse to reach its destination. The default is one
     * hop. However, if HTTP tokens are required, container image builds need a minimum of two hops.
     *
     * @var int|null
     */
    private $httpPutResponseHopLimit;

    /**
     * @param array{
     *   httpTokens?: string|null,
     *   httpPutResponseHopLimit?: int|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->httpTokens = $input['httpTokens'] ?? null;
        $this->httpPutResponseHopLimit = $input['httpPutResponseHopLimit'] ?? null;
    }

    /**
     * @param array{
     *   httpTokens?: string|null,
     *   httpPutResponseHopLimit?: int|null,
     * }|InstanceMetadataOptions $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getHttpPutResponseHopLimit(): ?int
    {
        return $this->httpPutResponseHopLimit;
    }

    public function getHttpTokens(): ?string
    {
        return $this->httpTokens;
    }
}
