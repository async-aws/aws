<?php

namespace AsyncAws\CognitoIdentityProvider\Result;

use AsyncAws\CognitoIdentityProvider\Enum\UserStatusType;
use AsyncAws\CognitoIdentityProvider\ValueObject\AttributeType;
use AsyncAws\CognitoIdentityProvider\ValueObject\MFAOptionType;
use AsyncAws\Core\Response;
use AsyncAws\Core\Result;

class AdminGetUserResponse extends Result
{
    /**
     * The user name of the user about whom you are receiving information.
     */
    private $Username;

    /**
     * An array of name-value pairs representing user attributes.
     */
    private $UserAttributes = [];

    /**
     * The date the user was created.
     */
    private $UserCreateDate;

    /**
     * The date the user was last modified.
     */
    private $UserLastModifiedDate;

    /**
     * Indicates that the status is enabled.
     */
    private $Enabled;

    /**
     * The user status. Can be one of the following:.
     */
    private $UserStatus;

    /**
     * *This response parameter is no longer supported.* It provides information only about SMS MFA configurations. It
     * doesn't provide information about TOTP software token MFA configurations. To look up information about either type of
     * MFA configuration, use the AdminGetUserResponse$UserMFASettingList response instead.
     */
    private $MFAOptions = [];

    /**
     * The user's preferred MFA setting.
     */
    private $PreferredMfaSetting;

    /**
     * The MFA options that are enabled for the user. The possible values in this list are `SMS_MFA` and
     * `SOFTWARE_TOKEN_MFA`.
     */
    private $UserMFASettingList = [];

    public function getEnabled(): ?bool
    {
        $this->initialize();

        return $this->Enabled;
    }

    /**
     * @return MFAOptionType[]
     */
    public function getMFAOptions(): array
    {
        $this->initialize();

        return $this->MFAOptions;
    }

    public function getPreferredMfaSetting(): ?string
    {
        $this->initialize();

        return $this->PreferredMfaSetting;
    }

    /**
     * @return AttributeType[]
     */
    public function getUserAttributes(): array
    {
        $this->initialize();

        return $this->UserAttributes;
    }

    public function getUserCreateDate(): ?\DateTimeImmutable
    {
        $this->initialize();

        return $this->UserCreateDate;
    }

    public function getUserLastModifiedDate(): ?\DateTimeImmutable
    {
        $this->initialize();

        return $this->UserLastModifiedDate;
    }

    /**
     * @return string[]
     */
    public function getUserMFASettingList(): array
    {
        $this->initialize();

        return $this->UserMFASettingList;
    }

    /**
     * @return UserStatusType::*|null
     */
    public function getUserStatus(): ?string
    {
        $this->initialize();

        return $this->UserStatus;
    }

    public function getUsername(): string
    {
        $this->initialize();

        return $this->Username;
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->Username = (string) $data['Username'];
        $this->UserAttributes = empty($data['UserAttributes']) ? [] : $this->populateResultAttributeListType($data['UserAttributes']);
        $this->UserCreateDate = (isset($data['UserCreateDate']) && ($d = \DateTimeImmutable::createFromFormat('U.u', \sprintf('%.6F', $data['UserCreateDate'])))) ? $d : null;
        $this->UserLastModifiedDate = (isset($data['UserLastModifiedDate']) && ($d = \DateTimeImmutable::createFromFormat('U.u', \sprintf('%.6F', $data['UserLastModifiedDate'])))) ? $d : null;
        $this->Enabled = isset($data['Enabled']) ? filter_var($data['Enabled'], \FILTER_VALIDATE_BOOLEAN) : null;
        $this->UserStatus = isset($data['UserStatus']) ? (string) $data['UserStatus'] : null;
        $this->MFAOptions = empty($data['MFAOptions']) ? [] : $this->populateResultMFAOptionListType($data['MFAOptions']);
        $this->PreferredMfaSetting = isset($data['PreferredMfaSetting']) ? (string) $data['PreferredMfaSetting'] : null;
        $this->UserMFASettingList = empty($data['UserMFASettingList']) ? [] : $this->populateResultUserMFASettingListType($data['UserMFASettingList']);
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
