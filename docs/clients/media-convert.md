---
layout: client
category: clients
name: MediaConvert
package: async-aws/media-convert
---

## Usage

### Create jobs

```php
use AsyncAws\MediaConvert\MediaConvertClient;
use AsyncAws\MediaConvert\Input\CreateJobRequest;
use AsyncAws\MediaConvert\ValueObject\Input;
use AsyncAws\MediaConvert\ValueObject\JobSettings;
use AsyncAws\MediaConvert\ValueObject\Output;
use AsyncAws\MediaConvert\ValueObject\OutputGroup;

$mediaConvert = new MediaConvertClient();

$request = $mediaConvert->createJob(new CreateJobRequest([
    'Settings' => [
        new JobSettings([
            'Inputs' => [
                new Input(['FileInput' => 's3://my-bucket/file.mov'])
            ],
            'OutputGroups' => [
                new OutputGroup([
                    'Outputs' => [new Output([
                        'Preset' => 'h264'
                    ])],
                ]),
            ],
        ])
    ],
]));

echo 'Started the processing for the job '. $request->getJob()->getId();
```

> Note: The `MediaConvertClient` takes care automatically of the discovery of the account-specific
> endpoint needed for most operations (unlike what the official SDK implements).
