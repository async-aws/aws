<?php

namespace AsyncAws\Ec2\Input;

use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;
use AsyncAws\Ec2\ValueObject\Filter;

final class DescribeImagesRequest extends Input
{
    /**
     * Scopes the images by users with explicit launch permissions. Specify an Amazon Web Services account ID, `self` (the
     * sender of the request), or `all` (public AMIs).
     *
     * - If you specify an Amazon Web Services account ID that is not your own, only AMIs shared with that specific Amazon
     *   Web Services account ID are returned. However, AMIs that are shared with the account’s organization or
     *   organizational unit (OU) are not returned.
     * - If you specify `self` or your own Amazon Web Services account ID, AMIs shared with your account are returned. In
     *   addition, AMIs that are shared with the organization or OU of which you are member are also returned.
     * - If you specify `all`, all public AMIs are returned.
     *
     * @var string[]|null
     */
    private $executableUsers;

    /**
     * The image IDs.
     *
     * Default: Describes all images available to you.
     *
     * @var string[]|null
     */
    private $imageIds;

    /**
     * Scopes the results to images with the specified owners. You can specify a combination of Amazon Web Services account
     * IDs, `self`, `amazon`, `aws-backup-vault`, and `aws-marketplace`. If you omit this parameter, the results include all
     * images for which you have launch permissions, regardless of ownership.
     *
     * @var string[]|null
     */
    private $owners;

    /**
     * Specifies whether to include deprecated AMIs.
     *
     * Default: No deprecated AMIs are included in the response.
     *
     * > If you are the AMI owner, all deprecated AMIs appear in the response regardless of what you specify for this
     * > parameter.
     *
     * @var bool|null
     */
    private $includeDeprecated;

    /**
     * Specifies whether to include disabled AMIs.
     *
     * Default: No disabled AMIs are included in the response.
     *
     * @var bool|null
     */
    private $includeDisabled;

    /**
     * The maximum number of items to return for this request. To get the next page of items, make another request with the
     * token returned in the output. For more information, see Pagination [^1].
     *
     * [^1]: https://docs.aws.amazon.com/AWSEC2/latest/APIReference/Query-Requests.html#api-pagination
     *
     * @var int|null
     */
    private $maxResults;

    /**
     * The token returned from a previous paginated request. Pagination continues from the end of the items returned by the
     * previous request.
     *
     * @var string|null
     */
    private $nextToken;

    /**
     * Checks whether you have the required permissions for the action, without actually making the request, and provides an
     * error response. If you have the required permissions, the error response is `DryRunOperation`. Otherwise, it is
     * `UnauthorizedOperation`.
     *
     * @var bool|null
     */
    private $dryRun;

    /**
     * The filters.
     *
     * - `architecture` - The image architecture (`i386` | `x86_64` | `arm64` | `x86_64_mac` | `arm64_mac`).
     * - `block-device-mapping.delete-on-termination` - A Boolean value that indicates whether the Amazon EBS volume is
     *   deleted on instance termination.
     * - `block-device-mapping.device-name` - The device name specified in the block device mapping (for example, `/dev/sdh`
     *   or `xvdh`).
     * - `block-device-mapping.snapshot-id` - The ID of the snapshot used for the Amazon EBS volume.
     * - `block-device-mapping.volume-size` - The volume size of the Amazon EBS volume, in GiB.
     * - `block-device-mapping.volume-type` - The volume type of the Amazon EBS volume (`io1` | `io2` | `gp2` | `gp3` | `sc1
     *   `| `st1` | `standard`).
     * - `block-device-mapping.encrypted` - A Boolean that indicates whether the Amazon EBS volume is encrypted.
     * - `creation-date` - The time when the image was created, in the ISO 8601 format in the UTC time zone
     *   (YYYY-MM-DDThh:mm:ss.sssZ), for example, `2021-09-29T11:04:43.305Z`. You can use a wildcard (`*`), for example,
     *   `2021-09-29T*`, which matches an entire day.
     * - `description` - The description of the image (provided during image creation).
     * - `ena-support` - A Boolean that indicates whether enhanced networking with ENA is enabled.
     * - `free-tier-eligible` - A Boolean that indicates whether this image can be used under the Amazon Web Services Free
     *   Tier (`true` | `false`).
     * - `hypervisor` - The hypervisor type (`ovm` | `xen`).
     * - `image-allowed` - A Boolean that indicates whether the image meets the criteria specified for Allowed AMIs.
     * - `image-id` - The ID of the image.
     * - `image-type` - The image type (`machine` | `kernel` | `ramdisk`).
     * - `is-public` - A Boolean that indicates whether the image is public.
     * - `kernel-id` - The kernel ID.
     * - `manifest-location` - The location of the image manifest.
     * - `name` - The name of the AMI (provided during image creation).
     * - `owner-alias` - The owner alias (`amazon` | `aws-backup-vault` | `aws-marketplace`). The valid aliases are defined
     *   in an Amazon-maintained list. This is not the Amazon Web Services account alias that can be set using the IAM
     *   console. We recommend that you use the **Owner** request parameter instead of this filter.
     * - `owner-id` - The Amazon Web Services account ID of the owner. We recommend that you use the **Owner** request
     *   parameter instead of this filter.
     * - `platform` - The platform. The only supported value is `windows`.
     * - `product-code` - The product code.
     * - `product-code.type` - The type of the product code (`marketplace`).
     * - `ramdisk-id` - The RAM disk ID.
     * - `root-device-name` - The device name of the root device volume (for example, `/dev/sda1`).
     * - `root-device-type` - The type of the root device volume (`ebs` | `instance-store`).
     * - `source-image-id` - The ID of the source AMI from which the AMI was created.
     * - `source-image-region` - The Region of the source AMI.
     * - `source-instance-id` - The ID of the instance that the AMI was created from if the AMI was created using
     *   CreateImage. This filter is applicable only if the AMI was created using CreateImage [^1].
     * - `state` - The state of the image (`available` | `pending` | `failed`).
     * - `state-reason-code` - The reason code for the state change.
     * - `state-reason-message` - The message for the state change.
     * - `sriov-net-support` - A value of `simple` indicates that enhanced networking with the Intel 82599 VF interface is
     *   enabled.
     * - `tag:<key>` - The key/value combination of a tag assigned to the resource. Use the tag key in the filter name
     *   and the tag value as the filter value. For example, to find all resources that have a tag with the key `Owner` and
     *   the value `TeamA`, specify `tag:Owner` for the filter name and `TeamA` for the filter value.
     * - `tag-key` - The key of a tag assigned to the resource. Use this filter to find all resources assigned a tag with a
     *   specific key, regardless of the tag value.
     * - `virtualization-type` - The virtualization type (`paravirtual` | `hvm`).
     *
     * [^1]: https://docs.aws.amazon.com/AWSEC2/latest/APIReference/API_CreateImage.html
     *
     * @var Filter[]|null
     */
    private $filters;

    /**
     * @param array{
     *   ExecutableUsers?: string[]|null,
     *   ImageIds?: string[]|null,
     *   Owners?: string[]|null,
     *   IncludeDeprecated?: bool|null,
     *   IncludeDisabled?: bool|null,
     *   MaxResults?: int|null,
     *   NextToken?: string|null,
     *   DryRun?: bool|null,
     *   Filters?: array<Filter|array>|null,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->executableUsers = $input['ExecutableUsers'] ?? null;
        $this->imageIds = $input['ImageIds'] ?? null;
        $this->owners = $input['Owners'] ?? null;
        $this->includeDeprecated = $input['IncludeDeprecated'] ?? null;
        $this->includeDisabled = $input['IncludeDisabled'] ?? null;
        $this->maxResults = $input['MaxResults'] ?? null;
        $this->nextToken = $input['NextToken'] ?? null;
        $this->dryRun = $input['DryRun'] ?? null;
        $this->filters = isset($input['Filters']) ? array_map([Filter::class, 'create'], $input['Filters']) : null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   ExecutableUsers?: string[]|null,
     *   ImageIds?: string[]|null,
     *   Owners?: string[]|null,
     *   IncludeDeprecated?: bool|null,
     *   IncludeDisabled?: bool|null,
     *   MaxResults?: int|null,
     *   NextToken?: string|null,
     *   DryRun?: bool|null,
     *   Filters?: array<Filter|array>|null,
     *   '@region'?: string|null,
     * }|DescribeImagesRequest $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getDryRun(): ?bool
    {
        return $this->dryRun;
    }

    /**
     * @return string[]
     */
    public function getExecutableUsers(): array
    {
        return $this->executableUsers ?? [];
    }

    /**
     * @return Filter[]
     */
    public function getFilters(): array
    {
        return $this->filters ?? [];
    }

    /**
     * @return string[]
     */
    public function getImageIds(): array
    {
        return $this->imageIds ?? [];
    }

    public function getIncludeDeprecated(): ?bool
    {
        return $this->includeDeprecated;
    }

    public function getIncludeDisabled(): ?bool
    {
        return $this->includeDisabled;
    }

    public function getMaxResults(): ?int
    {
        return $this->maxResults;
    }

    public function getNextToken(): ?string
    {
        return $this->nextToken;
    }

    /**
     * @return string[]
     */
    public function getOwners(): array
    {
        return $this->owners ?? [];
    }

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = ['content-type' => 'application/x-www-form-urlencoded'];

        // Prepare query
        $query = [];

        // Prepare URI
        $uriString = '/';

        // Prepare Body
        $body = http_build_query(['Action' => 'DescribeImages', 'Version' => '2016-11-15'] + $this->requestBody(), '', '&', \PHP_QUERY_RFC1738);

        // Return the Request
        return new Request('POST', $uriString, $query, $headers, StreamFactory::create($body));
    }

    public function setDryRun(?bool $value): self
    {
        $this->dryRun = $value;

        return $this;
    }

    /**
     * @param string[] $value
     */
    public function setExecutableUsers(array $value): self
    {
        $this->executableUsers = $value;

        return $this;
    }

    /**
     * @param Filter[] $value
     */
    public function setFilters(array $value): self
    {
        $this->filters = $value;

        return $this;
    }

    /**
     * @param string[] $value
     */
    public function setImageIds(array $value): self
    {
        $this->imageIds = $value;

        return $this;
    }

    public function setIncludeDeprecated(?bool $value): self
    {
        $this->includeDeprecated = $value;

        return $this;
    }

    public function setIncludeDisabled(?bool $value): self
    {
        $this->includeDisabled = $value;

        return $this;
    }

    public function setMaxResults(?int $value): self
    {
        $this->maxResults = $value;

        return $this;
    }

    public function setNextToken(?string $value): self
    {
        $this->nextToken = $value;

        return $this;
    }

    /**
     * @param string[] $value
     */
    public function setOwners(array $value): self
    {
        $this->owners = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->executableUsers) {
            $index = 0;
            foreach ($v as $mapValue) {
                ++$index;
                $payload["ExecutableBy.$index"] = $mapValue;
            }
        }
        if (null !== $v = $this->imageIds) {
            $index = 0;
            foreach ($v as $mapValue) {
                ++$index;
                $payload["ImageId.$index"] = $mapValue;
            }
        }
        if (null !== $v = $this->owners) {
            $index = 0;
            foreach ($v as $mapValue) {
                ++$index;
                $payload["Owner.$index"] = $mapValue;
            }
        }
        if (null !== $v = $this->includeDeprecated) {
            $payload['IncludeDeprecated'] = $v ? 'true' : 'false';
        }
        if (null !== $v = $this->includeDisabled) {
            $payload['IncludeDisabled'] = $v ? 'true' : 'false';
        }
        if (null !== $v = $this->maxResults) {
            $payload['MaxResults'] = $v;
        }
        if (null !== $v = $this->nextToken) {
            $payload['NextToken'] = $v;
        }
        if (null !== $v = $this->dryRun) {
            $payload['DryRun'] = $v ? 'true' : 'false';
        }
        if (null !== $v = $this->filters) {
            $index = 0;
            foreach ($v as $mapValue) {
                ++$index;
                foreach ($mapValue->requestBody() as $bodyKey => $bodyValue) {
                    $payload["Filter.$index.$bodyKey"] = $bodyValue;
                }
            }
        }

        return $payload;
    }
}
