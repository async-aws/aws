<?php

namespace AsyncAws\Sso\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\Sso\ValueObject\RoleCredentials;

class GetRoleCredentialsResponse extends Result
{
    /**
     * The credentials for the role that is assigned to the user.
     *
     * @var RoleCredentials|null
     */
    private $roleCredentials;

    public function getRoleCredentials(): ?RoleCredentials
    {
        $this->initialize();

        return $this->roleCredentials;
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->roleCredentials = empty($data['roleCredentials']) ? null : $this->populateResultRoleCredentials($data['roleCredentials']);
    }

    private function populateResultRoleCredentials(array $json): RoleCredentials
    {
        return new RoleCredentials([
            'accessKeyId' => isset($json['accessKeyId']) ? (string) $json['accessKeyId'] : null,
            'secretAccessKey' => isset($json['secretAccessKey']) ? (string) $json['secretAccessKey'] : null,
            'sessionToken' => isset($json['sessionToken']) ? (string) $json['sessionToken'] : null,
            'expiration' => isset($json['expiration']) ? (int) $json['expiration'] : null,
        ]);
    }
}
