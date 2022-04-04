<?php

namespace AsyncAws\CognitoIdentityProvider\Result;

use AsyncAws\CognitoIdentityProvider\ValueObject\AttributeType;
use AsyncAws\CognitoIdentityProvider\ValueObject\MFAOptionType;
use AsyncAws\CognitoIdentityProvider\ValueObject\UserType;
use AsyncAws\Core\Response;
use AsyncAws\Core\Result;

/**
 * Represents the response from the server to the request to create the user.
 */
class AdminCreateUserResponse extends Result
{
    /**
     * The newly created user.
     */
    private $user;

    public function getUser(): ?UserType
    {
        $this->initialize();

        return $this->user;
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->user = empty($data['User']) ? null : $this->populateResultUserType($data['User']);
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

    private function populateResultUserType(array $json): UserType
    {
        return new UserType([
            'Username' => isset($json['Username']) ? (string) $json['Username'] : null,
            'Attributes' => !isset($json['Attributes']) ? null : $this->populateResultAttributeListType($json['Attributes']),
            'UserCreateDate' => (isset($json['UserCreateDate']) && ($d = \DateTimeImmutable::createFromFormat('U.u', sprintf('%.6F', $json['UserCreateDate'])))) ? $d : null,
            'UserLastModifiedDate' => (isset($json['UserLastModifiedDate']) && ($d = \DateTimeImmutable::createFromFormat('U.u', sprintf('%.6F', $json['UserLastModifiedDate'])))) ? $d : null,
            'Enabled' => isset($json['Enabled']) ? filter_var($json['Enabled'], \FILTER_VALIDATE_BOOLEAN) : null,
            'UserStatus' => isset($json['UserStatus']) ? (string) $json['UserStatus'] : null,
            'MFAOptions' => !isset($json['MFAOptions']) ? null : $this->populateResultMFAOptionListType($json['MFAOptions']),
        ]);
    }
}
