<?php

namespace AsyncAws\Foobar;

use AsyncAws\Core\AbstractApi;

class FoobarClient extends AbstractApi
{

    protected function getServiceCode(): string
    {
        return '';
    }

    protected function getSignatureScopeName(): string
    {
        return '';
    }

    protected function getSignatureVersion(): string
    {
        return '';
    }
}
