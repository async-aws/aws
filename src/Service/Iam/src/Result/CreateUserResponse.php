<?php

namespace AsyncAws\Iam\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\Iam\Enum\PermissionsBoundaryAttachmentType;
use AsyncAws\Iam\ValueObject\AttachedPermissionsBoundary;
use AsyncAws\Iam\ValueObject\Tag;
use AsyncAws\Iam\ValueObject\User;

/**
 * Contains the response to a successful CreateUser [^1] request.
 *
 * [^1]: https://docs.aws.amazon.com/IAM/latest/APIReference/API_CreateUser.html
 */
class CreateUserResponse extends Result
{
    /**
     * A structure with details about the new IAM user.
     *
     * @var User|null
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

        $this->user = 0 === $data->User->count() ? null : $this->populateResultUser($data->User);
    }

    private function populateResultAttachedPermissionsBoundary(\SimpleXMLElement $xml): AttachedPermissionsBoundary
    {
        return new AttachedPermissionsBoundary([
            'PermissionsBoundaryType' => (null !== $v = $xml->PermissionsBoundaryType[0]) ? (!PermissionsBoundaryAttachmentType::exists((string) $xml->PermissionsBoundaryType) ? PermissionsBoundaryAttachmentType::UNKNOWN_TO_SDK : (string) $xml->PermissionsBoundaryType) : null,
            'PermissionsBoundaryArn' => (null !== $v = $xml->PermissionsBoundaryArn[0]) ? (string) $v : null,
        ]);
    }

    private function populateResultTag(\SimpleXMLElement $xml): Tag
    {
        return new Tag([
            'Key' => (string) $xml->Key,
            'Value' => (string) $xml->Value,
        ]);
    }

    /**
     * @return Tag[]
     */
    private function populateResultTagListType(\SimpleXMLElement $xml): array
    {
        $items = [];
        foreach ($xml->member as $item) {
            $items[] = $this->populateResultTag($item);
        }

        return $items;
    }

    private function populateResultUser(\SimpleXMLElement $xml): User
    {
        return new User([
            'Path' => (string) $xml->Path,
            'UserName' => (string) $xml->UserName,
            'UserId' => (string) $xml->UserId,
            'Arn' => (string) $xml->Arn,
            'CreateDate' => new \DateTimeImmutable((string) $xml->CreateDate),
            'PasswordLastUsed' => (null !== $v = $xml->PasswordLastUsed[0]) ? new \DateTimeImmutable((string) $v) : null,
            'PermissionsBoundary' => 0 === $xml->PermissionsBoundary->count() ? null : $this->populateResultAttachedPermissionsBoundary($xml->PermissionsBoundary),
            'Tags' => (0 === ($v = $xml->Tags)->count()) ? null : $this->populateResultTagListType($v),
        ]);
    }
}
