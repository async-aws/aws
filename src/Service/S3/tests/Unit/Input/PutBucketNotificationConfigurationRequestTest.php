<?php

namespace AsyncAws\S3\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
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
                        'Id' => 'change me',
                        'TopicArn' => 'change me',
                        'Events' => ['s3:ObjectCreated:*'],
                        'Filter' => new NotificationConfigurationFilter([
                            'Key' => new S3KeyFilter([
                                'FilterRules' => [
                                    new FilterRule([
                                        'Name' => 'prefix',
                                        'Value' => 'change me',
                                    ]),
                                ],
                            ]),
                        ]),
                    ]),
                ],
                'QueueConfigurations' => [
                    new QueueConfiguration([
                        'Id' => 'change me',
                        'QueueArn' => 'change me',
                        'Events' => ['s3:ObjectCreated:*'],
                        'Filter' => new NotificationConfigurationFilter([
                            'Key' => new S3KeyFilter([
                                'FilterRules' => [new FilterRule([
                                    'Name' => 'prefix',
                                    'Value' => 'change me',
                                ])],
                            ]),
                        ]),
                    ]),
                ],
                'LambdaFunctionConfigurations' => [
                    new LambdaFunctionConfiguration([
                        'Id' => 'change me',
                        'LambdaFunctionArn' => 'change me',
                        'Events' => ['s3:ObjectCreated:*'],
                        'Filter' => new NotificationConfigurationFilter([
                            'Key' => new S3KeyFilter([
                                'FilterRules' => [
                                    new FilterRule([
                                        'Name' => 'suffix',
                                        'Value' => 'change me',
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
        <Id>change me</Id>
        <Topic>change me</Topic>
        <Event>s3:ObjectCreated:*</Event>
        <Filter>
            <S3Key><FilterRule><Name>prefix</Name><Value>change me</Value></FilterRule></S3Key>
        </Filter>
    </TopicConfiguration>
    <QueueConfiguration>
        <Id>change me</Id>
        <Queue>change me</Queue>
        <Event>s3:ObjectCreated:*</Event>
        <Filter>
            <S3Key><FilterRule><Name>prefix</Name><Value>change me</Value></FilterRule></S3Key>
        </Filter>
    </QueueConfiguration>
    <CloudFunctionConfiguration>
        <Id>change me</Id>
        <CloudFunction>change me</CloudFunction>
        <Event>s3:ObjectCreated:*</Event>
        <Filter>
            <S3Key><FilterRule><Name>suffix</Name><Value>change me</Value></FilterRule></S3Key>
        </Filter>
    </CloudFunctionConfiguration>
</NotificationConfiguration>
';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
