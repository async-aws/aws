<?php

namespace AsyncAws\MediaConvert;

use AsyncAws\Core\AbstractApi;
use AsyncAws\Core\Exception\UnsupportedRegion;

class MediaConvertClient extends AbstractApi
{
    protected function getEndpointMetadata(?string $region): array
    {
        throw new UnsupportedRegion('The region is not supported.');
    }
}
