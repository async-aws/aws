<?php

namespace AsyncAws\Iam\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\Iam\ValueObject\AttachedPermissionsBoundary;
use AsyncAws\Iam\ValueObject\Tag;
use AsyncAws\Iam\ValueObject\User;

class CreateUserResponse extends Result
{
    /**
     * A structure with details about the new IAM user.
     */
    private $User;

    public function getUser(): ?User
    {
        $this->initialize();

        return $this->User;
    }

    protected function populateResult(Response $response): void
    {
        $data = new \SimpleXMLElement($response->getContent());
        $data = $data->CreateUserResult;

        $this->User = !$data->User ? null : new User([
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
            'Tags' => !$data->User->Tags ? [] : (function (\SimpleXMLElement $xml): array {
                $items = [];
                foreach ($xml->member as $item) {
                    $items[] = new Tag([
                        'Key' => (string) $item->Key,
                        'Value' => (string) $item->Value,
                    ]);
                }

                return $items;
            })($data->User->Tags),
        ]);
    }
}
