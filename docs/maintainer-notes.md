# Maintainer notes

A few notes that are good to know if you contribute or maintain this library. 

## Generated code

Most functions in our API clients are generated. So are the result classes. They
are generated from the JSON provided by the official [AWS PHP SDK](https://github.com/aws/aws-sdk-php).
This will assure correctness and it will be easy to keep up to with API changes. 

To create or update a class run the `generate` command. 

```cli
./generate create s3 CreateBucket
```

The `build/manifest.json` file contains information where the source is located
and some metadata about the generated files and methods.
