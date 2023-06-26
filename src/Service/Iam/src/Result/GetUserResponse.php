<?php

namespace AsyncAws\Iam\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\Iam\ValueObject\AttachedPermissionsBoundary;
use AsyncAws\Iam\ValueObject\Tag;
use AsyncAws\Iam\ValueObject\User;

/**
 * Contains the response to a successful GetUser request.
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

        $this->user = new User([
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
            'Tags' => !$data->User->Tags ? null : $this->populateResultTagListType($data->User->Tags),
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
