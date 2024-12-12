<?php

namespace AsyncAws\CognitoIdentityProvider\Result;

use AsyncAws\CognitoIdentityProvider\ValueObject\GroupType;
use AsyncAws\Core\Response;
use AsyncAws\Core\Result;

class CreateGroupResponse extends Result
{
    /**
     * The response object for a created group.
     *
     * @var GroupType|null
     */
    private $group;

    public function getGroup(): ?GroupType
    {
        $this->initialize();

        return $this->group;
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->group = empty($data['Group']) ? null : $this->populateResultGroupType($data['Group']);
    }

    private function populateResultGroupType(array $json): GroupType
    {
        return new GroupType([
            'GroupName' => isset($json['GroupName']) ? (string) $json['GroupName'] : null,
            'UserPoolId' => isset($json['UserPoolId']) ? (string) $json['UserPoolId'] : null,
            'Description' => isset($json['Description']) ? (string) $json['Description'] : null,
            'RoleArn' => isset($json['RoleArn']) ? (string) $json['RoleArn'] : null,
            'Precedence' => isset($json['Precedence']) ? (int) $json['Precedence'] : null,
            'LastModifiedDate' => (isset($json['LastModifiedDate']) && ($d = \DateTimeImmutable::createFromFormat('U.u', \sprintf('%.6F', $json['LastModifiedDate'])))) ? $d : null,
            'CreationDate' => (isset($json['CreationDate']) && ($d = \DateTimeImmutable::createFromFormat('U.u', \sprintf('%.6F', $json['CreationDate'])))) ? $d : null,
        ]);
    }
}
