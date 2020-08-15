---
layout: client
category: clients
name: Rekognition
package: async-aws/rekognition
---

The client supports the basics methods for common use cases of facial recognition

## Usage

### Create Collections

Creating collection is a way to split your rekognition workflows into bags with is own characteristics, for easy searching:

```php
use AsyncAws\Rekognition\RekognitionClient;
use AsyncAws\Rekognition\Input\CreateCollectionRequest;

$rekognition = new RekognitionClient();

$request = new CreateCollectionRequest([
    "collectionId" => "testId",
    "region" => "us-west-2"
]);

$response = $rekognition->createCollection($request);

$response->resolve();

$collectionArn = $response->getCollectionArn();
```

### Add faces to a collection

If you want to add faces from an image into a collection you will use the method indexFaces.

```php
use AsyncAws\Rekognition\RekognitionClient;
use AsyncAws\Rekognition\Input\IndexFacesRequest;

$rekognition = new RekognitionClient();

$addFacesRequest = new IndexFacesRequest(
    [
        "Image" => "base64",
        "CollectionId" => "testId",
        "ExternalImageId" => "dbIdToBind",
        "Region" => "us-west-2"
    ]
);

$response = $rekognition->indexFaces($addFacesRequest);
```

### Search for a face inside a collection

When you want to search a face inside a collection with an image you will use the method searchFacesByImage.

```php
use AsyncAws\Rekognition\RekognitionClient;
use AsyncAws\Rekognition\Input\searchFacesByImageRequest;

$rekognition = new RekognitionClient();

$addFacesRequest = new searchFacesByImageRequest(
    [
        "image" => "base64",
        "faceMatchThreshold" => 75.5,
        "collectionId" => "testId",
        "region" => "us-west-2"
    ]
);

$response = $rekognition->searchFacesByImage($addFacesRequest);
```

