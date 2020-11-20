<?php

namespace AsyncAws\S3\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\S3\Enum\Event;
use AsyncAws\S3\Enum\FilterRuleName;
use AsyncAws\S3\Input\PutBucketNotificationConfigurationRequest;
use AsyncAws\S3\ValueObject\FilterRule;
use AsyncAws\S3\ValueObject\LambdaFunctionConfiguration;
use AsyncAws\S3\ValueObject\NotificationConfiguration;
use AsyncAws\S3\ValueObject\NotificationConfigurationFilter;
use AsyncAws\S3\ValueObject\QueueConfiguration;
use AsyncAws\S3\ValueObject\S3KeyFilter;
use AsyncAws\S3\ValueObject\TopicConfiguration;

class PutBucketNotificationConfigurationRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new PutBucketNotificationConfigurationRequest([
            'Bucket' => 'bucket-name',
            'NotificationConfiguration' => new NotificationConfiguration([
                'TopicConfigurations' => [
                    new TopicConfiguration([
                        'Id' => 'TopicId',
                        'TopicArn' => 'arn:topic',
                        'Events' => [Event::S3_OBJECT_CREATED_ALL],
                        'Filter' => new NotificationConfigurationFilter([
                            'Key' => new S3KeyFilter([
                                'FilterRules' => [
                                    new FilterRule([
                                        'Name' => FilterRuleName::PREFIX,
                                        'Value' => 'images/',
                                    ]),
                                ],
                            ]),
                        ]),
                    ]),
                ],
                'QueueConfigurations' => [
                    new QueueConfiguration([
                        'Id' => 'QueueId',
                        'QueueArn' => 'arn:queue',
                        'Events' => [Event::S3_OBJECT_CREATED_ALL],
                        'Filter' => new NotificationConfigurationFilter([
                            'Key' => new S3KeyFilter([
                                'FilterRules' => [new FilterRule([
                                    'Name' => FilterRuleName::PREFIX,
                                    'Value' => 'pdf/',
                                ])],
                            ]),
                        ]),
                    ]),
                ],
                'LambdaFunctionConfigurations' => [
                    new LambdaFunctionConfiguration([
                        'Id' => 'LambdaId',
                        'LambdaFunctionArn' => 'arn:lambda',
                        'Events' => [Event::S3_OBJECT_CREATED_ALL],
                        'Filter' => new NotificationConfigurationFilter([
                            'Key' => new S3KeyFilter([
                                'FilterRules' => [
                                    new FilterRule([
                                        'Name' => FilterRuleName::SUFFIX,
                                        'Value' => '.jpg',
                                    ]),
                                ],
                            ]),
                        ]),
                    ]),
                ],
            ]),
            'ExpectedBucketOwner' => 'bucket-name',
        ]);

        // see example-1.json from SDK
        $expected = '
PUT /bucket-name?notification HTTP/1.0
Content-Type: application/xml
x-amz-expected-bucket-owner: bucket-name

<?xml version="1.0" encoding="UTF-8"?>
<NotificationConfiguration xmlns="http://s3.amazonaws.com/doc/2006-03-01/">
    <TopicConfiguration>
        <Id>TopicId</Id>
        <Topic>arn:topic</Topic>
        <Event>s3:ObjectCreated:*</Event>
        <Filter>
            <S3Key><FilterRule><Name>prefix</Name><Value>images/</Value></FilterRule></S3Key>
        </Filter>
    </TopicConfiguration>
    <QueueConfiguration>
        <Id>QueueId</Id>
        <Queue>arn:queue</Queue>
        <Event>s3:ObjectCreated:*</Event>
        <Filter>
            <S3Key><FilterRule><Name>prefix</Name><Value>pdf/</Value></FilterRule></S3Key>
        </Filter>
    </QueueConfiguration>
    <CloudFunctionConfiguration>
        <Id>LambdaId</Id>
        <CloudFunction>arn:lambda</CloudFunction>
        <Event>s3:ObjectCreated:*</Event>
        <Filter>
            <S3Key><FilterRule><Name>suffix</Name><Value>.jpg</Value></FilterRule></S3Key>
        </Filter>
    </CloudFunctionConfiguration>
</NotificationConfiguration>
';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
