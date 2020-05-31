<?php

namespace AsyncAws\CognitoIdentityProvider\Result;

use AsyncAws\CognitoIdentityProvider\ValueObject\AttributeType;
use AsyncAws\CognitoIdentityProvider\ValueObject\MFAOptionType;
use AsyncAws\CognitoIdentityProvider\ValueObject\UserType;
use AsyncAws\Core\Response;
use AsyncAws\Core\Result;

class AdminCreateUserResponse extends Result
{
    /**
     * The newly created user.
     */
    private $User;

    public function getUser(): ?UserType
    {
        $this->initialize();

        return $this->User;
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();
        $fn = [];
        $fn['list-AttributeListType'] = static function (array $json) use (&$fn): array {
            $items = [];
            foreach ($json as $item) {
                $items[] = new AttributeType([
                    'Name' => (string) $item['Name'],
                    'Value' => isset($item['Value']) ? (string) $item['Value'] : null,
                ]);
            }

            return $items;
        };
        $fn['list-MFAOptionListType'] = static function (array $json) use (&$fn): array {
            $items = [];
            foreach ($json as $item) {
                $items[] = new MFAOptionType([
                    'DeliveryMedium' => isset($item['DeliveryMedium']) ? (string) $item['DeliveryMedium'] : null,
                    'AttributeName' => isset($item['AttributeName']) ? (string) $item['AttributeName'] : null,
                ]);
            }

            return $items;
        };
        $this->User = empty($data['User']) ? null : new UserType([
            'Username' => isset($data['User']['Username']) ? (string) $data['User']['Username'] : null,
            'Attributes' => empty($data['User']['Attributes']) ? [] : $fn['list-AttributeListType']($data['User']['Attributes']),
            'UserCreateDate' => (isset($data['User']['UserCreateDate']) && ($d = \DateTimeImmutable::createFromFormat('U.u', \sprintf('%.6F', $data['User']['UserCreateDate'])))) ? $d : null,
            'UserLastModifiedDate' => (isset($data['User']['UserLastModifiedDate']) && ($d = \DateTimeImmutable::createFromFormat('U.u', \sprintf('%.6F', $data['User']['UserLastModifiedDate'])))) ? $d : null,
            'Enabled' => isset($data['User']['Enabled']) ? filter_var($data['User']['Enabled'], \FILTER_VALIDATE_BOOLEAN) : null,
            'UserStatus' => isset($data['User']['UserStatus']) ? (string) $data['User']['UserStatus'] : null,
            'MFAOptions' => empty($data['User']['MFAOptions']) ? [] : $fn['list-MFAOptionListType']($data['User']['MFAOptions']),
        ]);
    }
}
