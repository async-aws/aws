<?php

namespace AsyncAws\S3\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\S3\Enum\TransitionDefaultMinimumObjectSize;

class PutBucketLifecycleConfigurationOutput extends Result
{
    /**
     * Indicates which default minimum object size behavior is applied to the lifecycle configuration.
     *
     * > This parameter applies to general purpose buckets only. It is not supported for directory bucket lifecycle
     * > configurations.
     *
     * - `all_storage_classes_128K` - Objects smaller than 128 KB will not transition to any storage class by default.
     * - `varies_by_storage_class` - Objects smaller than 128 KB will transition to Glacier Flexible Retrieval or Glacier
     *   Deep Archive storage classes. By default, all other storage classes will prevent transitions smaller than 128 KB.
     *
     * To customize the minimum object size for any transition you can add a filter that specifies a custom
     * `ObjectSizeGreaterThan` or `ObjectSizeLessThan` in the body of your transition rule. Custom filters always take
     * precedence over the default transition behavior.
     *
     * @var TransitionDefaultMinimumObjectSize::*|null
     */
    private $transitionDefaultMinimumObjectSize;

    /**
     * @return TransitionDefaultMinimumObjectSize::*|null
     */
    public function getTransitionDefaultMinimumObjectSize(): ?string
    {
        $this->initialize();

        return $this->transitionDefaultMinimumObjectSize;
    }

    protected function populateResult(Response $response): void
    {
        $headers = $response->getHeaders();

        $this->transitionDefaultMinimumObjectSize = $headers['x-amz-transition-default-minimum-object-size'][0] ?? null;
    }
}
