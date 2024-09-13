<?php

namespace AsyncAws\CognitoIdentityProvider\Result;

use AsyncAws\CognitoIdentityProvider\ValueObject\AttributeType;
use AsyncAws\CognitoIdentityProvider\ValueObject\MFAOptionType;
use AsyncAws\Core\Response;
use AsyncAws\Core\Result;

/**
 * Represents the response from the server from the request to get information about the user.
 */
class GetUserResponse extends Result
{
    /**
     * The username of the user that you requested.
     *
     * @var string
     */
    private $username;

    /**
     * An array of name-value pairs representing user attributes.
     *
     * For custom attributes, you must prepend the `custom:` prefix to the attribute name.
     *
     * @var AttributeType[]
     */
    private $userAttributes;

    /**
     * *This response parameter is no longer supported.* It provides information only about SMS MFA configurations. It
     * doesn't provide information about time-based one-time password (TOTP) software token MFA configurations. To look up
     * information about either type of MFA configuration, use UserMFASettingList instead.
     *
     * @var MFAOptionType[]
     */
    private $mfaOptions;

    /**
     * The user's preferred MFA setting.
     *
     * @var string|null
     */
    private $preferredMfaSetting;

    /**
     * The MFA options that are activated for the user. The possible values in this list are `SMS_MFA`, `EMAIL_OTP`, and
     * `SOFTWARE_TOKEN_MFA`.
     *
     * @var string[]
     */
    private $userMfaSettingList;

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

    /**
     * @return string[]
     */
    public function getUserMfaSettingList(): array
    {
        $this->initialize();

        return $this->userMfaSettingList;
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
        $this->userAttributes = $this->populateResultAttributeListType($data['UserAttributes'] ?? []);
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
            $items[] = $this->populateResultAttributeType($item);
        }

        return $items;
    }

    private function populateResultAttributeType(array $json): AttributeType
    {
        return new AttributeType([
            'Name' => (string) $json['Name'],
            'Value' => isset($json['Value']) ? (string) $json['Value'] : null,
        ]);
    }

    /**
     * @return MFAOptionType[]
     */
    private function populateResultMFAOptionListType(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = $this->populateResultMFAOptionType($item);
        }

        return $items;
    }

    private function populateResultMFAOptionType(array $json): MFAOptionType
    {
        return new MFAOptionType([
            'DeliveryMedium' => isset($json['DeliveryMedium']) ? (string) $json['DeliveryMedium'] : null,
            'AttributeName' => isset($json['AttributeName']) ? (string) $json['AttributeName'] : null,
        ]);
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
