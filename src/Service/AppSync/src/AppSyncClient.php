<?php

namespace AsyncAws\AppSync;

use AsyncAws\Core\AbstractApi;
use AsyncAws\Core\Exception\UnsupportedRegion;

class AppSyncClient extends AbstractApi
{
    protected function getEndpointMetadata(?string $region): array
    {
        throw new UnsupportedRegion('The region is not supported.');
    }
}
