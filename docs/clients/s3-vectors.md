---
layout: client
category: clients
name: S3Vectors
package: async-aws/s3-vectors
---

The client supports CRUD operations on vector buckets, indexes and vectors alongside the ability to query vectors.

## Usage

### Create a Vector Bucket

```php
use AsyncAws\S3Vectors\S3VectorsClient;
use AsyncAws\S3Vectors\Input\CreateVectorBucketInput;

$client = new S3VectorsClient();

$result = $client->createVectorBucket(new CreateVectorBucketInput([
    'vectorBucketName' => 'my-vector-bucket',
    // optional: encryptionConfiguration and tags
    // 'encryptionConfiguration' => ['sseType' => 'AES256'],
    // 'tags' => ['env' => 'dev'],
]));

echo $result->getVectorBucketArn() . PHP_EOL;
```

### Create an Index in a Vector Bucket

```php
use AsyncAws\S3Vectors\S3VectorsClient;
use AsyncAws\S3Vectors\Input\CreateIndexInput;
use AsyncAws\S3Vectors\Enum\DataType;
use AsyncAws\S3Vectors\Enum\DistanceMetric;

$client = new S3VectorsClient();

$client->createIndex(new CreateIndexInput([
    // Identify the vector bucket where the index will be created. You can also use 'vectorBucketArn'.
    'vectorBucketName' => 'my-vector-bucket',

    // The immutable name of the index. Cannot be changed after creation.
    'indexName' => 'my-index',

    // Data type for stored vectors. S3 Vectors requires float32 values.
    'dataType' => DataType::FLOAT32,

    // Dimension: number of elements in each embedding vector produced by your model
    // (e.g. 128, 256, 1536). Every vector you put into this index must have this length.
    'dimension' => 128,

    // Distance metric used for similarity search. Choose based on your embeddings/use-case:
    // - DistanceMetric::COSINE: good for angle-based similarity (common for text embeddings)
    // - DistanceMetric::EUCLIDEAN: L2 distance when absolute magnitudes matter
    'distanceMetric' => DistanceMetric::COSINE,

    // Optional: specify metadata configuration. Use 'nonFilterableMetadataKeys' to mark
    // keys that should NOT be usable in query filters (useful for large text fields).
    // 'metadataConfiguration' => ['nonFilterableMetadataKeys' => ['large_text']],

    // Optional: encryption and tags
    // 'encryptionConfiguration' => ['sseType' => 'AES256'],
    // 'tags' => ['env' => 'dev'],
]));

echo "Index created" . PHP_EOL;
```


### Put Vectors Into the Index

```php
use AsyncAws\S3Vectors\S3VectorsClient;
use AsyncAws\S3Vectors\Input\PutVectorsInput;
use AsyncAws\S3Vectors\ValueObject\PutInputVector;

$client = new S3VectorsClient();

$vectors = [
    new PutInputVector([
        // Unique key for this vector inside the index. Putting a vector with an
        // existing key overwrites the previous vector.
        'key' => 'vec-1',

        // 'data' must match the index 'dimension' and be float32 values.
        // Provide as ['float32' => [ ... ]] where the inner array length equals 'dimension'.
        'data' => ['float32' => [1.1, 2.2, 3.3 /* ... up to dimension */]],

        // Optional metadata attached to the vector. By default metadata keys are filterable
        // and can be used in QueryVectors filters. If a key was declared non-filterable at
        // index creation, it cannot be used in filters but can be returned with the result.
        'metadata' => ['genre' => 'documentary', 'year' => 2023],
    ]),
];

$client->putVectors(new PutVectorsInput([
    'vectorBucketName' => 'my-vector-bucket',
    'indexName' => 'my-index',
    'vectors' => $vectors,
]));

echo "Vectors uploaded" . PHP_EOL;
```


### Query Vectors

```php
use AsyncAws\S3Vectors\S3VectorsClient;
use AsyncAws\S3Vectors\Input\QueryVectorsInput;
use AsyncAws\S3Vectors\ValueObject\VectorData;

$client = new S3VectorsClient();

$queryVector = new VectorData(['float32' => [1.23, 4.56 /* ... */]]);

$result = $client->queryVectors(new QueryVectorsInput([
    'vectorBucketName' => 'my-vector-bucket',
    'indexName' => 'my-index',

    // topK: number of nearest neighbours to return (the 'K' in top-K)
    'topK' => 5,

    // queryVector: the embedding to search with. Use the same embedding model
    // that was used to create the vectors in the index for consistent results.
    'queryVector' => $queryVector,

    // filter: optional metadata filter (must not refer to non-filterable keys)
    // 'filter' => ['genre' => 'documentary'],

    // returnMetadata: include metadata in the response
    'returnMetadata' => true,

    // returnDistance: include the computed distance between query and each match
    'returnDistance' => true,
]));

$keys = [];
foreach ($result->getVectors() as $match) {
    $keys[] = $match->getKey();
    echo $match->getKey() . ' distance=' . ($match->getDistance() ?? '') . PHP_EOL;
}

// Use the vector keys to fetch their data and metadata in bulk
if ([] !== $keys) {
    $vectorResult = $client->getVectors(new \AsyncAws\S3Vectors\Input\GetVectorsInput([
        'vectorBucketName' => 'my-vector-bucket',
        'indexName' => 'my-index',
        'keys' => $keys,
        'returnData' => true,
        'returnMetadata' => true,
    ]));
    foreach ($vectorResult->getVectors() as $stored) {
        echo 'Stored key=' . $stored->getKey() . PHP_EOL;
        echo 'Metadata=' . json_encode($stored->getMetadata() ?? []) . PHP_EOL;
        echo 'Data=' . json_encode($stored->getData() ? $stored->getData()->getFloat32() : []) . PHP_EOL;
    }
}
```
