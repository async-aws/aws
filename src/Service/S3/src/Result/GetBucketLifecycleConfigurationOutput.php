<?php

namespace AsyncAws\S3\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\S3\Enum\ExpirationStatus;
use AsyncAws\S3\Enum\TransitionDefaultMinimumObjectSize;
use AsyncAws\S3\Enum\TransitionStorageClass;
use AsyncAws\S3\ValueObject\AbortIncompleteMultipartUpload;
use AsyncAws\S3\ValueObject\LifecycleExpiration;
use AsyncAws\S3\ValueObject\LifecycleRule;
use AsyncAws\S3\ValueObject\LifecycleRuleAndOperator;
use AsyncAws\S3\ValueObject\LifecycleRuleFilter;
use AsyncAws\S3\ValueObject\NoncurrentVersionExpiration;
use AsyncAws\S3\ValueObject\NoncurrentVersionTransition;
use AsyncAws\S3\ValueObject\Tag;
use AsyncAws\S3\ValueObject\Transition;

class GetBucketLifecycleConfigurationOutput extends Result
{
    /**
     * Container for a lifecycle rule.
     *
     * @var LifecycleRule[]
     */
    private $rules;

    /**
     * Indicates which default minimum object size behavior is applied to the lifecycle configuration.
     *
     * > This parameter applies to general purpose buckets only. It isn't supported for directory bucket lifecycle
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
     * @return LifecycleRule[]
     */
    public function getRules(): array
    {
        $this->initialize();

        return $this->rules;
    }

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

        $data = new \SimpleXMLElement($response->getContent());
        $this->rules = (0 === ($v = $data->Rule)->count()) ? [] : $this->populateResultLifecycleRules($v);
    }

    private function populateResultAbortIncompleteMultipartUpload(\SimpleXMLElement $xml): AbortIncompleteMultipartUpload
    {
        return new AbortIncompleteMultipartUpload([
            'DaysAfterInitiation' => (null !== $v = $xml->DaysAfterInitiation[0]) ? (int) (string) $v : null,
        ]);
    }

    private function populateResultLifecycleExpiration(\SimpleXMLElement $xml): LifecycleExpiration
    {
        return new LifecycleExpiration([
            'Date' => (null !== $v = $xml->Date[0]) ? new \DateTimeImmutable((string) $v) : null,
            'Days' => (null !== $v = $xml->Days[0]) ? (int) (string) $v : null,
            'ExpiredObjectDeleteMarker' => (null !== $v = $xml->ExpiredObjectDeleteMarker[0]) ? filter_var((string) $v, \FILTER_VALIDATE_BOOLEAN) : null,
        ]);
    }

    private function populateResultLifecycleRule(\SimpleXMLElement $xml): LifecycleRule
    {
        return new LifecycleRule([
            'Expiration' => 0 === $xml->Expiration->count() ? null : $this->populateResultLifecycleExpiration($xml->Expiration),
            'ID' => (null !== $v = $xml->ID[0]) ? (string) $v : null,
            'Prefix' => (null !== $v = $xml->Prefix[0]) ? (string) $v : null,
            'Filter' => 0 === $xml->Filter->count() ? null : $this->populateResultLifecycleRuleFilter($xml->Filter),
            'Status' => !ExpirationStatus::exists((string) $xml->Status) ? ExpirationStatus::UNKNOWN_TO_SDK : (string) $xml->Status,
            'Transitions' => (0 === ($v = $xml->Transition)->count()) ? null : $this->populateResultTransitionList($v),
            'NoncurrentVersionTransitions' => (0 === ($v = $xml->NoncurrentVersionTransition)->count()) ? null : $this->populateResultNoncurrentVersionTransitionList($v),
            'NoncurrentVersionExpiration' => 0 === $xml->NoncurrentVersionExpiration->count() ? null : $this->populateResultNoncurrentVersionExpiration($xml->NoncurrentVersionExpiration),
            'AbortIncompleteMultipartUpload' => 0 === $xml->AbortIncompleteMultipartUpload->count() ? null : $this->populateResultAbortIncompleteMultipartUpload($xml->AbortIncompleteMultipartUpload),
        ]);
    }

    private function populateResultLifecycleRuleAndOperator(\SimpleXMLElement $xml): LifecycleRuleAndOperator
    {
        return new LifecycleRuleAndOperator([
            'Prefix' => (null !== $v = $xml->Prefix[0]) ? (string) $v : null,
            'Tags' => (0 === ($v = $xml->Tag)->count()) ? null : $this->populateResultTagSet($v),
            'ObjectSizeGreaterThan' => (null !== $v = $xml->ObjectSizeGreaterThan[0]) ? (int) (string) $v : null,
            'ObjectSizeLessThan' => (null !== $v = $xml->ObjectSizeLessThan[0]) ? (int) (string) $v : null,
        ]);
    }

    private function populateResultLifecycleRuleFilter(\SimpleXMLElement $xml): LifecycleRuleFilter
    {
        return new LifecycleRuleFilter([
            'Prefix' => (null !== $v = $xml->Prefix[0]) ? (string) $v : null,
            'Tag' => 0 === $xml->Tag->count() ? null : $this->populateResultTag($xml->Tag),
            'ObjectSizeGreaterThan' => (null !== $v = $xml->ObjectSizeGreaterThan[0]) ? (int) (string) $v : null,
            'ObjectSizeLessThan' => (null !== $v = $xml->ObjectSizeLessThan[0]) ? (int) (string) $v : null,
            'And' => 0 === $xml->And->count() ? null : $this->populateResultLifecycleRuleAndOperator($xml->And),
        ]);
    }

    /**
     * @return LifecycleRule[]
     */
    private function populateResultLifecycleRules(\SimpleXMLElement $xml): array
    {
        $items = [];
        foreach ($xml as $item) {
            $items[] = $this->populateResultLifecycleRule($item);
        }

        return $items;
    }

    private function populateResultNoncurrentVersionExpiration(\SimpleXMLElement $xml): NoncurrentVersionExpiration
    {
        return new NoncurrentVersionExpiration([
            'NoncurrentDays' => (null !== $v = $xml->NoncurrentDays[0]) ? (int) (string) $v : null,
            'NewerNoncurrentVersions' => (null !== $v = $xml->NewerNoncurrentVersions[0]) ? (int) (string) $v : null,
        ]);
    }

    private function populateResultNoncurrentVersionTransition(\SimpleXMLElement $xml): NoncurrentVersionTransition
    {
        return new NoncurrentVersionTransition([
            'NoncurrentDays' => (null !== $v = $xml->NoncurrentDays[0]) ? (int) (string) $v : null,
            'StorageClass' => (null !== $v = $xml->StorageClass[0]) ? (!TransitionStorageClass::exists((string) $xml->StorageClass) ? TransitionStorageClass::UNKNOWN_TO_SDK : (string) $xml->StorageClass) : null,
            'NewerNoncurrentVersions' => (null !== $v = $xml->NewerNoncurrentVersions[0]) ? (int) (string) $v : null,
        ]);
    }

    /**
     * @return NoncurrentVersionTransition[]
     */
    private function populateResultNoncurrentVersionTransitionList(\SimpleXMLElement $xml): array
    {
        $items = [];
        foreach ($xml as $item) {
            $items[] = $this->populateResultNoncurrentVersionTransition($item);
        }

        return $items;
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
    private function populateResultTagSet(\SimpleXMLElement $xml): array
    {
        $items = [];
        foreach ($xml->Tag as $item) {
            $items[] = $this->populateResultTag($item);
        }

        return $items;
    }

    private function populateResultTransition(\SimpleXMLElement $xml): Transition
    {
        return new Transition([
            'Date' => (null !== $v = $xml->Date[0]) ? new \DateTimeImmutable((string) $v) : null,
            'Days' => (null !== $v = $xml->Days[0]) ? (int) (string) $v : null,
            'StorageClass' => (null !== $v = $xml->StorageClass[0]) ? (!TransitionStorageClass::exists((string) $xml->StorageClass) ? TransitionStorageClass::UNKNOWN_TO_SDK : (string) $xml->StorageClass) : null,
        ]);
    }

    /**
     * @return Transition[]
     */
    private function populateResultTransitionList(\SimpleXMLElement $xml): array
    {
        $items = [];
        foreach ($xml as $item) {
            $items[] = $this->populateResultTransition($item);
        }

        return $items;
    }
}
