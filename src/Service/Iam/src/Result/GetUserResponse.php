<?php

namespace AsyncAws\Iam\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\Iam\Enum\PermissionsBoundaryAttachmentType;
use AsyncAws\Iam\ValueObject\AttachedPermissionsBoundary;
use AsyncAws\Iam\ValueObject\Tag;
use AsyncAws\Iam\ValueObject\User;

/**
 * Contains the response to a successful GetUser [^1] request.
 *
 * [^1]: https://docs.aws.amazon.com/IAM/latest/APIReference/API_GetUser.html
 */
class GetUserResponse extends Result
{
    /**
     * A structure containing details about the IAM user.
     *
     * ! Due to a service issue, password last used data does not include password use from May 3, 2018 22:50 PDT to May 23,
     * ! 2018 14:08 PDT. This affects last sign-in [^1] dates shown in the IAM console and password last used dates in the
     * ! IAM credential report [^2], and returned by this operation. If users signed in during the affected time, the
     * ! password last used date that is returned is the date the user last signed in before May 3, 2018. For users that
     * ! signed in after May 23, 2018 14:08 PDT, the returned password last used date is accurate.
     * !
     * ! You can use password last used information to identify unused credentials for deletion. For example, you might
     * ! delete users who did not sign in to Amazon Web Services in the last 90 days. In cases like this, we recommend that
     * ! you adjust your evaluation window to include dates after May 23, 2018. Alternatively, if your users use access keys
     * ! to access Amazon Web Services programmatically you can refer to access key last used information because it is
     * ! accurate for all dates.
     *
     * [^1]: https://docs.aws.amazon.com/IAM/latest/UserGuide/id_credentials_finding-unused.html
     * [^2]: https://docs.aws.amazon.com/IAM/latest/UserGuide/id_credentials_getting-report.html
     *
     * @var User
     */
    private $user;

    public function getUser(): User
    {
        $this->initialize();

        return $this->user;
    }

    protected function populateResult(Response $response): void
    {
        $data = new \SimpleXMLElement($response->getContent());
        $data = $data->GetUserResult;

        $this->user = $this->populateResultUser($data->User);
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
