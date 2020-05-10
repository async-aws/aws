<?php

namespace AsyncAws\Iam;

use AsyncAws\Core\AbstractApi;

class IamClient extends AbstractApi
{
    protected function getServiceCode(): string
    {
        return 'iam';
    }

    protected function getSignatureScopeName(): string
    {
        return 'iam';
    }

    protected function getSignatureVersion(): string
    {
        return 'v4';
    }
}
