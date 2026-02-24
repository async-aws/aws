<?php

namespace AsyncAws\S3\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\S3\Enum\ExpirationStatus;
use AsyncAws\S3\Enum\TransitionStorageClass;
use AsyncAws\S3\Input\PutBucketLifecycleConfigurationRequest;
use AsyncAws\S3\ValueObject\BucketLifecycleConfiguration;
use AsyncAws\S3\ValueObject\LifecycleExpiration;
use AsyncAws\S3\ValueObject\LifecycleRule;
use AsyncAws\S3\ValueObject\LifecycleRuleFilter;
use AsyncAws\S3\ValueObject\Transition;

class PutBucketLifecycleConfigurationRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new PutBucketLifecycleConfigurationRequest([
            'Bucket' => 'my-bucket',
            'LifecycleConfiguration' => new BucketLifecycleConfiguration([
                'Rules' => [new LifecycleRule([
                    'ID' => 'Archive rule',
                    'Filter' => new LifecycleRuleFilter([
                        'Prefix' => 'documents/',
                    ]),
                    'Status' => ExpirationStatus::ENABLED,
                    'Transitions' => [new Transition([
                        'Days' => 30,
                        'StorageClass' => TransitionStorageClass::GLACIER,
                    ])],
                    'Expiration' => new LifecycleExpiration([
                        'Days' => 365,
                    ]),
                ])],
            ]),
        ]);

        $expected = '
PUT /my-bucket?lifecycle HTTP/1.0
Content-Type: application/xml

<?xml version="1.0" encoding="UTF-8"?>
<LifecycleConfiguration xmlns="http://s3.amazonaws.com/doc/2006-03-01/">
    <Rule>
        <Expiration>
            <Days>365</Days>
        </Expiration>
        <ID>Archive rule</ID>
        <Filter>
            <Prefix>documents/</Prefix>
        </Filter>
        <Status>Enabled</Status>
        <Transition>
            <Days>30</Days>
            <StorageClass>GLACIER</StorageClass>
        </Transition>
    </Rule>
</LifecycleConfiguration>
';
        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
