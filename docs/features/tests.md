---
category: features
---

# Writing tests and mocking

Below is an example of a unit test for a `FileUploader` class. It is mocking the `S3Client`
and makes sure it returns a `PutObjectOutput` result.

```php
use PHPUnit\Framework\TestCase;
use AsyncAws\S3\S3Client;
use AsyncAws\S3\Result\PutObjectOutput;
use AsyncAws\Core\Test\ResultMockFactory;
use App\FileUploader;

class S3UploadTest extends TestCase
{
    public function testWrite()
    {
        $file = '/foo/bar.txt';

        $s3Client = $this->getMockBuilder(S3Client::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['putObject'])
            ->getMock();

        $result = ResultMockFactory::create(PutObjectOutput::class);

        $s3Client->expects(self::once())
            ->method('putObject')
            ->with(self::callback(function (array $input) use ($file) {
                if ($input['Key'] !== $file) {
                    return false;
                }
                if ('my file contents' !== $input['Body']) {
                    return false;
                }

                if ('myBucket' !== $input['Bucket']) {
                    return false;
                }

                return true;
            }))->willReturn($result);

        $uploader = new FileUploader($s3Client);
        $uploader->write($file, 'my file contents');
    }
}

```

The `ResultMockFactory` is used to create mocks for result classes. Use the second
argument to set properties to the result class.

```php
use AsyncAws\S3\Result\GetObjectOutput;
use AsyncAws\Core\Test\ResultMockFactory;
use AsyncAws\Core\Test\SimpleResultStream;

$result = ResultMockFactory::create(GetObjectOutput::class, [
    'LastModified' => new \DateTimeImmutable(),
    'Body' => new SimpleResultStream('my content'),
]);
```
