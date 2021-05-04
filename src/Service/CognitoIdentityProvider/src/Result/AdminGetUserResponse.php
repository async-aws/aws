<?php

namespace AsyncAws\CognitoIdentityProvider\Result;

use AsyncAws\CognitoIdentityProvider\Enum\UserStatusType;
use AsyncAws\CognitoIdentityProvider\ValueObject\AttributeType;
use AsyncAws\CognitoIdentityProvider\ValueObject\MFAOptionType;
use AsyncAws\Core\Response;
use AsyncAws\Core\Result;

/**
 * Represents the response from the server from the request to get the specified user as an administrator.
 */
class AdminGetUserResponse extends Result
{
    /**
     * The user name of the user about whom you are receiving information.
     */
    private $username;

    /**
     * An array of name-value pairs representing user attributes.
     */
    private $userAttributes = [];

    /**
     * The date the user was created.
     */
    private $userCreateDate;

    /**
     * The date the user was last modified.
     */
    private $userLastModifiedDate;

    /**
     * Indicates that the status is enabled.
     */
    private $enabled;

    /**
     * The user status. Can be one of the following:.
     */
    private $userStatus;

    /**
     * *This response parameter is no longer supported.* It provides information only about SMS MFA configurations. It
     * doesn't provide information about TOTP software token MFA configurations. To look up information about either type of
     * MFA configuration, use UserMFASettingList instead.
     */
    private $mfaOptions = [];

    /**
     * The user's preferred MFA setting.
     */
    private $preferredMfaSetting;

    /**
     * The MFA options that are enabled for the user. The possible values in this list are `SMS_MFA` and
     * `SOFTWARE_TOKEN_MFA`.
     */
    private $userMfaSettingList = [];

    public function getEnabled(): ?bool
    {
        $this->initialize();

        return $this->enabled;
    }

    /**
     * @return MFAOptionType[]
     */
    public function getMfaOptions(): array
    {
        $this->initialize();

        return $this->mfaOptions;
    }

    public function getPreferredMfaSetting(): ?string
    {
        $this->initialize();

        return $this->preferredMfaSetting;
    }

    /**
     * @return AttributeType[]
     */
    public function getUserAttributes(): array
    {
        $this->initialize();

        return $this->userAttributes;
    }

    public function getUserCreateDate(): ?\DateTimeImmutable
    {
        $this->initialize();

        return $this->userCreateDate;
    }

    public function getUserLastModifiedDate(): ?\DateTimeImmutable
    {
        $this->initialize();

        return $this->userLastModifiedDate;
    }

    /**
     * @return string[]
     */
    public function getUserMfaSettingList(): array
    {
        $this->initialize();

        return $this->userMfaSettingList;
    }

    /**
     * @return UserStatusType::*|null
     */
    public function getUserStatus(): ?string
    {
        $this->initialize();

        return $this->userStatus;
    }

    public function getUsername(): string
    {
        $this->initialize();

        return $this->username;
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->username = (string) $data['Username'];
        $this->userAttributes = empty($data['UserAttributes']) ? [] : $this->populateResultAttributeListType($data['UserAttributes']);
        $this->userCreateDate = (isset($data['UserCreateDate']) && ($d = \DateTimeImmutable::createFromFormat('U.u', sprintf('%.6F', $data['UserCreateDate'])))) ? $d : null;
        $this->userLastModifiedDate = (isset($data['UserLastModifiedDate']) && ($d = \DateTimeImmutable::createFromFormat('U.u', sprintf('%.6F', $data['UserLastModifiedDate'])))) ? $d : null;
        $this->enabled = isset($data['Enabled']) ? filter_var($data['Enabled'], \FILTER_VALIDATE_BOOLEAN) : null;
        $this->userStatus = isset($data['UserStatus']) ? (string) $data['UserStatus'] : null;
        $this->mfaOptions = empty($data['MFAOptions']) ? [] : $this->populateResultMFAOptionListType($data['MFAOptions']);
        $this->preferredMfaSetting = isset($data['PreferredMfaSetting']) ? (string) $data['PreferredMfaSetting'] : null;
        $this->userMfaSettingList = empty($data['UserMFASettingList']) ? [] : $this->populateResultUserMFASettingListType($data['UserMFASettingList']);
    }

    /**
     * @return AttributeType[]
     */
    private function populateResultAttributeListType(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = new AttributeType([
                'Name' => (string) $item['Name'],
                'Value' => isset($item['Value']) ? (string) $item['Value'] : null,
            ]);
        }

        return $items;
    }

    /**
     * @return MFAOptionType[]
     */
    private function populateResultMFAOptionListType(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = new MFAOptionType([
                'DeliveryMedium' => isset($item['DeliveryMedium']) ? (string) $item['DeliveryMedium'] : null,
                'AttributeName' => isset($item['AttributeName']) ? (string) $item['AttributeName'] : null,
            ]);
        }

        return $items;
    }

    /**
     * @return string[]
     */
    private function populateResultUserMFASettingListType(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $a = isset($item) ? (string) $item : null;
            if (null !== $a) {
                $items[] = $a;
            }
        }

        return $items;
    }
}
