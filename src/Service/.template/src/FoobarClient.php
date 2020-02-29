<?php

namespace AsyncAws\Foobar;

use AsyncAws\Core\AbstractApi;

class FoobarClient extends AbstractApi
{

    protected function getServiceCode(): string
    {
        return 'foo';
    }

    protected function getSignatureScopeName(): string
    {
        return 'bar';
    }

    protected function getSignatureVersion(): string
    {
        return 'baz';
    }
}
