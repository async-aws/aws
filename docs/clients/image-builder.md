---
layout: client
category: clients
name: ImageBuilder
package: async-aws/image-builder
---

## Usage

### Start an image pipeline execution

```php
use AsyncAws\ImageBuilder\ImageBuilderClient;

$imageBuilder = new ImageBuilderClient();

$result = $imageBuilder->startImagePipelineExecution([
    'imagePipelineArn' => 'arn:aws:imagebuilder:us-east-1:123456789012:image-pipeline/example',
]);

echo $result->getImageBuildVersionArn();
```

### Get an image

```php
use AsyncAws\ImageBuilder\ImageBuilderClient;

$imageBuilder = new ImageBuilderClient();

$result = $imageBuilder->getImage([
    'imageBuildVersionArn' => 'arn:aws:imagebuilder:us-east-1:123456789012:image/example/1.0.0/1',
]);

$image = $result->getImage();
echo $image->getState()->getStatus() . PHP_EOL;

foreach ($image->getOutputResources()->getAmis() as $ami) {
    echo $ami->getImage() . PHP_EOL;
}
```
