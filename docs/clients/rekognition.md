---
layout: client
category: clients
name: Rekognition
package: async-aws/rekognition
---

The client supports the basics methods for common use cases of facial recognition

## Usage

### Create Collections

Creating collection is a way to split your rekognition workflows into bags with
is own characteristics, for easy searching:

```php
use AsyncAws\Rekognition\RekognitionClient;
use AsyncAws\Rekognition\Input\CreateCollectionRequest;

$rekognition = new RekognitionClient();

$request = new CreateCollectionRequest([
    "collectionId" => "dogs-collection",
]);

$response = $rekognition->createCollection($request);

echo 'Version: ' . $response->getFaceModelVersion();
```

### Add faces to a collection

If you want to add faces from an image into a collection you will use the method
indexFaces.

```php
use AsyncAws\Rekognition\RekognitionClient;
use AsyncAws\Rekognition\Input\IndexFacesRequest;
use AsyncAws\Rekognition\ValueObject\Image;

$rekognition = new RekognitionClient();

$addFacesRequest = new IndexFacesRequest(
    [
        'Image' => new Image(['Bytes' => file_get_contents(__DIR__ . '/resources/snoopy.jpg')]),
        'CollectionId' => 'dogs-collection',
        'ExternalImageId' => 'snoopy_1',
    ]
);

foreach ($rekognition->indexFaces($addFacesRequest)->getFaceRecords() as $faceRecord) {
    echo 'face: ' . $faceRecord->getFace()->getFaceId() . ' (' . $faceRecord->getFace()->getConfidence() . '%)';
}
```

### Search for a face inside a collection

When you want to search a face inside a collection with an image you will use
the method searchFacesByImage.

```php
use AsyncAws\Rekognition\RekognitionClient;
use AsyncAws\Rekognition\Input\searchFacesByImageRequest;
use AsyncAws\Rekognition\ValueObject\Image;

$rekognition = new RekognitionClient();

$addFacesRequest = new SearchFacesByImageRequest(
    [
        'image' => new Image(['Bytes' => file_get_contents(__DIR__ . '/resources/snoopy.jpg')]),
        'faceMatchThreshold' => 75.5,
        'collectionId' => 'dogs-collection',
    ]
);

$response = $rekognition->searchFacesByImage($addFacesRequest)->getSearchedFaceBoundingBox();

foreach ($rekognition->searchFacesByImage($addFacesRequest)->getFaceMatches() as $faceMatch) {
    echo 'face: ' . $faceMatch->getFace()->getFaceId() . ' (' . $faceMatch->getFace()->getConfidence() . '%)';
}
```

