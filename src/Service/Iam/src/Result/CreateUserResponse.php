<?php

namespace AsyncAws\Iam\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\Iam\ValueObject\AttachedPermissionsBoundary;
use AsyncAws\Iam\ValueObject\Tag;
use AsyncAws\Iam\ValueObject\User;

/**
 * Contains the response to a successful CreateUser request.
 */
class CreateUserResponse extends Result
{
    /**
     * A structure with details about the new IAM user.
     */
    private $user;

    public function getUser(): ?User
    {
        $this->initialize();

        return $this->user;
    }

    protected function populateResult(Response $response): void
    {
        $data = new \SimpleXMLElement($response->getContent());
        $data = $data->CreateUserResult;

        $this->user = !$data->User ? null : new User([
            'Path' => (string) $data->User->Path,
            'UserName' => (string) $data->User->UserName,
            'UserId' => (string) $data->User->UserId,
            'Arn' => (string) $data->User->Arn,
            'CreateDate' => new \DateTimeImmutable((string) $data->User->CreateDate),
            'PasswordLastUsed' => ($v = $data->User->PasswordLastUsed) ? new \DateTimeImmutable((string) $v) : null,
            'PermissionsBoundary' => !$data->User->PermissionsBoundary ? null : new AttachedPermissionsBoundary([
                'PermissionsBoundaryType' => ($v = $data->User->PermissionsBoundary->PermissionsBoundaryType) ? (string) $v : null,
                'PermissionsBoundaryArn' => ($v = $data->User->PermissionsBoundary->PermissionsBoundaryArn) ? (string) $v : null,
            ]),
            'Tags' => !$data->User->Tags ? [] : $this->populateResultTagListType($data->User->Tags),
        ]);
    }

    /**
     * @return Tag[]
     */
    private function populateResultTagListType(\SimpleXMLElement $xml): array
    {
        $items = [];
        foreach ($xml->member as $item) {
            $items[] = new Tag([
                'Key' => (string) $item->Key,
                'Value' => (string) $item->Value,
            ]);
        }

        return $items;
    }
}
