<?php

namespace AsyncAws\Rekognition\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Rekognition\Enum\ContentClassifier;
use AsyncAws\Rekognition\Input\DetectModerationLabelsRequest;
use AsyncAws\Rekognition\ValueObject\HumanLoopConfig;
use AsyncAws\Rekognition\ValueObject\HumanLoopDataAttributes;
use AsyncAws\Rekognition\ValueObject\Image;
use AsyncAws\Rekognition\ValueObject\S3Object;

class DetectModerationLabelsRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new DetectModerationLabelsRequest([
            'Image' => new Image([
                'Bytes' => 'change me',
                'S3Object' => new S3Object([
                    'Bucket' => 'some-bucket',
                    'Name' => 'path/to/my/file.png',
                    'Version' => '1',
                ]),
            ]),
            'MinConfidence' => 50,
            'HumanLoopConfig' => new HumanLoopConfig([
                'HumanLoopName' => 'some-flow',
                'FlowDefinitionArn' => 'arn:aws:sage-maker:us-east-1:123456789012:my-flow',
                'DataAttributes' => new HumanLoopDataAttributes([
                    'ContentClassifiers' => [ContentClassifier::FREE_OF_PERSONALLY_IDENTIFIABLE_INFORMATION],
                ]),
            ]),
        ]);

        // see https://docs.aws.amazon.com/rekognition/latest/dg/API_DetectModerationLabels.html
        $expected = '
            POST / HTTP/1.0
            Content-Type: application/x-amz-json-1.1
            x-amz-target: RekognitionService.DetectModerationLabels

            {
              "HumanLoopConfig": {
                "DataAttributes": {
                  "ContentClassifiers": [
                    "FreeOfPersonallyIdentifiableInformation"
                  ]
                },
                "FlowDefinitionArn": "arn:aws:sage-maker:us-east-1:123456789012:my-flow",
                "HumanLoopName": "some-flow"
              },
              "Image": {
                "Bytes": "Y2hhbmdlIG1l",
                "S3Object": {
                  "Bucket": "some-bucket",
                  "Name": "path/to/my/file.png",
                  "Version": "1"
                }
              },
              "MinConfidence": 50
            }
                ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
