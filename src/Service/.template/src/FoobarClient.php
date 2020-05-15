<?php

namespace AsyncAws\Foobar;

use AsyncAws\Core\AbstractApi;
use AsyncAws\Core\Exception\UnsupportedRegion;

class FoobarClient extends AbstractApi
{
    protected function getEndpointMetadata(?string $region): array
    {
        throw new UnsupportedRegion('The region is not supported.');
    }
}
