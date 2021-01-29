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

        $this->user = empty($data['User']) ? null : new UserType([
            'Username' => isset($data['User']['Username']) ? (string) $data['User']['Username'] : null,
            'Attributes' => empty($data['User']['Attributes']) ? [] : $this->populateResultAttributeListType($data['User']['Attributes']),
            'UserCreateDate' => (isset($data['User']['UserCreateDate']) && ($d = \DateTimeImmutable::createFromFormat('U.u', \sprintf('%.6F', $data['User']['UserCreateDate'])))) ? $d : null,
            'UserLastModifiedDate' => (isset($data['User']['UserLastModifiedDate']) && ($d = \DateTimeImmutable::createFromFormat('U.u', \sprintf('%.6F', $data['User']['UserLastModifiedDate'])))) ? $d : null,
            'Enabled' => isset($data['User']['Enabled']) ? filter_var($data['User']['Enabled'], \FILTER_VALIDATE_BOOLEAN) : null,
            'UserStatus' => isset($data['User']['UserStatus']) ? (string) $data['User']['UserStatus'] : null,
            'MFAOptions' => empty($data['User']['MFAOptions']) ? [] : $this->populateResultMFAOptionListType($data['User']['MFAOptions']),
        ]);
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
}
