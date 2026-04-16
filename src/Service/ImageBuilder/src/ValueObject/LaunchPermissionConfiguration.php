<?php

namespace AsyncAws\ImageBuilder\ValueObject;

/**
 * Describes the configuration for a launch permission. The launch permission modification request is sent to the Amazon
 * EC2 ModifyImageAttribute [^1] API on behalf of the user for each Region they have selected to distribute the AMI. To
 * make an AMI public, set the launch permission authorized accounts to `all`. See the examples for making an AMI public
 * at Amazon EC2 ModifyImageAttribute [^2].
 *
 * [^1]: https://docs.aws.amazon.com/AWSEC2/latest/APIReference/API_ModifyImageAttribute.html
 * [^2]: https://docs.aws.amazon.com/AWSEC2/latest/APIReference/API_ModifyImageAttribute.html
 */
final class LaunchPermissionConfiguration
{
    /**
     * The Amazon Web Services account ID.
     *
     * @var string[]|null
     */
    private $userIds;

    /**
     * The name of the group.
     *
     * @var string[]|null
     */
    private $userGroups;

    /**
     * The ARN for an Amazon Web Services Organization that you want to share your AMI with. For more information, see What
     * is Organizations? [^1].
     *
     * [^1]: https://docs.aws.amazon.com/organizations/latest/userguide/orgs_introduction.html
     *
     * @var string[]|null
     */
    private $organizationArns;

    /**
     * The ARN for an Organizations organizational unit (OU) that you want to share your AMI with. For more information
     * about key concepts for Organizations, see Organizations terminology and concepts [^1].
     *
     * [^1]: https://docs.aws.amazon.com/organizations/latest/userguide/orgs_getting-started_concepts.html
     *
     * @var string[]|null
     */
    private $organizationalUnitArns;

    /**
     * @param array{
     *   userIds?: string[]|null,
     *   userGroups?: string[]|null,
     *   organizationArns?: string[]|null,
     *   organizationalUnitArns?: string[]|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->userIds = $input['userIds'] ?? null;
        $this->userGroups = $input['userGroups'] ?? null;
        $this->organizationArns = $input['organizationArns'] ?? null;
        $this->organizationalUnitArns = $input['organizationalUnitArns'] ?? null;
    }

    /**
     * @param array{
     *   userIds?: string[]|null,
     *   userGroups?: string[]|null,
     *   organizationArns?: string[]|null,
     *   organizationalUnitArns?: string[]|null,
     * }|LaunchPermissionConfiguration $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return string[]
     */
    public function getOrganizationArns(): array
    {
        return $this->organizationArns ?? [];
    }

    /**
     * @return string[]
     */
    public function getOrganizationalUnitArns(): array
    {
        return $this->organizationalUnitArns ?? [];
    }

    /**
     * @return string[]
     */
    public function getUserGroups(): array
    {
        return $this->userGroups ?? [];
    }

    /**
     * @return string[]
     */
    public function getUserIds(): array
    {
        return $this->userIds ?? [];
    }
}
