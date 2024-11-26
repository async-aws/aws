<?php

namespace AsyncAws\S3;

use AsyncAws\Core\AbstractApi;
use AsyncAws\Core\AwsError\AwsErrorFactoryInterface;
use AsyncAws\Core\AwsError\XmlAwsErrorFactory;
use AsyncAws\Core\Configuration;
use AsyncAws\Core\RequestContext;
use AsyncAws\Core\Result;
use AsyncAws\S3\Enum\BucketCannedACL;
use AsyncAws\S3\Enum\ChecksumAlgorithm;
use AsyncAws\S3\Enum\ChecksumMode;
use AsyncAws\S3\Enum\EncodingType;
use AsyncAws\S3\Enum\MetadataDirective;
use AsyncAws\S3\Enum\ObjectCannedACL;
use AsyncAws\S3\Enum\ObjectLockLegalHoldStatus;
use AsyncAws\S3\Enum\ObjectLockMode;
use AsyncAws\S3\Enum\ObjectOwnership;
use AsyncAws\S3\Enum\OptionalObjectAttributes;
use AsyncAws\S3\Enum\RequestPayer;
use AsyncAws\S3\Enum\ServerSideEncryption;
use AsyncAws\S3\Enum\StorageClass;
use AsyncAws\S3\Enum\TaggingDirective;
use AsyncAws\S3\Exception\BucketAlreadyExistsException;
use AsyncAws\S3\Exception\BucketAlreadyOwnedByYouException;
use AsyncAws\S3\Exception\EncryptionTypeMismatchException;
use AsyncAws\S3\Exception\InvalidObjectStateException;
use AsyncAws\S3\Exception\InvalidRequestException;
use AsyncAws\S3\Exception\InvalidWriteOffsetException;
use AsyncAws\S3\Exception\NoSuchBucketException;
use AsyncAws\S3\Exception\NoSuchKeyException;
use AsyncAws\S3\Exception\NoSuchUploadException;
use AsyncAws\S3\Exception\ObjectNotInActiveTierErrorException;
use AsyncAws\S3\Exception\TooManyPartsException;
use AsyncAws\S3\Input\AbortMultipartUploadRequest;
use AsyncAws\S3\Input\CompleteMultipartUploadRequest;
use AsyncAws\S3\Input\CopyObjectRequest;
use AsyncAws\S3\Input\CreateBucketRequest;
use AsyncAws\S3\Input\CreateMultipartUploadRequest;
use AsyncAws\S3\Input\DeleteBucketCorsRequest;
use AsyncAws\S3\Input\DeleteBucketRequest;
use AsyncAws\S3\Input\DeleteObjectRequest;
use AsyncAws\S3\Input\DeleteObjectsRequest;
use AsyncAws\S3\Input\DeleteObjectTaggingRequest;
use AsyncAws\S3\Input\GetBucketCorsRequest;
use AsyncAws\S3\Input\GetBucketEncryptionRequest;
use AsyncAws\S3\Input\GetObjectAclRequest;
use AsyncAws\S3\Input\GetObjectRequest;
use AsyncAws\S3\Input\GetObjectTaggingRequest;
use AsyncAws\S3\Input\HeadBucketRequest;
use AsyncAws\S3\Input\HeadObjectRequest;
use AsyncAws\S3\Input\ListBucketsRequest;
use AsyncAws\S3\Input\ListMultipartUploadsRequest;
use AsyncAws\S3\Input\ListObjectsV2Request;
use AsyncAws\S3\Input\ListObjectVersionsRequest;
use AsyncAws\S3\Input\ListPartsRequest;
use AsyncAws\S3\Input\PutBucketCorsRequest;
use AsyncAws\S3\Input\PutBucketNotificationConfigurationRequest;
use AsyncAws\S3\Input\PutBucketTaggingRequest;
use AsyncAws\S3\Input\PutObjectAclRequest;
use AsyncAws\S3\Input\PutObjectRequest;
use AsyncAws\S3\Input\PutObjectTaggingRequest;
use AsyncAws\S3\Input\UploadPartCopyRequest;
use AsyncAws\S3\Input\UploadPartRequest;
use AsyncAws\S3\Result\AbortMultipartUploadOutput;
use AsyncAws\S3\Result\BucketExistsWaiter;
use AsyncAws\S3\Result\BucketNotExistsWaiter;
use AsyncAws\S3\Result\CompleteMultipartUploadOutput;
use AsyncAws\S3\Result\CopyObjectOutput;
use AsyncAws\S3\Result\CreateBucketOutput;
use AsyncAws\S3\Result\CreateMultipartUploadOutput;
use AsyncAws\S3\Result\DeleteObjectOutput;
use AsyncAws\S3\Result\DeleteObjectsOutput;
use AsyncAws\S3\Result\DeleteObjectTaggingOutput;
use AsyncAws\S3\Result\GetBucketCorsOutput;
use AsyncAws\S3\Result\GetBucketEncryptionOutput;
use AsyncAws\S3\Result\GetObjectAclOutput;
use AsyncAws\S3\Result\GetObjectOutput;
use AsyncAws\S3\Result\GetObjectTaggingOutput;
use AsyncAws\S3\Result\HeadObjectOutput;
use AsyncAws\S3\Result\ListBucketsOutput;
use AsyncAws\S3\Result\ListMultipartUploadsOutput;
use AsyncAws\S3\Result\ListObjectsV2Output;
use AsyncAws\S3\Result\ListObjectVersionsOutput;
use AsyncAws\S3\Result\ListPartsOutput;
use AsyncAws\S3\Result\ObjectExistsWaiter;
use AsyncAws\S3\Result\ObjectNotExistsWaiter;
use AsyncAws\S3\Result\PutObjectAclOutput;
use AsyncAws\S3\Result\PutObjectOutput;
use AsyncAws\S3\Result\PutObjectTaggingOutput;
use AsyncAws\S3\Result\UploadPartCopyOutput;
use AsyncAws\S3\Result\UploadPartOutput;
use AsyncAws\S3\Signer\SignerV4ForS3;
use AsyncAws\S3\ValueObject\AccessControlPolicy;
use AsyncAws\S3\ValueObject\CompletedMultipartUpload;
use AsyncAws\S3\ValueObject\CORSConfiguration;
use AsyncAws\S3\ValueObject\CreateBucketConfiguration;
use AsyncAws\S3\ValueObject\Delete;
use AsyncAws\S3\ValueObject\MultipartUpload;
use AsyncAws\S3\ValueObject\NotificationConfiguration;
use AsyncAws\S3\ValueObject\Part;
use AsyncAws\S3\ValueObject\Tagging;

class S3Client extends AbstractApi
{
    /**
     * This operation aborts a multipart upload. After a multipart upload is aborted, no additional parts can be uploaded
     * using that upload ID. The storage consumed by any previously uploaded parts will be freed. However, if any part
     * uploads are currently in progress, those part uploads might or might not succeed. As a result, it might be necessary
     * to abort a given multipart upload multiple times in order to completely free all storage consumed by all parts.
     *
     * To verify that all parts have been removed and prevent getting charged for the part storage, you should call the
     * ListParts [^1] API operation and ensure that the parts list is empty.
     *
     * > - **Directory buckets** - If multipart uploads in a directory bucket are in progress, you can't delete the bucket
     * >   until all the in-progress multipart uploads are aborted or completed. To delete these in-progress multipart
     * >   uploads, use the `ListMultipartUploads` operation to list the in-progress multipart uploads in the bucket and use
     * >   the `AbortMultipartUpload` operation to abort all the in-progress multipart uploads.
     * > - **Directory buckets** - For directory buckets, you must make requests for this API operation to the Zonal
     * >   endpoint. These endpoints support virtual-hosted-style requests in the format
     * >   `https://*bucket_name*.s3express-*az_id*.*region*.amazonaws.com/*key-name*`. Path-style requests are not
     * >   supported. For more information, see Regional and Zonal endpoints [^2] in the *Amazon S3 User Guide*.
     * >
     *
     * - `Permissions`:
     *
     *   - **General purpose bucket permissions** - For information about permissions required to use the multipart upload,
     *     see Multipart Upload and Permissions [^3] in the *Amazon S3 User Guide*.
     *   - **Directory bucket permissions** - To grant access to this API operation on a directory bucket, we recommend that
     *     you use the `CreateSession` [^4] API operation for session-based authorization. Specifically, you grant the
     *     `s3express:CreateSession` permission to the directory bucket in a bucket policy or an IAM identity-based policy.
     *     Then, you make the `CreateSession` API call on the bucket to obtain a session token. With the session token in
     *     your request header, you can make API requests to this operation. After the session token expires, you make
     *     another `CreateSession` API call to generate a new session token for use. Amazon Web Services CLI or SDKs create
     *     session and refresh the session token automatically to avoid service interruptions when a session expires. For
     *     more information about authorization, see `CreateSession` [^5].
     *
     * - `HTTP Host header syntax`:
     *
     *   **Directory buckets ** - The HTTP Host header syntax is `*Bucket_name*.s3express-*az_id*.*region*.amazonaws.com`.
     *
     * The following operations are related to `AbortMultipartUpload`:
     *
     * - CreateMultipartUpload [^6]
     * - UploadPart [^7]
     * - CompleteMultipartUpload [^8]
     * - ListParts [^9]
     * - ListMultipartUploads [^10]
     *
     * [^1]: https://docs.aws.amazon.com/AmazonS3/latest/API/API_ListParts.html
     * [^2]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/s3-express-Regions-and-Zones.html
     * [^3]: https://docs.aws.amazon.com/AmazonS3/latest/dev/mpuAndPermissions.html
     * [^4]: https://docs.aws.amazon.com/AmazonS3/latest/API/API_CreateSession.html
     * [^5]: https://docs.aws.amazon.com/AmazonS3/latest/API/API_CreateSession.html
     * [^6]: https://docs.aws.amazon.com/AmazonS3/latest/API/API_CreateMultipartUpload.html
     * [^7]: https://docs.aws.amazon.com/AmazonS3/latest/API/API_UploadPart.html
     * [^8]: https://docs.aws.amazon.com/AmazonS3/latest/API/API_CompleteMultipartUpload.html
     * [^9]: https://docs.aws.amazon.com/AmazonS3/latest/API/API_ListParts.html
     * [^10]: https://docs.aws.amazon.com/AmazonS3/latest/API/API_ListMultipartUploads.html
     *
     * @see http://docs.amazonwebservices.com/AmazonS3/latest/API/mpUploadAbort.html
     * @see https://docs.aws.amazon.com/AmazonS3/latest/API/API_AbortMultipartUpload.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-s3-2006-03-01.html#abortmultipartupload
     *
     * @param array{
     *   Bucket: string,
     *   Key: string,
     *   UploadId: string,
     *   RequestPayer?: null|RequestPayer::*,
     *   ExpectedBucketOwner?: null|string,
     *   IfMatchInitiatedTime?: null|\DateTimeImmutable|string,
     *   '@region'?: string|null,
     * }|AbortMultipartUploadRequest $input
     *
     * @throws NoSuchUploadException
     */
    public function abortMultipartUpload($input): AbortMultipartUploadOutput
    {
        $input = AbortMultipartUploadRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'AbortMultipartUpload', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'NoSuchUpload' => NoSuchUploadException::class,
        ]]));

        return new AbortMultipartUploadOutput($response);
    }

    /**
     * @see headBucket
     *
     * @param array{
     *   Bucket: string,
     *   ExpectedBucketOwner?: null|string,
     *   '@region'?: string|null,
     * }|HeadBucketRequest $input
     */
    public function bucketExists($input): BucketExistsWaiter
    {
        $input = HeadBucketRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'HeadBucket', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'NoSuchBucket' => NoSuchBucketException::class,
        ]]));

        return new BucketExistsWaiter($response, $this, $input);
    }

    /**
     * @see headBucket
     *
     * @param array{
     *   Bucket: string,
     *   ExpectedBucketOwner?: null|string,
     *   '@region'?: string|null,
     * }|HeadBucketRequest $input
     */
    public function bucketNotExists($input): BucketNotExistsWaiter
    {
        $input = HeadBucketRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'HeadBucket', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'NoSuchBucket' => NoSuchBucketException::class,
        ]]));

        return new BucketNotExistsWaiter($response, $this, $input);
    }

    /**
     * Completes a multipart upload by assembling previously uploaded parts.
     *
     * You first initiate the multipart upload and then upload all parts using the UploadPart [^1] operation or the
     * UploadPartCopy [^2] operation. After successfully uploading all relevant parts of an upload, you call this
     * `CompleteMultipartUpload` operation to complete the upload. Upon receiving this request, Amazon S3 concatenates all
     * the parts in ascending order by part number to create a new object. In the CompleteMultipartUpload request, you must
     * provide the parts list and ensure that the parts list is complete. The CompleteMultipartUpload API operation
     * concatenates the parts that you provide in the list. For each part in the list, you must provide the `PartNumber`
     * value and the `ETag` value that are returned after that part was uploaded.
     *
     * The processing of a CompleteMultipartUpload request could take several minutes to finalize. After Amazon S3 begins
     * processing the request, it sends an HTTP response header that specifies a `200 OK` response. While processing is in
     * progress, Amazon S3 periodically sends white space characters to keep the connection from timing out. A request could
     * fail after the initial `200 OK` response has been sent. This means that a `200 OK` response can contain either a
     * success or an error. The error response might be embedded in the `200 OK` response. If you call this API operation
     * directly, make sure to design your application to parse the contents of the response and handle it appropriately. If
     * you use Amazon Web Services SDKs, SDKs handle this condition. The SDKs detect the embedded error and apply error
     * handling per your configuration settings (including automatically retrying the request as appropriate). If the
     * condition persists, the SDKs throw an exception (or, for the SDKs that don't use exceptions, they return an error).
     *
     * Note that if `CompleteMultipartUpload` fails, applications should be prepared to retry any failed requests (including
     * 500 error responses). For more information, see Amazon S3 Error Best Practices [^3].
     *
     * ! You can't use `Content-Type: application/x-www-form-urlencoded` for the CompleteMultipartUpload requests. Also, if
     * ! you don't provide a `Content-Type` header, `CompleteMultipartUpload` can still return a `200 OK` response.
     *
     * For more information about multipart uploads, see Uploading Objects Using Multipart Upload [^4] in the *Amazon S3
     * User Guide*.
     *
     * > **Directory buckets** - For directory buckets, you must make requests for this API operation to the Zonal endpoint.
     * > These endpoints support virtual-hosted-style requests in the format
     * > `https://*bucket_name*.s3express-*az_id*.*region*.amazonaws.com/*key-name*`. Path-style requests are not supported.
     * > For more information, see Regional and Zonal endpoints [^5] in the *Amazon S3 User Guide*.
     *
     * - `Permissions`:
     *
     *   - **General purpose bucket permissions** - For information about permissions required to use the multipart upload
     *     API, see Multipart Upload and Permissions [^6] in the *Amazon S3 User Guide*.
     *
     *     If you provide an additional checksum value [^7] in your `MultipartUpload` requests and the object is encrypted
     *     with Key Management Service, you must have permission to use the `kms:Decrypt` action for the
     *     `CompleteMultipartUpload` request to succeed.
     *   - **Directory bucket permissions** - To grant access to this API operation on a directory bucket, we recommend that
     *     you use the `CreateSession` [^8] API operation for session-based authorization. Specifically, you grant the
     *     `s3express:CreateSession` permission to the directory bucket in a bucket policy or an IAM identity-based policy.
     *     Then, you make the `CreateSession` API call on the bucket to obtain a session token. With the session token in
     *     your request header, you can make API requests to this operation. After the session token expires, you make
     *     another `CreateSession` API call to generate a new session token for use. Amazon Web Services CLI or SDKs create
     *     session and refresh the session token automatically to avoid service interruptions when a session expires. For
     *     more information about authorization, see `CreateSession` [^9].
     *
     *     If the object is encrypted with SSE-KMS, you must also have the `kms:GenerateDataKey` and `kms:Decrypt`
     *     permissions in IAM identity-based policies and KMS key policies for the KMS key.
     *
     * - `Special errors`:
     *
     *   - Error Code: `EntityTooSmall`
     *
     *     - Description: Your proposed upload is smaller than the minimum allowed object size. Each part must be at least 5
     *       MB in size, except the last part.
     *     - HTTP Status Code: 400 Bad Request
     *
     *   - Error Code: `InvalidPart`
     *
     *     - Description: One or more of the specified parts could not be found. The part might not have been uploaded, or
     *       the specified ETag might not have matched the uploaded part's ETag.
     *     - HTTP Status Code: 400 Bad Request
     *
     *   - Error Code: `InvalidPartOrder`
     *
     *     - Description: The list of parts was not in ascending order. The parts list must be specified in order by part
     *       number.
     *     - HTTP Status Code: 400 Bad Request
     *
     *   - Error Code: `NoSuchUpload`
     *
     *     - Description: The specified multipart upload does not exist. The upload ID might be invalid, or the multipart
     *       upload might have been aborted or completed.
     *     - HTTP Status Code: 404 Not Found
     *
     *
     * - `HTTP Host header syntax`:
     *
     *   **Directory buckets ** - The HTTP Host header syntax is `*Bucket_name*.s3express-*az_id*.*region*.amazonaws.com`.
     *
     * The following operations are related to `CompleteMultipartUpload`:
     *
     * - CreateMultipartUpload [^10]
     * - UploadPart [^11]
     * - AbortMultipartUpload [^12]
     * - ListParts [^13]
     * - ListMultipartUploads [^14]
     *
     * [^1]: https://docs.aws.amazon.com/AmazonS3/latest/API/API_UploadPart.html
     * [^2]: https://docs.aws.amazon.com/AmazonS3/latest/API/API_UploadPartCopy.html
     * [^3]: https://docs.aws.amazon.com/AmazonS3/latest/dev/ErrorBestPractices.html
     * [^4]: https://docs.aws.amazon.com/AmazonS3/latest/dev/uploadobjusingmpu.html
     * [^5]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/s3-express-Regions-and-Zones.html
     * [^6]: https://docs.aws.amazon.com/AmazonS3/latest/dev/mpuAndPermissions.html
     * [^7]: https://docs.aws.amazon.com/AmazonS3/latest/API/API_Checksum.html
     * [^8]: https://docs.aws.amazon.com/AmazonS3/latest/API/API_CreateSession.html
     * [^9]: https://docs.aws.amazon.com/AmazonS3/latest/API/API_CreateSession.html
     * [^10]: https://docs.aws.amazon.com/AmazonS3/latest/API/API_CreateMultipartUpload.html
     * [^11]: https://docs.aws.amazon.com/AmazonS3/latest/API/API_UploadPart.html
     * [^12]: https://docs.aws.amazon.com/AmazonS3/latest/API/API_AbortMultipartUpload.html
     * [^13]: https://docs.aws.amazon.com/AmazonS3/latest/API/API_ListParts.html
     * [^14]: https://docs.aws.amazon.com/AmazonS3/latest/API/API_ListMultipartUploads.html
     *
     * @see http://docs.amazonwebservices.com/AmazonS3/latest/API/mpUploadComplete.html
     * @see https://docs.aws.amazon.com/AmazonS3/latest/API/API_CompleteMultipartUpload.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-s3-2006-03-01.html#completemultipartupload
     *
     * @param array{
     *   Bucket: string,
     *   Key: string,
     *   MultipartUpload?: null|CompletedMultipartUpload|array,
     *   UploadId: string,
     *   ChecksumCRC32?: null|string,
     *   ChecksumCRC32C?: null|string,
     *   ChecksumSHA1?: null|string,
     *   ChecksumSHA256?: null|string,
     *   RequestPayer?: null|RequestPayer::*,
     *   ExpectedBucketOwner?: null|string,
     *   IfMatch?: null|string,
     *   IfNoneMatch?: null|string,
     *   SSECustomerAlgorithm?: null|string,
     *   SSECustomerKey?: null|string,
     *   SSECustomerKeyMD5?: null|string,
     *   '@region'?: string|null,
     * }|CompleteMultipartUploadRequest $input
     */
    public function completeMultipartUpload($input): CompleteMultipartUploadOutput
    {
        $input = CompleteMultipartUploadRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'CompleteMultipartUpload', 'region' => $input->getRegion()]));

        return new CompleteMultipartUploadOutput($response);
    }

    /**
     * Creates a copy of an object that is already stored in Amazon S3.
     *
     * > You can store individual objects of up to 5 TB in Amazon S3. You create a copy of your object up to 5 GB in size in
     * > a single atomic action using this API. However, to copy an object greater than 5 GB, you must use the multipart
     * > upload Upload Part - Copy (UploadPartCopy) API. For more information, see Copy Object Using the REST Multipart
     * > Upload API [^1].
     *
     * You can copy individual objects between general purpose buckets, between directory buckets, and between general
     * purpose buckets and directory buckets.
     *
     * > - Amazon S3 supports copy operations using Multi-Region Access Points only as a destination when using the
     * >   Multi-Region Access Point ARN.
     * > - **Directory buckets ** - For directory buckets, you must make requests for this API operation to the Zonal
     * >   endpoint. These endpoints support virtual-hosted-style requests in the format
     * >   `https://*bucket_name*.s3express-*az_id*.*region*.amazonaws.com/*key-name*`. Path-style requests are not
     * >   supported. For more information, see Regional and Zonal endpoints [^2] in the *Amazon S3 User Guide*.
     * > - VPC endpoints don't support cross-Region requests (including copies). If you're using VPC endpoints, your source
     * >   and destination buckets should be in the same Amazon Web Services Region as your VPC endpoint.
     * >
     *
     * Both the Region that you want to copy the object from and the Region that you want to copy the object to must be
     * enabled for your account. For more information about how to enable a Region for your account, see Enable or disable a
     * Region for standalone accounts [^3] in the *Amazon Web Services Account Management Guide*.
     *
     * ! Amazon S3 transfer acceleration does not support cross-Region copies. If you request a cross-Region copy using a
     * ! transfer acceleration endpoint, you get a `400 Bad Request` error. For more information, see Transfer Acceleration
     * ! [^4].
     *
     * - `Authentication and authorization`:
     *
     *   All `CopyObject` requests must be authenticated and signed by using IAM credentials (access key ID and secret
     *   access key for the IAM identities). All headers with the `x-amz-` prefix, including `x-amz-copy-source`, must be
     *   signed. For more information, see REST Authentication [^5].
     *
     *   **Directory buckets** - You must use the IAM credentials to authenticate and authorize your access to the
     *   `CopyObject` API operation, instead of using the temporary security credentials through the `CreateSession` API
     *   operation.
     *
     *   Amazon Web Services CLI or SDKs handles authentication and authorization on your behalf.
     * - `Permissions`:
     *
     *   You must have *read* access to the source object and *write* access to the destination bucket.
     *
     *   - **General purpose bucket permissions** - You must have permissions in an IAM policy based on the source and
     *     destination bucket types in a `CopyObject` operation.
     *
     *     - If the source object is in a general purpose bucket, you must have **`s3:GetObject`** permission to read the
     *       source object that is being copied.
     *     - If the destination bucket is a general purpose bucket, you must have **`s3:PutObject`** permission to write the
     *       object copy to the destination bucket.
     *
     *   - **Directory bucket permissions** - You must have permissions in a bucket policy or an IAM identity-based policy
     *     based on the source and destination bucket types in a `CopyObject` operation.
     *
     *     - If the source object that you want to copy is in a directory bucket, you must have the
     *       **`s3express:CreateSession`** permission in the `Action` element of a policy to read the object. By default,
     *       the session is in the `ReadWrite` mode. If you want to restrict the access, you can explicitly set the
     *       `s3express:SessionMode` condition key to `ReadOnly` on the copy source bucket.
     *     - If the copy destination is a directory bucket, you must have the **`s3express:CreateSession`** permission in
     *       the `Action` element of a policy to write the object to the destination. The `s3express:SessionMode` condition
     *       key can't be set to `ReadOnly` on the copy destination bucket.
     *
     *     If the object is encrypted with SSE-KMS, you must also have the `kms:GenerateDataKey` and `kms:Decrypt`
     *     permissions in IAM identity-based policies and KMS key policies for the KMS key.
     *
     *     For example policies, see Example bucket policies for S3 Express One Zone [^6] and Amazon Web Services Identity
     *     and Access Management (IAM) identity-based policies for S3 Express One Zone [^7] in the *Amazon S3 User Guide*.
     *
     * - `Response and special errors`:
     *
     *   When the request is an HTTP 1.1 request, the response is chunk encoded. When the request is not an HTTP 1.1
     *   request, the response would not contain the `Content-Length`. You always need to read the entire response body to
     *   check if the copy succeeds.
     *
     *   - If the copy is successful, you receive a response with information about the copied object.
     *   - A copy request might return an error when Amazon S3 receives the copy request or while Amazon S3 is copying the
     *     files. A `200 OK` response can contain either a success or an error.
     *
     *     - If the error occurs before the copy action starts, you receive a standard Amazon S3 error.
     *     - If the error occurs during the copy operation, the error response is embedded in the `200 OK` response. For
     *       example, in a cross-region copy, you may encounter throttling and receive a `200 OK` response. For more
     *       information, see Resolve the Error 200 response when copying objects to Amazon S3 [^8]. The `200 OK` status
     *       code means the copy was accepted, but it doesn't mean the copy is complete. Another example is when you
     *       disconnect from Amazon S3 before the copy is complete, Amazon S3 might cancel the copy and you may receive a
     *       `200 OK` response. You must stay connected to Amazon S3 until the entire response is successfully received and
     *       processed.
     *
     *       If you call this API operation directly, make sure to design your application to parse the content of the
     *       response and handle it appropriately. If you use Amazon Web Services SDKs, SDKs handle this condition. The SDKs
     *       detect the embedded error and apply error handling per your configuration settings (including automatically
     *       retrying the request as appropriate). If the condition persists, the SDKs throw an exception (or, for the SDKs
     *       that don't use exceptions, they return an error).
     *
     *
     * - `Charge`:
     *
     *   The copy request charge is based on the storage class and Region that you specify for the destination object. The
     *   request can also result in a data retrieval charge for the source if the source storage class bills for data
     *   retrieval. If the copy source is in a different region, the data transfer is billed to the copy source account. For
     *   pricing information, see Amazon S3 pricing [^9].
     * - `HTTP Host header syntax`:
     *
     *   **Directory buckets ** - The HTTP Host header syntax is `*Bucket_name*.s3express-*az_id*.*region*.amazonaws.com`.
     *
     * The following operations are related to `CopyObject`:
     *
     * - PutObject [^10]
     * - GetObject [^11]
     *
     * [^1]: https://docs.aws.amazon.com/AmazonS3/latest/dev/CopyingObjctsUsingRESTMPUapi.html
     * [^2]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/s3-express-Regions-and-Zones.html
     * [^3]: https://docs.aws.amazon.com/accounts/latest/reference/manage-acct-regions.html#manage-acct-regions-enable-standalone
     * [^4]: https://docs.aws.amazon.com/AmazonS3/latest/dev/transfer-acceleration.html
     * [^5]: https://docs.aws.amazon.com/AmazonS3/latest/dev/RESTAuthentication.html
     * [^6]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/s3-express-security-iam-example-bucket-policies.html
     * [^7]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/s3-express-security-iam-identity-policies.html
     * [^8]: https://repost.aws/knowledge-center/s3-resolve-200-internalerror
     * [^9]: http://aws.amazon.com/s3/pricing/
     * [^10]: https://docs.aws.amazon.com/AmazonS3/latest/API/API_PutObject.html
     * [^11]: https://docs.aws.amazon.com/AmazonS3/latest/API/API_GetObject.html
     *
     * @see http://docs.amazonwebservices.com/AmazonS3/latest/API/RESTObjectCOPY.html
     * @see https://docs.aws.amazon.com/AmazonS3/latest/API/API_CopyObject.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-s3-2006-03-01.html#copyobject
     *
     * @param array{
     *   ACL?: null|ObjectCannedACL::*,
     *   Bucket: string,
     *   CacheControl?: null|string,
     *   ChecksumAlgorithm?: null|ChecksumAlgorithm::*,
     *   ContentDisposition?: null|string,
     *   ContentEncoding?: null|string,
     *   ContentLanguage?: null|string,
     *   ContentType?: null|string,
     *   CopySource: string,
     *   CopySourceIfMatch?: null|string,
     *   CopySourceIfModifiedSince?: null|\DateTimeImmutable|string,
     *   CopySourceIfNoneMatch?: null|string,
     *   CopySourceIfUnmodifiedSince?: null|\DateTimeImmutable|string,
     *   Expires?: null|\DateTimeImmutable|string,
     *   GrantFullControl?: null|string,
     *   GrantRead?: null|string,
     *   GrantReadACP?: null|string,
     *   GrantWriteACP?: null|string,
     *   Key: string,
     *   Metadata?: null|array<string, string>,
     *   MetadataDirective?: null|MetadataDirective::*,
     *   TaggingDirective?: null|TaggingDirective::*,
     *   ServerSideEncryption?: null|ServerSideEncryption::*,
     *   StorageClass?: null|StorageClass::*,
     *   WebsiteRedirectLocation?: null|string,
     *   SSECustomerAlgorithm?: null|string,
     *   SSECustomerKey?: null|string,
     *   SSECustomerKeyMD5?: null|string,
     *   SSEKMSKeyId?: null|string,
     *   SSEKMSEncryptionContext?: null|string,
     *   BucketKeyEnabled?: null|bool,
     *   CopySourceSSECustomerAlgorithm?: null|string,
     *   CopySourceSSECustomerKey?: null|string,
     *   CopySourceSSECustomerKeyMD5?: null|string,
     *   RequestPayer?: null|RequestPayer::*,
     *   Tagging?: null|string,
     *   ObjectLockMode?: null|ObjectLockMode::*,
     *   ObjectLockRetainUntilDate?: null|\DateTimeImmutable|string,
     *   ObjectLockLegalHoldStatus?: null|ObjectLockLegalHoldStatus::*,
     *   ExpectedBucketOwner?: null|string,
     *   ExpectedSourceBucketOwner?: null|string,
     *   '@region'?: string|null,
     * }|CopyObjectRequest $input
     *
     * @throws ObjectNotInActiveTierErrorException
     */
    public function copyObject($input): CopyObjectOutput
    {
        $input = CopyObjectRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'CopyObject', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'ObjectNotInActiveTierError' => ObjectNotInActiveTierErrorException::class,
        ]]));

        return new CopyObjectOutput($response);
    }

    /**
     * > This action creates an Amazon S3 bucket. To create an Amazon S3 on Outposts bucket, see `CreateBucket` [^1].
     *
     * Creates a new S3 bucket. To create a bucket, you must set up Amazon S3 and have a valid Amazon Web Services Access
     * Key ID to authenticate requests. Anonymous requests are never allowed to create buckets. By creating the bucket, you
     * become the bucket owner.
     *
     * There are two types of buckets: general purpose buckets and directory buckets. For more information about these
     * bucket types, see Creating, configuring, and working with Amazon S3 buckets [^2] in the *Amazon S3 User Guide*.
     *
     * > - **General purpose buckets** - If you send your `CreateBucket` request to the `s3.amazonaws.com` global endpoint,
     * >   the request goes to the `us-east-1` Region. So the signature calculations in Signature Version 4 must use
     * >   `us-east-1` as the Region, even if the location constraint in the request specifies another Region where the
     * >   bucket is to be created. If you create a bucket in a Region other than US East (N. Virginia), your application
     * >   must be able to handle 307 redirect. For more information, see Virtual hosting of buckets [^3] in the *Amazon S3
     * >   User Guide*.
     * > - **Directory buckets ** - For directory buckets, you must make requests for this API operation to the Regional
     * >   endpoint. These endpoints support path-style requests in the format
     * >   `https://s3express-control.*region_code*.amazonaws.com/*bucket-name*`. Virtual-hosted-style requests aren't
     * >   supported. For more information, see Regional and Zonal endpoints [^4] in the *Amazon S3 User Guide*.
     * >
     *
     * - `Permissions`:
     *
     *   - **General purpose bucket permissions** - In addition to the `s3:CreateBucket` permission, the following
     *     permissions are required in a policy when your `CreateBucket` request includes specific headers:
     *
     *     - **Access control lists (ACLs)** - In your `CreateBucket` request, if you specify an access control list (ACL)
     *       and set it to `public-read`, `public-read-write`, `authenticated-read`, or if you explicitly specify any other
     *       custom ACLs, both `s3:CreateBucket` and `s3:PutBucketAcl` permissions are required. In your `CreateBucket`
     *       request, if you set the ACL to `private`, or if you don't specify any ACLs, only the `s3:CreateBucket`
     *       permission is required.
     *     - **Object Lock** - In your `CreateBucket` request, if you set `x-amz-bucket-object-lock-enabled` to true, the
     *       `s3:PutBucketObjectLockConfiguration` and `s3:PutBucketVersioning` permissions are required.
     *     - **S3 Object Ownership** - If your `CreateBucket` request includes the `x-amz-object-ownership` header, then the
     *       `s3:PutBucketOwnershipControls` permission is required.
     *
     *       ! To set an ACL on a bucket as part of a `CreateBucket` request, you must explicitly set S3 Object Ownership
     *       ! for the bucket to a different value than the default, `BucketOwnerEnforced`. Additionally, if your desired
     *       ! bucket ACL grants public access, you must first create the bucket (without the bucket ACL) and then
     *       ! explicitly disable Block Public Access on the bucket before using `PutBucketAcl` to set the ACL. If you try
     *       ! to create a bucket with a public ACL, the request will fail.
     *       !
     *       ! For the majority of modern use cases in S3, we recommend that you keep all Block Public Access settings
     *       ! enabled and keep ACLs disabled. If you would like to share data with users outside of your account, you can
     *       ! use bucket policies as needed. For more information, see Controlling ownership of objects and disabling ACLs
     *       ! for your bucket [^5] and Blocking public access to your Amazon S3 storage [^6] in the *Amazon S3 User Guide*.
     *
     *     - **S3 Block Public Access** - If your specific use case requires granting public access to your S3 resources,
     *       you can disable Block Public Access. Specifically, you can create a new bucket with Block Public Access
     *       enabled, then separately call the `DeletePublicAccessBlock` [^7] API. To use this operation, you must have the
     *       `s3:PutBucketPublicAccessBlock` permission. For more information about S3 Block Public Access, see Blocking
     *       public access to your Amazon S3 storage [^8] in the *Amazon S3 User Guide*.
     *
     *   - **Directory bucket permissions** - You must have the `s3express:CreateBucket` permission in an IAM identity-based
     *     policy instead of a bucket policy. Cross-account access to this API operation isn't supported. This operation can
     *     only be performed by the Amazon Web Services account that owns the resource. For more information about directory
     *     bucket policies and permissions, see Amazon Web Services Identity and Access Management (IAM) for S3 Express One
     *     Zone [^9] in the *Amazon S3 User Guide*.
     *
     *     ! The permissions for ACLs, Object Lock, S3 Object Ownership, and S3 Block Public Access are not supported for
     *     ! directory buckets. For directory buckets, all Block Public Access settings are enabled at the bucket level and
     *     ! S3 Object Ownership is set to Bucket owner enforced (ACLs disabled). These settings can't be modified.
     *     !
     *     ! For more information about permissions for creating and working with directory buckets, see Directory buckets
     *     ! [^10] in the *Amazon S3 User Guide*. For more information about supported S3 features for directory buckets,
     *     ! see Features of S3 Express One Zone [^11] in the *Amazon S3 User Guide*.
     *
     *
     * - `HTTP Host header syntax`:
     *
     *   **Directory buckets ** - The HTTP Host header syntax is `s3express-control.*region*.amazonaws.com`.
     *
     * The following operations are related to `CreateBucket`:
     *
     * - PutObject [^12]
     * - DeleteBucket [^13]
     *
     * [^1]: https://docs.aws.amazon.com/AmazonS3/latest/API/API_control_CreateBucket.html
     * [^2]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/creating-buckets-s3.html
     * [^3]: https://docs.aws.amazon.com/AmazonS3/latest/dev/VirtualHosting.html
     * [^4]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/s3-express-Regions-and-Zones.html
     * [^5]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/about-object-ownership.html
     * [^6]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/access-control-block-public-access.html
     * [^7]: https://docs.aws.amazon.com/AmazonS3/latest/API/API_DeletePublicAccessBlock.html
     * [^8]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/access-control-block-public-access.html
     * [^9]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/s3-express-security-iam.html
     * [^10]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/directory-buckets-overview.html
     * [^11]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/s3-express-one-zone.html#s3-express-features
     * [^12]: https://docs.aws.amazon.com/AmazonS3/latest/API/API_PutObject.html
     * [^13]: https://docs.aws.amazon.com/AmazonS3/latest/API/API_DeleteBucket.html
     *
     * @see http://docs.amazonwebservices.com/AmazonS3/latest/API/RESTBucketPUT.html
     * @see https://docs.aws.amazon.com/AmazonS3/latest/API/API_CreateBucket.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-s3-2006-03-01.html#createbucket
     *
     * @param array{
     *   ACL?: null|BucketCannedACL::*,
     *   Bucket: string,
     *   CreateBucketConfiguration?: null|CreateBucketConfiguration|array,
     *   GrantFullControl?: null|string,
     *   GrantRead?: null|string,
     *   GrantReadACP?: null|string,
     *   GrantWrite?: null|string,
     *   GrantWriteACP?: null|string,
     *   ObjectLockEnabledForBucket?: null|bool,
     *   ObjectOwnership?: null|ObjectOwnership::*,
     *   '@region'?: string|null,
     * }|CreateBucketRequest $input
     *
     * @throws BucketAlreadyExistsException
     * @throws BucketAlreadyOwnedByYouException
     */
    public function createBucket($input): CreateBucketOutput
    {
        $input = CreateBucketRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'CreateBucket', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'BucketAlreadyExists' => BucketAlreadyExistsException::class,
            'BucketAlreadyOwnedByYou' => BucketAlreadyOwnedByYouException::class,
        ]]));

        return new CreateBucketOutput($response);
    }

    /**
     * This action initiates a multipart upload and returns an upload ID. This upload ID is used to associate all of the
     * parts in the specific multipart upload. You specify this upload ID in each of your subsequent upload part requests
     * (see UploadPart [^1]). You also include this upload ID in the final request to either complete or abort the multipart
     * upload request. For more information about multipart uploads, see Multipart Upload Overview [^2] in the *Amazon S3
     * User Guide*.
     *
     * > After you initiate a multipart upload and upload one or more parts, to stop being charged for storing the uploaded
     * > parts, you must either complete or abort the multipart upload. Amazon S3 frees up the space used to store the parts
     * > and stops charging you for storing them only after you either complete or abort a multipart upload.
     *
     * If you have configured a lifecycle rule to abort incomplete multipart uploads, the created multipart upload must be
     * completed within the number of days specified in the bucket lifecycle configuration. Otherwise, the incomplete
     * multipart upload becomes eligible for an abort action and Amazon S3 aborts the multipart upload. For more
     * information, see Aborting Incomplete Multipart Uploads Using a Bucket Lifecycle Configuration [^3].
     *
     * > - **Directory buckets ** - S3 Lifecycle is not supported by directory buckets.
     * > - **Directory buckets ** - For directory buckets, you must make requests for this API operation to the Zonal
     * >   endpoint. These endpoints support virtual-hosted-style requests in the format
     * >   `https://*bucket_name*.s3express-*az_id*.*region*.amazonaws.com/*key-name*`. Path-style requests are not
     * >   supported. For more information, see Regional and Zonal endpoints [^4] in the *Amazon S3 User Guide*.
     * >
     *
     * - `Request signing`:
     *
     *   For request signing, multipart upload is just a series of regular requests. You initiate a multipart upload, send
     *   one or more requests to upload parts, and then complete the multipart upload process. You sign each request
     *   individually. There is nothing special about signing multipart upload requests. For more information about signing,
     *   see Authenticating Requests (Amazon Web Services Signature Version 4) [^5] in the *Amazon S3 User Guide*.
     * - `Permissions`:
     *
     *   - **General purpose bucket permissions** - To perform a multipart upload with encryption using an Key Management
     *     Service (KMS) KMS key, the requester must have permission to the `kms:Decrypt` and `kms:GenerateDataKey` actions
     *     on the key. The requester must also have permissions for the `kms:GenerateDataKey` action for the
     *     `CreateMultipartUpload` API. Then, the requester needs permissions for the `kms:Decrypt` action on the
     *     `UploadPart` and `UploadPartCopy` APIs. These permissions are required because Amazon S3 must decrypt and read
     *     data from the encrypted file parts before it completes the multipart upload. For more information, see Multipart
     *     upload API and permissions [^6] and Protecting data using server-side encryption with Amazon Web Services KMS
     *     [^7] in the *Amazon S3 User Guide*.
     *   - **Directory bucket permissions** - To grant access to this API operation on a directory bucket, we recommend that
     *     you use the `CreateSession` [^8] API operation for session-based authorization. Specifically, you grant the
     *     `s3express:CreateSession` permission to the directory bucket in a bucket policy or an IAM identity-based policy.
     *     Then, you make the `CreateSession` API call on the bucket to obtain a session token. With the session token in
     *     your request header, you can make API requests to this operation. After the session token expires, you make
     *     another `CreateSession` API call to generate a new session token for use. Amazon Web Services CLI or SDKs create
     *     session and refresh the session token automatically to avoid service interruptions when a session expires. For
     *     more information about authorization, see `CreateSession` [^9].
     *
     * - `Encryption`:
     *
     *   - **General purpose buckets** - Server-side encryption is for data encryption at rest. Amazon S3 encrypts your data
     *     as it writes it to disks in its data centers and decrypts it when you access it. Amazon S3 automatically encrypts
     *     all new objects that are uploaded to an S3 bucket. When doing a multipart upload, if you don't specify encryption
     *     information in your request, the encryption setting of the uploaded parts is set to the default encryption
     *     configuration of the destination bucket. By default, all buckets have a base level of encryption configuration
     *     that uses server-side encryption with Amazon S3 managed keys (SSE-S3). If the destination bucket has a default
     *     encryption configuration that uses server-side encryption with an Key Management Service (KMS) key (SSE-KMS), or
     *     a customer-provided encryption key (SSE-C), Amazon S3 uses the corresponding KMS key, or a customer-provided key
     *     to encrypt the uploaded parts. When you perform a CreateMultipartUpload operation, if you want to use a different
     *     type of encryption setting for the uploaded parts, you can request that Amazon S3 encrypts the object with a
     *     different encryption key (such as an Amazon S3 managed key, a KMS key, or a customer-provided key). When the
     *     encryption setting in your request is different from the default encryption configuration of the destination
     *     bucket, the encryption setting in your request takes precedence. If you choose to provide your own encryption
     *     key, the request headers you provide in UploadPart [^10] and UploadPartCopy [^11] requests must match the headers
     *     you used in the `CreateMultipartUpload` request.
     *
     *     - Use KMS keys (SSE-KMS) that include the Amazon Web Services managed key (`aws/s3`) and KMS customer managed
     *       keys stored in Key Management Service (KMS) – If you want Amazon Web Services to manage the keys used to
     *       encrypt data, specify the following headers in the request.
     *
     *       - `x-amz-server-side-encryption`
     *       - `x-amz-server-side-encryption-aws-kms-key-id`
     *       - `x-amz-server-side-encryption-context`
     *
     *       > - If you specify `x-amz-server-side-encryption:aws:kms`, but don't provide
     *       >   `x-amz-server-side-encryption-aws-kms-key-id`, Amazon S3 uses the Amazon Web Services managed key (`aws/s3`
     *       >   key) in KMS to protect the data.
     *       > - To perform a multipart upload with encryption by using an Amazon Web Services KMS key, the requester must
     *       >   have permission to the `kms:Decrypt` and `kms:GenerateDataKey*` actions on the key. These permissions are
     *       >   required because Amazon S3 must decrypt and read data from the encrypted file parts before it completes the
     *       >   multipart upload. For more information, see Multipart upload API and permissions [^12] and Protecting data
     *       >   using server-side encryption with Amazon Web Services KMS [^13] in the *Amazon S3 User Guide*.
     *       > - If your Identity and Access Management (IAM) user or role is in the same Amazon Web Services account as the
     *       >   KMS key, then you must have these permissions on the key policy. If your IAM user or role is in a different
     *       >   account from the key, then you must have the permissions on both the key policy and your IAM user or role.
     *       > - All `GET` and `PUT` requests for an object protected by KMS fail if you don't make them by using Secure
     *       >   Sockets Layer (SSL), Transport Layer Security (TLS), or Signature Version 4. For information about
     *       >   configuring any of the officially supported Amazon Web Services SDKs and Amazon Web Services CLI, see
     *       >   Specifying the Signature Version in Request Authentication [^14] in the *Amazon S3 User Guide*.
     *       >
     *
     *       For more information about server-side encryption with KMS keys (SSE-KMS), see Protecting Data Using
     *       Server-Side Encryption with KMS keys [^15] in the *Amazon S3 User Guide*.
     *     - Use customer-provided encryption keys (SSE-C) – If you want to manage your own encryption keys, provide all
     *       the following headers in the request.
     *
     *       - `x-amz-server-side-encryption-customer-algorithm`
     *       - `x-amz-server-side-encryption-customer-key`
     *       - `x-amz-server-side-encryption-customer-key-MD5`
     *
     *       For more information about server-side encryption with customer-provided encryption keys (SSE-C), see
     *       Protecting data using server-side encryption with customer-provided encryption keys (SSE-C) [^16] in the
     *       *Amazon S3 User Guide*.
     *
     *   - **Directory buckets** - For directory buckets, there are only two supported options for server-side encryption:
     *     server-side encryption with Amazon S3 managed keys (SSE-S3) (`AES256`) and server-side encryption with KMS keys
     *     (SSE-KMS) (`aws:kms`). We recommend that the bucket's default encryption uses the desired encryption
     *     configuration and you don't override the bucket default encryption in your `CreateSession` requests or `PUT`
     *     object requests. Then, new objects are automatically encrypted with the desired encryption settings. For more
     *     information, see Protecting data with server-side encryption [^17] in the *Amazon S3 User Guide*. For more
     *     information about the encryption overriding behaviors in directory buckets, see Specifying server-side encryption
     *     with KMS for new object uploads [^18].
     *
     *     In the Zonal endpoint API calls (except CopyObject [^19] and UploadPartCopy [^20]) using the REST API, the
     *     encryption request headers must match the encryption settings that are specified in the `CreateSession` request.
     *     You can't override the values of the encryption settings (`x-amz-server-side-encryption`,
     *     `x-amz-server-side-encryption-aws-kms-key-id`, `x-amz-server-side-encryption-context`, and
     *     `x-amz-server-side-encryption-bucket-key-enabled`) that are specified in the `CreateSession` request. You don't
     *     need to explicitly specify these encryption settings values in Zonal endpoint API calls, and Amazon S3 will use
     *     the encryption settings values from the `CreateSession` request to protect new objects in the directory bucket.
     *
     *     > When you use the CLI or the Amazon Web Services SDKs, for `CreateSession`, the session token refreshes
     *     > automatically to avoid service interruptions when a session expires. The CLI or the Amazon Web Services SDKs
     *     > use the bucket's default encryption configuration for the `CreateSession` request. It's not supported to
     *     > override the encryption settings values in the `CreateSession` request. So in the Zonal endpoint API calls
     *     > (except CopyObject [^21] and UploadPartCopy [^22]), the encryption request headers must match the default
     *     > encryption configuration of the directory bucket.
     *
     *     > For directory buckets, when you perform a `CreateMultipartUpload` operation and an `UploadPartCopy` operation,
     *     > the request headers you provide in the `CreateMultipartUpload` request must match the default encryption
     *     > configuration of the destination bucket.
     *
     *
     * - `HTTP Host header syntax`:
     *
     *   **Directory buckets ** - The HTTP Host header syntax is `*Bucket_name*.s3express-*az_id*.*region*.amazonaws.com`.
     *
     * The following operations are related to `CreateMultipartUpload`:
     *
     * - UploadPart [^23]
     * - CompleteMultipartUpload [^24]
     * - AbortMultipartUpload [^25]
     * - ListParts [^26]
     * - ListMultipartUploads [^27]
     *
     * [^1]: https://docs.aws.amazon.com/AmazonS3/latest/API/API_UploadPart.html
     * [^2]: https://docs.aws.amazon.com/AmazonS3/latest/dev/mpuoverview.html
     * [^3]: https://docs.aws.amazon.com/AmazonS3/latest/dev/mpuoverview.html#mpu-abort-incomplete-mpu-lifecycle-config
     * [^4]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/s3-express-Regions-and-Zones.html
     * [^5]: https://docs.aws.amazon.com/AmazonS3/latest/API/sig-v4-authenticating-requests.html
     * [^6]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/mpuoverview.html#mpuAndPermissions
     * [^7]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/UsingKMSEncryption.html
     * [^8]: https://docs.aws.amazon.com/AmazonS3/latest/API/API_CreateSession.html
     * [^9]: https://docs.aws.amazon.com/AmazonS3/latest/API/API_CreateSession.html
     * [^10]: https://docs.aws.amazon.com/AmazonS3/latest/API/API_UploadPart.html
     * [^11]: https://docs.aws.amazon.com/AmazonS3/latest/API/API_UploadPartCopy.html
     * [^12]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/mpuoverview.html#mpuAndPermissions
     * [^13]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/UsingKMSEncryption.html
     * [^14]: https://docs.aws.amazon.com/AmazonS3/latest/dev/UsingAWSSDK.html#specify-signature-version
     * [^15]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/UsingKMSEncryption.html
     * [^16]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/ServerSideEncryptionCustomerKeys.html
     * [^17]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/s3-express-serv-side-encryption.html
     * [^18]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/s3-express-specifying-kms-encryption.html
     * [^19]: https://docs.aws.amazon.com/AmazonS3/latest/API/API_CopyObject.html
     * [^20]: https://docs.aws.amazon.com/AmazonS3/latest/API/API_UploadPartCopy.html
     * [^21]: https://docs.aws.amazon.com/AmazonS3/latest/API/API_CopyObject.html
     * [^22]: https://docs.aws.amazon.com/AmazonS3/latest/API/API_UploadPartCopy.html
     * [^23]: https://docs.aws.amazon.com/AmazonS3/latest/API/API_UploadPart.html
     * [^24]: https://docs.aws.amazon.com/AmazonS3/latest/API/API_CompleteMultipartUpload.html
     * [^25]: https://docs.aws.amazon.com/AmazonS3/latest/API/API_AbortMultipartUpload.html
     * [^26]: https://docs.aws.amazon.com/AmazonS3/latest/API/API_ListParts.html
     * [^27]: https://docs.aws.amazon.com/AmazonS3/latest/API/API_ListMultipartUploads.html
     *
     * @see http://docs.amazonwebservices.com/AmazonS3/latest/API/mpUploadInitiate.html
     * @see https://docs.aws.amazon.com/AmazonS3/latest/API/API_CreateMultipartUpload.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-s3-2006-03-01.html#createmultipartupload
     *
     * @param array{
     *   ACL?: null|ObjectCannedACL::*,
     *   Bucket: string,
     *   CacheControl?: null|string,
     *   ContentDisposition?: null|string,
     *   ContentEncoding?: null|string,
     *   ContentLanguage?: null|string,
     *   ContentType?: null|string,
     *   Expires?: null|\DateTimeImmutable|string,
     *   GrantFullControl?: null|string,
     *   GrantRead?: null|string,
     *   GrantReadACP?: null|string,
     *   GrantWriteACP?: null|string,
     *   Key: string,
     *   Metadata?: null|array<string, string>,
     *   ServerSideEncryption?: null|ServerSideEncryption::*,
     *   StorageClass?: null|StorageClass::*,
     *   WebsiteRedirectLocation?: null|string,
     *   SSECustomerAlgorithm?: null|string,
     *   SSECustomerKey?: null|string,
     *   SSECustomerKeyMD5?: null|string,
     *   SSEKMSKeyId?: null|string,
     *   SSEKMSEncryptionContext?: null|string,
     *   BucketKeyEnabled?: null|bool,
     *   RequestPayer?: null|RequestPayer::*,
     *   Tagging?: null|string,
     *   ObjectLockMode?: null|ObjectLockMode::*,
     *   ObjectLockRetainUntilDate?: null|\DateTimeImmutable|string,
     *   ObjectLockLegalHoldStatus?: null|ObjectLockLegalHoldStatus::*,
     *   ExpectedBucketOwner?: null|string,
     *   ChecksumAlgorithm?: null|ChecksumAlgorithm::*,
     *   '@region'?: string|null,
     * }|CreateMultipartUploadRequest $input
     */
    public function createMultipartUpload($input): CreateMultipartUploadOutput
    {
        $input = CreateMultipartUploadRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'CreateMultipartUpload', 'region' => $input->getRegion()]));

        return new CreateMultipartUploadOutput($response);
    }

    /**
     * Deletes the S3 bucket. All objects (including all object versions and delete markers) in the bucket must be deleted
     * before the bucket itself can be deleted.
     *
     * > - **Directory buckets** - If multipart uploads in a directory bucket are in progress, you can't delete the bucket
     * >   until all the in-progress multipart uploads are aborted or completed.
     * > - **Directory buckets ** - For directory buckets, you must make requests for this API operation to the Regional
     * >   endpoint. These endpoints support path-style requests in the format
     * >   `https://s3express-control.*region_code*.amazonaws.com/*bucket-name*`. Virtual-hosted-style requests aren't
     * >   supported. For more information, see Regional and Zonal endpoints [^1] in the *Amazon S3 User Guide*.
     * >
     *
     * - `Permissions`:
     *
     *   - **General purpose bucket permissions** - You must have the `s3:DeleteBucket` permission on the specified bucket
     *     in a policy.
     *   - **Directory bucket permissions** - You must have the `s3express:DeleteBucket` permission in an IAM identity-based
     *     policy instead of a bucket policy. Cross-account access to this API operation isn't supported. This operation can
     *     only be performed by the Amazon Web Services account that owns the resource. For more information about directory
     *     bucket policies and permissions, see Amazon Web Services Identity and Access Management (IAM) for S3 Express One
     *     Zone [^2] in the *Amazon S3 User Guide*.
     *
     * - `HTTP Host header syntax`:
     *
     *   **Directory buckets ** - The HTTP Host header syntax is `s3express-control.*region*.amazonaws.com`.
     *
     * The following operations are related to `DeleteBucket`:
     *
     * - CreateBucket [^3]
     * - DeleteObject [^4]
     *
     * [^1]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/s3-express-Regions-and-Zones.html
     * [^2]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/s3-express-security-iam.html
     * [^3]: https://docs.aws.amazon.com/AmazonS3/latest/API/API_CreateBucket.html
     * [^4]: https://docs.aws.amazon.com/AmazonS3/latest/API/API_DeleteObject.html
     *
     * @see http://docs.amazonwebservices.com/AmazonS3/latest/API/RESTBucketDELETE.html
     * @see https://docs.aws.amazon.com/AmazonS3/latest/API/API_DeleteBucket.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-s3-2006-03-01.html#deletebucket
     *
     * @param array{
     *   Bucket: string,
     *   ExpectedBucketOwner?: null|string,
     *   '@region'?: string|null,
     * }|DeleteBucketRequest $input
     */
    public function deleteBucket($input): Result
    {
        $input = DeleteBucketRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'DeleteBucket', 'region' => $input->getRegion()]));

        return new Result($response);
    }

    /**
     * > This operation is not supported for directory buckets.
     *
     * Deletes the `cors` configuration information set for the bucket.
     *
     * To use this operation, you must have permission to perform the `s3:PutBucketCORS` action. The bucket owner has this
     * permission by default and can grant this permission to others.
     *
     * For information about `cors`, see Enabling Cross-Origin Resource Sharing [^1] in the *Amazon S3 User Guide*.
     *
     * **Related Resources**
     *
     * - PutBucketCors [^2]
     * - RESTOPTIONSobject [^3]
     *
     * [^1]: https://docs.aws.amazon.com/AmazonS3/latest/dev/cors.html
     * [^2]: https://docs.aws.amazon.com/AmazonS3/latest/API/API_PutBucketCors.html
     * [^3]: https://docs.aws.amazon.com/AmazonS3/latest/API/RESTOPTIONSobject.html
     *
     * @see http://docs.amazonwebservices.com/AmazonS3/latest/API/RESTBucketDELETEcors.html
     * @see https://docs.aws.amazon.com/AmazonS3/latest/API/API_DeleteBucketCors.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-s3-2006-03-01.html#deletebucketcors
     *
     * @param array{
     *   Bucket: string,
     *   ExpectedBucketOwner?: null|string,
     *   '@region'?: string|null,
     * }|DeleteBucketCorsRequest $input
     */
    public function deleteBucketCors($input): Result
    {
        $input = DeleteBucketCorsRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'DeleteBucketCors', 'region' => $input->getRegion()]));

        return new Result($response);
    }

    /**
     * Removes an object from a bucket. The behavior depends on the bucket's versioning state. For more information, see
     * Best practices to consider before deleting an object [^1].
     *
     * To remove a specific version, you must use the `versionId` query parameter. Using this query parameter permanently
     * deletes the version. If the object deleted is a delete marker, Amazon S3 sets the response header
     * `x-amz-delete-marker` to true. If the object you want to delete is in a bucket where the bucket versioning
     * configuration is MFA delete enabled, you must include the `x-amz-mfa` request header in the DELETE `versionId`
     * request. Requests that include `x-amz-mfa` must use HTTPS. For more information about MFA delete and to see example
     * requests, see Using MFA delete [^2] and Sample request [^3] in the *Amazon S3 User Guide*.
     *
     * > - S3 Versioning isn't enabled and supported for directory buckets. For this API operation, only the `null` value of
     * >   the version ID is supported by directory buckets. You can only specify `null` to the `versionId` query parameter
     * >   in the request.
     * > - For directory buckets, you must make requests for this API operation to the Zonal endpoint. These endpoints
     * >   support virtual-hosted-style requests in the format
     * >   `https://*bucket_name*.s3express-*az_id*.*region*.amazonaws.com/*key-name*`. Path-style requests are not
     * >   supported. For more information, see Regional and Zonal endpoints [^4] in the *Amazon S3 User Guide*.
     * > - MFA delete is not supported by directory buckets.
     * >
     *
     * - `Permissions`:
     *
     *   - **General purpose bucket permissions** - The following permissions are required in your policies when your
     *     `DeleteObjects` request includes specific headers.
     *
     *     - **`s3:DeleteObject`** - To delete an object from a bucket, you must always have the `s3:DeleteObject`
     *       permission.
     *
     *       > You can also use PutBucketLifecycle to delete objects in Amazon S3.
     *
     *     - **`s3:DeleteObjectVersion`** - To delete a specific version of an object from a versioning-enabled bucket, you
     *       must have the `s3:DeleteObjectVersion` permission.
     *     - If you want to block users or accounts from removing or deleting objects from your bucket, you must deny them
     *       the `s3:DeleteObject`, `s3:DeleteObjectVersion`, and `s3:PutLifeCycleConfiguration` permissions.
     *
     *   - **Directory buckets permissions** - To grant access to this API operation on a directory bucket, we recommend
     *     that you use the CreateSession API operation for session-based authorization.
     *
     * - `HTTP Host header syntax`:
     *
     *   **Directory buckets ** - The HTTP Host header syntax is `*Bucket_name*.s3express-*az_id*.*region*.amazonaws.com`.
     *
     * The following action is related to `DeleteObject`:
     *
     * - PutObject [^5]
     *
     * [^1]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/DeletingObjects.html#DeletingObjects-best-practices
     * [^2]: https://docs.aws.amazon.com/AmazonS3/latest/dev/UsingMFADelete.html
     * [^3]: https://docs.aws.amazon.com/AmazonS3/latest/API/RESTObjectDELETE.html#ExampleVersionObjectDelete
     * [^4]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/s3-express-Regions-and-Zones.html
     * [^5]: https://docs.aws.amazon.com/AmazonS3/latest/API/API_PutObject.html
     *
     * @see http://docs.amazonwebservices.com/AmazonS3/latest/API/RESTObjectDELETE.html
     * @see https://docs.aws.amazon.com/AmazonS3/latest/API/API_DeleteObject.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-s3-2006-03-01.html#deleteobject
     *
     * @param array{
     *   Bucket: string,
     *   Key: string,
     *   MFA?: null|string,
     *   VersionId?: null|string,
     *   RequestPayer?: null|RequestPayer::*,
     *   BypassGovernanceRetention?: null|bool,
     *   ExpectedBucketOwner?: null|string,
     *   IfMatch?: null|string,
     *   IfMatchLastModifiedTime?: null|\DateTimeImmutable|string,
     *   IfMatchSize?: null|int,
     *   '@region'?: string|null,
     * }|DeleteObjectRequest $input
     */
    public function deleteObject($input): DeleteObjectOutput
    {
        $input = DeleteObjectRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'DeleteObject', 'region' => $input->getRegion()]));

        return new DeleteObjectOutput($response);
    }

    /**
     * > This operation is not supported for directory buckets.
     *
     * Removes the entire tag set from the specified object. For more information about managing object tags, see Object
     * Tagging [^1].
     *
     * To use this operation, you must have permission to perform the `s3:DeleteObjectTagging` action.
     *
     * To delete tags of a specific object version, add the `versionId` query parameter in the request. You will need
     * permission for the `s3:DeleteObjectVersionTagging` action.
     *
     * The following operations are related to `DeleteObjectTagging`:
     *
     * - PutObjectTagging [^2]
     * - GetObjectTagging [^3]
     *
     * [^1]: https://docs.aws.amazon.com/AmazonS3/latest/dev/object-tagging.html
     * [^2]: https://docs.aws.amazon.com/AmazonS3/latest/API/API_PutObjectTagging.html
     * [^3]: https://docs.aws.amazon.com/AmazonS3/latest/API/API_GetObjectTagging.html
     *
     * @see https://docs.aws.amazon.com/AmazonS3/latest/API/API_DeleteObjectTagging.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-s3-2006-03-01.html#deleteobjecttagging
     *
     * @param array{
     *   Bucket: string,
     *   Key: string,
     *   VersionId?: null|string,
     *   ExpectedBucketOwner?: null|string,
     *   '@region'?: string|null,
     * }|DeleteObjectTaggingRequest $input
     */
    public function deleteObjectTagging($input): DeleteObjectTaggingOutput
    {
        $input = DeleteObjectTaggingRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'DeleteObjectTagging', 'region' => $input->getRegion()]));

        return new DeleteObjectTaggingOutput($response);
    }

    /**
     * This operation enables you to delete multiple objects from a bucket using a single HTTP request. If you know the
     * object keys that you want to delete, then this operation provides a suitable alternative to sending individual delete
     * requests, reducing per-request overhead.
     *
     * The request can contain a list of up to 1000 keys that you want to delete. In the XML, you provide the object key
     * names, and optionally, version IDs if you want to delete a specific version of the object from a versioning-enabled
     * bucket. For each key, Amazon S3 performs a delete operation and returns the result of that delete, success or
     * failure, in the response. Note that if the object specified in the request is not found, Amazon S3 returns the result
     * as deleted.
     *
     * > - **Directory buckets** - S3 Versioning isn't enabled and supported for directory buckets.
     * > - **Directory buckets** - For directory buckets, you must make requests for this API operation to the Zonal
     * >   endpoint. These endpoints support virtual-hosted-style requests in the format
     * >   `https://*bucket_name*.s3express-*az_id*.*region*.amazonaws.com/*key-name*`. Path-style requests are not
     * >   supported. For more information, see Regional and Zonal endpoints [^1] in the *Amazon S3 User Guide*.
     * >
     *
     * The operation supports two modes for the response: verbose and quiet. By default, the operation uses verbose mode in
     * which the response includes the result of deletion of each key in your request. In quiet mode the response includes
     * only keys where the delete operation encountered an error. For a successful deletion in a quiet mode, the operation
     * does not return any information about the delete in the response body.
     *
     * When performing this action on an MFA Delete enabled bucket, that attempts to delete any versioned objects, you must
     * include an MFA token. If you do not provide one, the entire request will fail, even if there are non-versioned
     * objects you are trying to delete. If you provide an invalid token, whether there are versioned keys in the request or
     * not, the entire Multi-Object Delete request will fail. For information about MFA Delete, see MFA Delete [^2] in the
     * *Amazon S3 User Guide*.
     *
     * > **Directory buckets** - MFA delete is not supported by directory buckets.
     *
     * - `Permissions`:
     *
     *   - **General purpose bucket permissions** - The following permissions are required in your policies when your
     *     `DeleteObjects` request includes specific headers.
     *
     *     - **`s3:DeleteObject`** - To delete an object from a bucket, you must always specify the `s3:DeleteObject`
     *       permission.
     *     - **`s3:DeleteObjectVersion`** - To delete a specific version of an object from a versioning-enabled bucket, you
     *       must specify the `s3:DeleteObjectVersion` permission.
     *
     *   - **Directory bucket permissions** - To grant access to this API operation on a directory bucket, we recommend that
     *     you use the `CreateSession` [^3] API operation for session-based authorization. Specifically, you grant the
     *     `s3express:CreateSession` permission to the directory bucket in a bucket policy or an IAM identity-based policy.
     *     Then, you make the `CreateSession` API call on the bucket to obtain a session token. With the session token in
     *     your request header, you can make API requests to this operation. After the session token expires, you make
     *     another `CreateSession` API call to generate a new session token for use. Amazon Web Services CLI or SDKs create
     *     session and refresh the session token automatically to avoid service interruptions when a session expires. For
     *     more information about authorization, see `CreateSession` [^4].
     *
     * - `Content-MD5 request header`:
     *
     *   - **General purpose bucket** - The Content-MD5 request header is required for all Multi-Object Delete requests.
     *     Amazon S3 uses the header value to ensure that your request body has not been altered in transit.
     *   - **Directory bucket** - The Content-MD5 request header or a additional checksum request header (including
     *     `x-amz-checksum-crc32`, `x-amz-checksum-crc32c`, `x-amz-checksum-sha1`, or `x-amz-checksum-sha256`) is required
     *     for all Multi-Object Delete requests.
     *
     * - `HTTP Host header syntax`:
     *
     *   **Directory buckets ** - The HTTP Host header syntax is `*Bucket_name*.s3express-*az_id*.*region*.amazonaws.com`.
     *
     * The following operations are related to `DeleteObjects`:
     *
     * - CreateMultipartUpload [^5]
     * - UploadPart [^6]
     * - CompleteMultipartUpload [^7]
     * - ListParts [^8]
     * - AbortMultipartUpload [^9]
     *
     * [^1]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/s3-express-Regions-and-Zones.html
     * [^2]: https://docs.aws.amazon.com/AmazonS3/latest/dev/Versioning.html#MultiFactorAuthenticationDelete
     * [^3]: https://docs.aws.amazon.com/AmazonS3/latest/API/API_CreateSession.html
     * [^4]: https://docs.aws.amazon.com/AmazonS3/latest/API/API_CreateSession.html
     * [^5]: https://docs.aws.amazon.com/AmazonS3/latest/API/API_CreateMultipartUpload.html
     * [^6]: https://docs.aws.amazon.com/AmazonS3/latest/API/API_UploadPart.html
     * [^7]: https://docs.aws.amazon.com/AmazonS3/latest/API/API_CompleteMultipartUpload.html
     * [^8]: https://docs.aws.amazon.com/AmazonS3/latest/API/API_ListParts.html
     * [^9]: https://docs.aws.amazon.com/AmazonS3/latest/API/API_AbortMultipartUpload.html
     *
     * @see http://docs.amazonwebservices.com/AmazonS3/latest/API/multiobjectdeleteapi.html
     * @see https://docs.aws.amazon.com/AmazonS3/latest/API/API_DeleteObjects.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-s3-2006-03-01.html#deleteobjects
     *
     * @param array{
     *   Bucket: string,
     *   Delete: Delete|array,
     *   MFA?: null|string,
     *   RequestPayer?: null|RequestPayer::*,
     *   BypassGovernanceRetention?: null|bool,
     *   ExpectedBucketOwner?: null|string,
     *   ChecksumAlgorithm?: null|ChecksumAlgorithm::*,
     *   '@region'?: string|null,
     * }|DeleteObjectsRequest $input
     */
    public function deleteObjects($input): DeleteObjectsOutput
    {
        $input = DeleteObjectsRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'DeleteObjects', 'region' => $input->getRegion()]));

        return new DeleteObjectsOutput($response);
    }

    /**
     * > This operation is not supported for directory buckets.
     *
     * Returns the Cross-Origin Resource Sharing (CORS) configuration information set for the bucket.
     *
     * To use this operation, you must have permission to perform the `s3:GetBucketCORS` action. By default, the bucket
     * owner has this permission and can grant it to others.
     *
     * When you use this API operation with an access point, provide the alias of the access point in place of the bucket
     * name.
     *
     * When you use this API operation with an Object Lambda access point, provide the alias of the Object Lambda access
     * point in place of the bucket name. If the Object Lambda access point alias in a request is not valid, the error code
     * `InvalidAccessPointAliasError` is returned. For more information about `InvalidAccessPointAliasError`, see List of
     * Error Codes [^1].
     *
     * For more information about CORS, see Enabling Cross-Origin Resource Sharing [^2].
     *
     * The following operations are related to `GetBucketCors`:
     *
     * - PutBucketCors [^3]
     * - DeleteBucketCors [^4]
     *
     * [^1]: https://docs.aws.amazon.com/AmazonS3/latest/API/ErrorResponses.html#ErrorCodeList
     * [^2]: https://docs.aws.amazon.com/AmazonS3/latest/dev/cors.html
     * [^3]: https://docs.aws.amazon.com/AmazonS3/latest/API/API_PutBucketCors.html
     * [^4]: https://docs.aws.amazon.com/AmazonS3/latest/API/API_DeleteBucketCors.html
     *
     * @see http://docs.amazonwebservices.com/AmazonS3/latest/API/RESTBucketGETcors.html
     * @see https://docs.aws.amazon.com/AmazonS3/latest/API/API_GetBucketCors.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-s3-2006-03-01.html#getbucketcors
     *
     * @param array{
     *   Bucket: string,
     *   ExpectedBucketOwner?: null|string,
     *   '@region'?: string|null,
     * }|GetBucketCorsRequest $input
     */
    public function getBucketCors($input): GetBucketCorsOutput
    {
        $input = GetBucketCorsRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'GetBucketCors', 'region' => $input->getRegion()]));

        return new GetBucketCorsOutput($response);
    }

    /**
     * Returns the default encryption configuration for an Amazon S3 bucket. By default, all buckets have a default
     * encryption configuration that uses server-side encryption with Amazon S3 managed keys (SSE-S3).
     *
     * > - **General purpose buckets** - For information about the bucket default encryption feature, see Amazon S3 Bucket
     * >   Default Encryption [^1] in the *Amazon S3 User Guide*.
     * > - **Directory buckets** - For directory buckets, there are only two supported options for server-side encryption:
     * >   SSE-S3 and SSE-KMS. For information about the default encryption configuration in directory buckets, see Setting
     * >   default server-side encryption behavior for directory buckets [^2].
     * >
     *
     * - `Permissions`:
     *
     *   - **General purpose bucket permissions** - The `s3:GetEncryptionConfiguration` permission is required in a policy.
     *     The bucket owner has this permission by default. The bucket owner can grant this permission to others. For more
     *     information about permissions, see Permissions Related to Bucket Operations [^3] and Managing Access Permissions
     *     to Your Amazon S3 Resources [^4].
     *   - **Directory bucket permissions** - To grant access to this API operation, you must have the
     *     `s3express:GetEncryptionConfiguration` permission in an IAM identity-based policy instead of a bucket policy.
     *     Cross-account access to this API operation isn't supported. This operation can only be performed by the Amazon
     *     Web Services account that owns the resource. For more information about directory bucket policies and
     *     permissions, see Amazon Web Services Identity and Access Management (IAM) for S3 Express One Zone [^5] in the
     *     *Amazon S3 User Guide*.
     *
     * - `HTTP Host header syntax`:
     *
     *   **Directory buckets ** - The HTTP Host header syntax is `s3express-control.*region*.amazonaws.com`.
     *
     * The following operations are related to `GetBucketEncryption`:
     *
     * - PutBucketEncryption [^6]
     * - DeleteBucketEncryption [^7]
     *
     * [^1]: https://docs.aws.amazon.com/AmazonS3/latest/dev/bucket-encryption.html
     * [^2]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/s3-express-bucket-encryption.html
     * [^3]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/using-with-s3-actions.html#using-with-s3-actions-related-to-bucket-subresources
     * [^4]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/s3-access-control.html
     * [^5]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/s3-express-security-iam.html
     * [^6]: https://docs.aws.amazon.com/AmazonS3/latest/API/API_PutBucketEncryption.html
     * [^7]: https://docs.aws.amazon.com/AmazonS3/latest/API/API_DeleteBucketEncryption.html
     *
     * @see https://docs.aws.amazon.com/AmazonS3/latest/API/API_GetBucketEncryption.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-s3-2006-03-01.html#getbucketencryption
     *
     * @param array{
     *   Bucket: string,
     *   ExpectedBucketOwner?: null|string,
     *   '@region'?: string|null,
     * }|GetBucketEncryptionRequest $input
     */
    public function getBucketEncryption($input): GetBucketEncryptionOutput
    {
        $input = GetBucketEncryptionRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'GetBucketEncryption', 'region' => $input->getRegion()]));

        return new GetBucketEncryptionOutput($response);
    }

    /**
     * Retrieves an object from Amazon S3.
     *
     * In the `GetObject` request, specify the full key name for the object.
     *
     * **General purpose buckets** - Both the virtual-hosted-style requests and the path-style requests are supported. For a
     * virtual hosted-style request example, if you have the object `photos/2006/February/sample.jpg`, specify the object
     * key name as `/photos/2006/February/sample.jpg`. For a path-style request example, if you have the object
     * `photos/2006/February/sample.jpg` in the bucket named `examplebucket`, specify the object key name as
     * `/examplebucket/photos/2006/February/sample.jpg`. For more information about request types, see HTTP Host Header
     * Bucket Specification [^1] in the *Amazon S3 User Guide*.
     *
     * **Directory buckets** - Only virtual-hosted-style requests are supported. For a virtual hosted-style request example,
     * if you have the object `photos/2006/February/sample.jpg` in the bucket named `examplebucket--use1-az5--x-s3`, specify
     * the object key name as `/photos/2006/February/sample.jpg`. Also, when you make requests to this API operation, your
     * requests are sent to the Zonal endpoint. These endpoints support virtual-hosted-style requests in the format
     * `https://*bucket_name*.s3express-*az_id*.*region*.amazonaws.com/*key-name*`. Path-style requests are not supported.
     * For more information, see Regional and Zonal endpoints [^2] in the *Amazon S3 User Guide*.
     *
     * - `Permissions`:
     *
     *   - **General purpose bucket permissions** - You must have the required permissions in a policy. To use `GetObject`,
     *     you must have the `READ` access to the object (or version). If you grant `READ` access to the anonymous user, the
     *     `GetObject` operation returns the object without using an authorization header. For more information, see
     *     Specifying permissions in a policy [^3] in the *Amazon S3 User Guide*.
     *
     *     If you include a `versionId` in your request header, you must have the `s3:GetObjectVersion` permission to access
     *     a specific version of an object. The `s3:GetObject` permission is not required in this scenario.
     *
     *     If you request the current version of an object without a specific `versionId` in the request header, only the
     *     `s3:GetObject` permission is required. The `s3:GetObjectVersion` permission is not required in this scenario.
     *
     *     If the object that you request doesn’t exist, the error that Amazon S3 returns depends on whether you also have
     *     the `s3:ListBucket` permission.
     *
     *     - If you have the `s3:ListBucket` permission on the bucket, Amazon S3 returns an HTTP status code `404 Not Found`
     *       error.
     *     - If you don’t have the `s3:ListBucket` permission, Amazon S3 returns an HTTP status code `403 Access Denied`
     *       error.
     *
     *   - **Directory bucket permissions** - To grant access to this API operation on a directory bucket, we recommend that
     *     you use the `CreateSession` [^4] API operation for session-based authorization. Specifically, you grant the
     *     `s3express:CreateSession` permission to the directory bucket in a bucket policy or an IAM identity-based policy.
     *     Then, you make the `CreateSession` API call on the bucket to obtain a session token. With the session token in
     *     your request header, you can make API requests to this operation. After the session token expires, you make
     *     another `CreateSession` API call to generate a new session token for use. Amazon Web Services CLI or SDKs create
     *     session and refresh the session token automatically to avoid service interruptions when a session expires. For
     *     more information about authorization, see `CreateSession` [^5].
     *
     *     If the object is encrypted using SSE-KMS, you must also have the `kms:GenerateDataKey` and `kms:Decrypt`
     *     permissions in IAM identity-based policies and KMS key policies for the KMS key.
     *
     * - `Storage classes`:
     *
     *   If the object you are retrieving is stored in the S3 Glacier Flexible Retrieval storage class, the S3 Glacier Deep
     *   Archive storage class, the S3 Intelligent-Tiering Archive Access tier, or the S3 Intelligent-Tiering Deep Archive
     *   Access tier, before you can retrieve the object you must first restore a copy using RestoreObject [^6]. Otherwise,
     *   this operation returns an `InvalidObjectState` error. For information about restoring archived objects, see
     *   Restoring Archived Objects [^7] in the *Amazon S3 User Guide*.
     *
     *   **Directory buckets ** - For directory buckets, only the S3 Express One Zone storage class is supported to store
     *   newly created objects. Unsupported storage class values won't write a destination object and will respond with the
     *   HTTP status code `400 Bad Request`.
     * - `Encryption`:
     *
     *   Encryption request headers, like `x-amz-server-side-encryption`, should not be sent for the `GetObject` requests,
     *   if your object uses server-side encryption with Amazon S3 managed encryption keys (SSE-S3), server-side encryption
     *   with Key Management Service (KMS) keys (SSE-KMS), or dual-layer server-side encryption with Amazon Web Services KMS
     *   keys (DSSE-KMS). If you include the header in your `GetObject` requests for the object that uses these types of
     *   keys, you’ll get an HTTP `400 Bad Request` error.
     *
     *   **Directory buckets** - For directory buckets, there are only two supported options for server-side encryption:
     *   SSE-S3 and SSE-KMS. SSE-C isn't supported. For more information, see Protecting data with server-side encryption
     *   [^8] in the *Amazon S3 User Guide*.
     * - `Overriding response header values through the request`:
     *
     *   There are times when you want to override certain response header values of a `GetObject` response. For example,
     *   you might override the `Content-Disposition` response header value through your `GetObject` request.
     *
     *   You can override values for a set of response headers. These modified response header values are included only in a
     *   successful response, that is, when the HTTP status code `200 OK` is returned. The headers you can override using
     *   the following query parameters in the request are a subset of the headers that Amazon S3 accepts when you create an
     *   object.
     *
     *   The response headers that you can override for the `GetObject` response are `Cache-Control`, `Content-Disposition`,
     *   `Content-Encoding`, `Content-Language`, `Content-Type`, and `Expires`.
     *
     *   To override values for a set of response headers in the `GetObject` response, you can use the following query
     *   parameters in the request.
     *
     *   - `response-cache-control`
     *   - `response-content-disposition`
     *   - `response-content-encoding`
     *   - `response-content-language`
     *   - `response-content-type`
     *   - `response-expires`
     *
     *   > When you use these parameters, you must sign the request by using either an Authorization header or a presigned
     *   > URL. These parameters cannot be used with an unsigned (anonymous) request.
     *
     * - `HTTP Host header syntax`:
     *
     *   **Directory buckets ** - The HTTP Host header syntax is `*Bucket_name*.s3express-*az_id*.*region*.amazonaws.com`.
     *
     * The following operations are related to `GetObject`:
     *
     * - ListBuckets [^9]
     * - GetObjectAcl [^10]
     *
     * [^1]: https://docs.aws.amazon.com/AmazonS3/latest/dev/VirtualHosting.html#VirtualHostingSpecifyBucket
     * [^2]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/s3-express-Regions-and-Zones.html
     * [^3]: https://docs.aws.amazon.com/AmazonS3/latest/dev/using-with-s3-actions.html
     * [^4]: https://docs.aws.amazon.com/AmazonS3/latest/API/API_CreateSession.html
     * [^5]: https://docs.aws.amazon.com/AmazonS3/latest/API/API_CreateSession.html
     * [^6]: https://docs.aws.amazon.com/AmazonS3/latest/API/API_RestoreObject.html
     * [^7]: https://docs.aws.amazon.com/AmazonS3/latest/dev/restoring-objects.html
     * [^8]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/s3-express-serv-side-encryption.html
     * [^9]: https://docs.aws.amazon.com/AmazonS3/latest/API/API_ListBuckets.html
     * [^10]: https://docs.aws.amazon.com/AmazonS3/latest/API/API_GetObjectAcl.html
     *
     * @see http://docs.amazonwebservices.com/AmazonS3/latest/API/RESTObjectGET.html
     * @see https://docs.aws.amazon.com/AmazonS3/latest/API/API_GetObject.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-s3-2006-03-01.html#getobject
     *
     * @param array{
     *   Bucket: string,
     *   IfMatch?: null|string,
     *   IfModifiedSince?: null|\DateTimeImmutable|string,
     *   IfNoneMatch?: null|string,
     *   IfUnmodifiedSince?: null|\DateTimeImmutable|string,
     *   Key: string,
     *   Range?: null|string,
     *   ResponseCacheControl?: null|string,
     *   ResponseContentDisposition?: null|string,
     *   ResponseContentEncoding?: null|string,
     *   ResponseContentLanguage?: null|string,
     *   ResponseContentType?: null|string,
     *   ResponseExpires?: null|\DateTimeImmutable|string,
     *   VersionId?: null|string,
     *   SSECustomerAlgorithm?: null|string,
     *   SSECustomerKey?: null|string,
     *   SSECustomerKeyMD5?: null|string,
     *   RequestPayer?: null|RequestPayer::*,
     *   PartNumber?: null|int,
     *   ExpectedBucketOwner?: null|string,
     *   ChecksumMode?: null|ChecksumMode::*,
     *   '@region'?: string|null,
     * }|GetObjectRequest $input
     *
     * @throws NoSuchKeyException
     * @throws InvalidObjectStateException
     */
    public function getObject($input): GetObjectOutput
    {
        $input = GetObjectRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'GetObject', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'NoSuchKey' => NoSuchKeyException::class,
            'InvalidObjectState' => InvalidObjectStateException::class,
        ]]));

        return new GetObjectOutput($response);
    }

    /**
     * > This operation is not supported for directory buckets.
     *
     * Returns the access control list (ACL) of an object. To use this operation, you must have `s3:GetObjectAcl`
     * permissions or `READ_ACP` access to the object. For more information, see Mapping of ACL permissions and access
     * policy permissions [^1] in the *Amazon S3 User Guide*
     *
     * This functionality is not supported for Amazon S3 on Outposts.
     *
     * By default, GET returns ACL information about the current version of an object. To return ACL information about a
     * different version, use the versionId subresource.
     *
     * > If your bucket uses the bucket owner enforced setting for S3 Object Ownership, requests to read ACLs are still
     * > supported and return the `bucket-owner-full-control` ACL with the owner being the account that created the bucket.
     * > For more information, see Controlling object ownership and disabling ACLs [^2] in the *Amazon S3 User Guide*.
     *
     * The following operations are related to `GetObjectAcl`:
     *
     * - GetObject [^3]
     * - GetObjectAttributes [^4]
     * - DeleteObject [^5]
     * - PutObject [^6]
     *
     * [^1]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/acl-overview.html#acl-access-policy-permission-mapping
     * [^2]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/about-object-ownership.html
     * [^3]: https://docs.aws.amazon.com/AmazonS3/latest/API/API_GetObject.html
     * [^4]: https://docs.aws.amazon.com/AmazonS3/latest/API/API_GetObjectAttributes.html
     * [^5]: https://docs.aws.amazon.com/AmazonS3/latest/API/API_DeleteObject.html
     * [^6]: https://docs.aws.amazon.com/AmazonS3/latest/API/API_PutObject.html
     *
     * @see http://docs.amazonwebservices.com/AmazonS3/latest/API/RESTObjectGETacl.html
     * @see https://docs.aws.amazon.com/AmazonS3/latest/API/API_GetObjectAcl.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-s3-2006-03-01.html#getobjectacl
     *
     * @param array{
     *   Bucket: string,
     *   Key: string,
     *   VersionId?: null|string,
     *   RequestPayer?: null|RequestPayer::*,
     *   ExpectedBucketOwner?: null|string,
     *   '@region'?: string|null,
     * }|GetObjectAclRequest $input
     *
     * @throws NoSuchKeyException
     */
    public function getObjectAcl($input): GetObjectAclOutput
    {
        $input = GetObjectAclRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'GetObjectAcl', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'NoSuchKey' => NoSuchKeyException::class,
        ]]));

        return new GetObjectAclOutput($response);
    }

    /**
     * > This operation is not supported for directory buckets.
     *
     * Returns the tag-set of an object. You send the GET request against the tagging subresource associated with the
     * object.
     *
     * To use this operation, you must have permission to perform the `s3:GetObjectTagging` action. By default, the GET
     * action returns information about current version of an object. For a versioned bucket, you can have multiple versions
     * of an object in your bucket. To retrieve tags of any other version, use the versionId query parameter. You also need
     * permission for the `s3:GetObjectVersionTagging` action.
     *
     * By default, the bucket owner has this permission and can grant this permission to others.
     *
     * For information about the Amazon S3 object tagging feature, see Object Tagging [^1].
     *
     * The following actions are related to `GetObjectTagging`:
     *
     * - DeleteObjectTagging [^2]
     * - GetObjectAttributes [^3]
     * - PutObjectTagging [^4]
     *
     * [^1]: https://docs.aws.amazon.com/AmazonS3/latest/dev/object-tagging.html
     * [^2]: https://docs.aws.amazon.com/AmazonS3/latest/API/API_DeleteObjectTagging.html
     * [^3]: https://docs.aws.amazon.com/AmazonS3/latest/API/API_GetObjectAttributes.html
     * [^4]: https://docs.aws.amazon.com/AmazonS3/latest/API/API_PutObjectTagging.html
     *
     * @see https://docs.aws.amazon.com/AmazonS3/latest/API/API_GetObjectTagging.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-s3-2006-03-01.html#getobjecttagging
     *
     * @param array{
     *   Bucket: string,
     *   Key: string,
     *   VersionId?: null|string,
     *   ExpectedBucketOwner?: null|string,
     *   RequestPayer?: null|RequestPayer::*,
     *   '@region'?: string|null,
     * }|GetObjectTaggingRequest $input
     */
    public function getObjectTagging($input): GetObjectTaggingOutput
    {
        $input = GetObjectTaggingRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'GetObjectTagging', 'region' => $input->getRegion()]));

        return new GetObjectTaggingOutput($response);
    }

    /**
     * The `HEAD` operation retrieves metadata from an object without returning the object itself. This operation is useful
     * if you're interested only in an object's metadata.
     *
     * > A `HEAD` request has the same options as a `GET` operation on an object. The response is identical to the `GET`
     * > response except that there is no response body. Because of this, if the `HEAD` request generates an error, it
     * > returns a generic code, such as `400 Bad Request`, `403 Forbidden`, `404 Not Found`, `405 Method Not Allowed`, `412
     * > Precondition Failed`, or `304 Not Modified`. It's not possible to retrieve the exact exception of these error
     * > codes.
     *
     * Request headers are limited to 8 KB in size. For more information, see Common Request Headers [^1].
     *
     * - `Permissions`:
     *
     *   - **General purpose bucket permissions** - To use `HEAD`, you must have the `s3:GetObject` permission. You need the
     *     relevant read object (or version) permission for this operation. For more information, see Actions, resources,
     *     and condition keys for Amazon S3 [^2] in the *Amazon S3 User Guide*. For more information about the permissions
     *     to S3 API operations by S3 resource types, see Required permissions for Amazon S3 API operations [^3] in the
     *     *Amazon S3 User Guide*.
     *
     *     If the object you request doesn't exist, the error that Amazon S3 returns depends on whether you also have the
     *     `s3:ListBucket` permission.
     *
     *     - If you have the `s3:ListBucket` permission on the bucket, Amazon S3 returns an HTTP status code `404 Not Found`
     *       error.
     *     - If you don’t have the `s3:ListBucket` permission, Amazon S3 returns an HTTP status code `403 Forbidden`
     *       error.
     *
     *   - **Directory bucket permissions** - To grant access to this API operation on a directory bucket, we recommend that
     *     you use the `CreateSession` [^4] API operation for session-based authorization. Specifically, you grant the
     *     `s3express:CreateSession` permission to the directory bucket in a bucket policy or an IAM identity-based policy.
     *     Then, you make the `CreateSession` API call on the bucket to obtain a session token. With the session token in
     *     your request header, you can make API requests to this operation. After the session token expires, you make
     *     another `CreateSession` API call to generate a new session token for use. Amazon Web Services CLI or SDKs create
     *     session and refresh the session token automatically to avoid service interruptions when a session expires. For
     *     more information about authorization, see `CreateSession` [^5].
     *
     *     If you enable `x-amz-checksum-mode` in the request and the object is encrypted with Amazon Web Services Key
     *     Management Service (Amazon Web Services KMS), you must also have the `kms:GenerateDataKey` and `kms:Decrypt`
     *     permissions in IAM identity-based policies and KMS key policies for the KMS key to retrieve the checksum of the
     *     object.
     *
     * - `Encryption`:
     *
     *   > Encryption request headers, like `x-amz-server-side-encryption`, should not be sent for `HEAD` requests if your
     *   > object uses server-side encryption with Key Management Service (KMS) keys (SSE-KMS), dual-layer server-side
     *   > encryption with Amazon Web Services KMS keys (DSSE-KMS), or server-side encryption with Amazon S3 managed
     *   > encryption keys (SSE-S3). The `x-amz-server-side-encryption` header is used when you `PUT` an object to S3 and
     *   > want to specify the encryption method. If you include this header in a `HEAD` request for an object that uses
     *   > these types of keys, you’ll get an HTTP `400 Bad Request` error. It's because the encryption method can't be
     *   > changed when you retrieve the object.
     *
     *   If you encrypt an object by using server-side encryption with customer-provided encryption keys (SSE-C) when you
     *   store the object in Amazon S3, then when you retrieve the metadata from the object, you must use the following
     *   headers to provide the encryption key for the server to be able to retrieve the object's metadata. The headers are:
     *
     *   - `x-amz-server-side-encryption-customer-algorithm`
     *   - `x-amz-server-side-encryption-customer-key`
     *   - `x-amz-server-side-encryption-customer-key-MD5`
     *
     *   For more information about SSE-C, see Server-Side Encryption (Using Customer-Provided Encryption Keys) [^6] in the
     *   *Amazon S3 User Guide*.
     *
     *   > **Directory bucket ** - For directory buckets, there are only two supported options for server-side encryption:
     *   > SSE-S3 and SSE-KMS. SSE-C isn't supported. For more information, see Protecting data with server-side encryption
     *   > [^7] in the *Amazon S3 User Guide*.
     *
     * - `Versioning`:
     *
     *   - If the current version of the object is a delete marker, Amazon S3 behaves as if the object was deleted and
     *     includes `x-amz-delete-marker: true` in the response.
     *   - If the specified version is a delete marker, the response returns a `405 Method Not Allowed` error and the
     *     `Last-Modified: timestamp` response header.
     *
     *   > - **Directory buckets** - Delete marker is not supported for directory buckets.
     *   > - **Directory buckets** - S3 Versioning isn't enabled and supported for directory buckets. For this API
     *   >   operation, only the `null` value of the version ID is supported by directory buckets. You can only specify
     *   >   `null` to the `versionId` query parameter in the request.
     *   >
     *
     * - `HTTP Host header syntax`:
     *
     *   **Directory buckets ** - The HTTP Host header syntax is `*Bucket_name*.s3express-*az_id*.*region*.amazonaws.com`.
     *
     *   > For directory buckets, you must make requests for this API operation to the Zonal endpoint. These endpoints
     *   > support virtual-hosted-style requests in the format
     *   > `https://*bucket_name*.s3express-*az_id*.*region*.amazonaws.com/*key-name*`. Path-style requests are not
     *   > supported. For more information, see Regional and Zonal endpoints [^8] in the *Amazon S3 User Guide*.
     *
     *
     * The following actions are related to `HeadObject`:
     *
     * - GetObject [^9]
     * - GetObjectAttributes [^10]
     *
     * [^1]: https://docs.aws.amazon.com/AmazonS3/latest/API/RESTCommonRequestHeaders.html
     * [^2]: https://docs.aws.amazon.com/AmazonS3/latest/dev/list_amazons3.html
     * [^3]: /AmazonS3/latest/userguide/using-with-s3-policy-actions.html
     * [^4]: https://docs.aws.amazon.com/AmazonS3/latest/API/API_CreateSession.html
     * [^5]: https://docs.aws.amazon.com/AmazonS3/latest/API/API_CreateSession.html
     * [^6]: https://docs.aws.amazon.com/AmazonS3/latest/dev/ServerSideEncryptionCustomerKeys.html
     * [^7]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/s3-express-serv-side-encryption.html
     * [^8]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/s3-express-Regions-and-Zones.html
     * [^9]: https://docs.aws.amazon.com/AmazonS3/latest/API/API_GetObject.html
     * [^10]: https://docs.aws.amazon.com/AmazonS3/latest/API/API_GetObjectAttributes.html
     *
     * @see http://docs.amazonwebservices.com/AmazonS3/latest/API/RESTObjectHEAD.html
     * @see https://docs.aws.amazon.com/AmazonS3/latest/API/API_HeadObject.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-s3-2006-03-01.html#headobject
     *
     * @param array{
     *   Bucket: string,
     *   IfMatch?: null|string,
     *   IfModifiedSince?: null|\DateTimeImmutable|string,
     *   IfNoneMatch?: null|string,
     *   IfUnmodifiedSince?: null|\DateTimeImmutable|string,
     *   Key: string,
     *   Range?: null|string,
     *   ResponseCacheControl?: null|string,
     *   ResponseContentDisposition?: null|string,
     *   ResponseContentEncoding?: null|string,
     *   ResponseContentLanguage?: null|string,
     *   ResponseContentType?: null|string,
     *   ResponseExpires?: null|\DateTimeImmutable|string,
     *   VersionId?: null|string,
     *   SSECustomerAlgorithm?: null|string,
     *   SSECustomerKey?: null|string,
     *   SSECustomerKeyMD5?: null|string,
     *   RequestPayer?: null|RequestPayer::*,
     *   PartNumber?: null|int,
     *   ExpectedBucketOwner?: null|string,
     *   ChecksumMode?: null|ChecksumMode::*,
     *   '@region'?: string|null,
     * }|HeadObjectRequest $input
     *
     * @throws NoSuchKeyException
     */
    public function headObject($input): HeadObjectOutput
    {
        $input = HeadObjectRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'HeadObject', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'NoSuchKey' => NoSuchKeyException::class,
            'http_status_code_404' => NoSuchKeyException::class,
        ]]));

        return new HeadObjectOutput($response);
    }

    /**
     * > This operation is not supported for directory buckets.
     *
     * Returns a list of all buckets owned by the authenticated sender of the request. To grant IAM permission to use this
     * operation, you must add the `s3:ListAllMyBuckets` policy action.
     *
     * For information about Amazon S3 buckets, see Creating, configuring, and working with Amazon S3 buckets [^1].
     *
     * ! We strongly recommend using only paginated `ListBuckets` requests. Unpaginated `ListBuckets` requests are only
     * ! supported for Amazon Web Services accounts set to the default general purpose bucket quota of 10,000. If you have
     * ! an approved general purpose bucket quota above 10,000, you must send paginated `ListBuckets` requests to list your
     * ! account’s buckets. All unpaginated `ListBuckets` requests will be rejected for Amazon Web Services accounts with
     * ! a general purpose bucket quota greater than 10,000.
     *
     * [^1]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/creating-buckets-s3.html
     *
     * @see http://docs.amazonwebservices.com/AmazonS3/latest/API/RESTServiceGET.html
     * @see https://docs.aws.amazon.com/AmazonS3/latest/API/API_ListBuckets.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-s3-2006-03-01.html#listbuckets
     *
     * @param array{
     *   MaxBuckets?: null|int,
     *   ContinuationToken?: null|string,
     *   Prefix?: null|string,
     *   BucketRegion?: null|string,
     *   '@region'?: string|null,
     * }|ListBucketsRequest $input
     */
    public function listBuckets($input = []): ListBucketsOutput
    {
        $input = ListBucketsRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'ListBuckets', 'region' => $input->getRegion()]));

        return new ListBucketsOutput($response, $this, $input);
    }

    /**
     * This operation lists in-progress multipart uploads in a bucket. An in-progress multipart upload is a multipart upload
     * that has been initiated by the `CreateMultipartUpload` request, but has not yet been completed or aborted.
     *
     * > **Directory buckets** - If multipart uploads in a directory bucket are in progress, you can't delete the bucket
     * > until all the in-progress multipart uploads are aborted or completed. To delete these in-progress multipart
     * > uploads, use the `ListMultipartUploads` operation to list the in-progress multipart uploads in the bucket and use
     * > the `AbortMultipartUpload` operation to abort all the in-progress multipart uploads.
     *
     * The `ListMultipartUploads` operation returns a maximum of 1,000 multipart uploads in the response. The limit of 1,000
     * multipart uploads is also the default value. You can further limit the number of uploads in a response by specifying
     * the `max-uploads` request parameter. If there are more than 1,000 multipart uploads that satisfy your
     * `ListMultipartUploads` request, the response returns an `IsTruncated` element with the value of `true`, a
     * `NextKeyMarker` element, and a `NextUploadIdMarker` element. To list the remaining multipart uploads, you need to
     * make subsequent `ListMultipartUploads` requests. In these requests, include two query parameters: `key-marker` and
     * `upload-id-marker`. Set the value of `key-marker` to the `NextKeyMarker` value from the previous response. Similarly,
     * set the value of `upload-id-marker` to the `NextUploadIdMarker` value from the previous response.
     *
     * > **Directory buckets** - The `upload-id-marker` element and the `NextUploadIdMarker` element aren't supported by
     * > directory buckets. To list the additional multipart uploads, you only need to set the value of `key-marker` to the
     * > `NextKeyMarker` value from the previous response.
     *
     * For more information about multipart uploads, see Uploading Objects Using Multipart Upload [^1] in the *Amazon S3
     * User Guide*.
     *
     * > **Directory buckets** - For directory buckets, you must make requests for this API operation to the Zonal endpoint.
     * > These endpoints support virtual-hosted-style requests in the format
     * > `https://*bucket_name*.s3express-*az_id*.*region*.amazonaws.com/*key-name*`. Path-style requests are not supported.
     * > For more information, see Regional and Zonal endpoints [^2] in the *Amazon S3 User Guide*.
     *
     * - `Permissions`:
     *
     *   - **General purpose bucket permissions** - For information about permissions required to use the multipart upload
     *     API, see Multipart Upload and Permissions [^3] in the *Amazon S3 User Guide*.
     *   - **Directory bucket permissions** - To grant access to this API operation on a directory bucket, we recommend that
     *     you use the `CreateSession` [^4] API operation for session-based authorization. Specifically, you grant the
     *     `s3express:CreateSession` permission to the directory bucket in a bucket policy or an IAM identity-based policy.
     *     Then, you make the `CreateSession` API call on the bucket to obtain a session token. With the session token in
     *     your request header, you can make API requests to this operation. After the session token expires, you make
     *     another `CreateSession` API call to generate a new session token for use. Amazon Web Services CLI or SDKs create
     *     session and refresh the session token automatically to avoid service interruptions when a session expires. For
     *     more information about authorization, see `CreateSession` [^5].
     *
     * - `Sorting of multipart uploads in response`:
     *
     *   - **General purpose bucket** - In the `ListMultipartUploads` response, the multipart uploads are sorted based on
     *     two criteria:
     *
     *     - Key-based sorting - Multipart uploads are initially sorted in ascending order based on their object keys.
     *     - Time-based sorting - For uploads that share the same object key, they are further sorted in ascending order
     *       based on the upload initiation time. Among uploads with the same key, the one that was initiated first will
     *       appear before the ones that were initiated later.
     *
     *   - **Directory bucket** - In the `ListMultipartUploads` response, the multipart uploads aren't sorted
     *     lexicographically based on the object keys.
     *
     * - `HTTP Host header syntax`:
     *
     *   **Directory buckets ** - The HTTP Host header syntax is `*Bucket_name*.s3express-*az_id*.*region*.amazonaws.com`.
     *
     * The following operations are related to `ListMultipartUploads`:
     *
     * - CreateMultipartUpload [^6]
     * - UploadPart [^7]
     * - CompleteMultipartUpload [^8]
     * - ListParts [^9]
     * - AbortMultipartUpload [^10]
     *
     * [^1]: https://docs.aws.amazon.com/AmazonS3/latest/dev/uploadobjusingmpu.html
     * [^2]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/s3-express-Regions-and-Zones.html
     * [^3]: https://docs.aws.amazon.com/AmazonS3/latest/dev/mpuAndPermissions.html
     * [^4]: https://docs.aws.amazon.com/AmazonS3/latest/API/API_CreateSession.html
     * [^5]: https://docs.aws.amazon.com/AmazonS3/latest/API/API_CreateSession.html
     * [^6]: https://docs.aws.amazon.com/AmazonS3/latest/API/API_CreateMultipartUpload.html
     * [^7]: https://docs.aws.amazon.com/AmazonS3/latest/API/API_UploadPart.html
     * [^8]: https://docs.aws.amazon.com/AmazonS3/latest/API/API_CompleteMultipartUpload.html
     * [^9]: https://docs.aws.amazon.com/AmazonS3/latest/API/API_ListParts.html
     * [^10]: https://docs.aws.amazon.com/AmazonS3/latest/API/API_AbortMultipartUpload.html
     *
     * @see http://docs.amazonwebservices.com/AmazonS3/latest/API/mpUploadListMPUpload.html
     * @see https://docs.aws.amazon.com/AmazonS3/latest/API/API_ListMultipartUploads.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-s3-2006-03-01.html#listmultipartuploads
     *
     * @param array{
     *   Bucket: string,
     *   Delimiter?: null|string,
     *   EncodingType?: null|EncodingType::*,
     *   KeyMarker?: null|string,
     *   MaxUploads?: null|int,
     *   Prefix?: null|string,
     *   UploadIdMarker?: null|string,
     *   ExpectedBucketOwner?: null|string,
     *   RequestPayer?: null|RequestPayer::*,
     *   '@region'?: string|null,
     * }|ListMultipartUploadsRequest $input
     */
    public function listMultipartUploads($input): ListMultipartUploadsOutput
    {
        $input = ListMultipartUploadsRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'ListMultipartUploads', 'region' => $input->getRegion()]));

        return new ListMultipartUploadsOutput($response, $this, $input);
    }

    /**
     * > This operation is not supported for directory buckets.
     *
     * Returns metadata about all versions of the objects in a bucket. You can also use request parameters as selection
     * criteria to return metadata about a subset of all the object versions.
     *
     * ! To use this operation, you must have permission to perform the `s3:ListBucketVersions` action. Be aware of the name
     * ! difference.
     *
     * > A `200 OK` response can contain valid or invalid XML. Make sure to design your application to parse the contents of
     * > the response and handle it appropriately.
     *
     * To use this operation, you must have READ access to the bucket.
     *
     * The following operations are related to `ListObjectVersions`:
     *
     * - ListObjectsV2 [^1]
     * - GetObject [^2]
     * - PutObject [^3]
     * - DeleteObject [^4]
     *
     * [^1]: https://docs.aws.amazon.com/AmazonS3/latest/API/API_ListObjectsV2.html
     * [^2]: https://docs.aws.amazon.com/AmazonS3/latest/API/API_GetObject.html
     * [^3]: https://docs.aws.amazon.com/AmazonS3/latest/API/API_PutObject.html
     * [^4]: https://docs.aws.amazon.com/AmazonS3/latest/API/API_DeleteObject.html
     *
     * @see http://docs.amazonwebservices.com/AmazonS3/latest/API/RESTBucketGETVersion.html
     * @see https://docs.aws.amazon.com/AmazonS3/latest/API/API_ListObjectVersions.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-s3-2006-03-01.html#listobjectversions
     *
     * @param array{
     *   Bucket: string,
     *   Delimiter?: null|string,
     *   EncodingType?: null|EncodingType::*,
     *   KeyMarker?: null|string,
     *   MaxKeys?: null|int,
     *   Prefix?: null|string,
     *   VersionIdMarker?: null|string,
     *   ExpectedBucketOwner?: null|string,
     *   RequestPayer?: null|RequestPayer::*,
     *   OptionalObjectAttributes?: null|array<OptionalObjectAttributes::*>,
     *   '@region'?: string|null,
     * }|ListObjectVersionsRequest $input
     */
    public function listObjectVersions($input): ListObjectVersionsOutput
    {
        $input = ListObjectVersionsRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'ListObjectVersions', 'region' => $input->getRegion()]));

        return new ListObjectVersionsOutput($response, $this, $input);
    }

    /**
     * Returns some or all (up to 1,000) of the objects in a bucket with each request. You can use the request parameters as
     * selection criteria to return a subset of the objects in a bucket. A `200 OK` response can contain valid or invalid
     * XML. Make sure to design your application to parse the contents of the response and handle it appropriately. For more
     * information about listing objects, see Listing object keys programmatically [^1] in the *Amazon S3 User Guide*. To
     * get a list of your buckets, see ListBuckets [^2].
     *
     * > - **General purpose bucket** - For general purpose buckets, `ListObjectsV2` doesn't return prefixes that are
     * >   related only to in-progress multipart uploads.
     * > - **Directory buckets** - For directory buckets, `ListObjectsV2` response includes the prefixes that are related
     * >   only to in-progress multipart uploads.
     * > - **Directory buckets** - For directory buckets, you must make requests for this API operation to the Zonal
     * >   endpoint. These endpoints support virtual-hosted-style requests in the format
     * >   `https://*bucket_name*.s3express-*az_id*.*region*.amazonaws.com/*key-name*`. Path-style requests are not
     * >   supported. For more information, see Regional and Zonal endpoints [^3] in the *Amazon S3 User Guide*.
     * >
     *
     * - `Permissions`:
     *
     *   - **General purpose bucket permissions** - To use this operation, you must have READ access to the bucket. You must
     *     have permission to perform the `s3:ListBucket` action. The bucket owner has this permission by default and can
     *     grant this permission to others. For more information about permissions, see Permissions Related to Bucket
     *     Subresource Operations [^4] and Managing Access Permissions to Your Amazon S3 Resources [^5] in the *Amazon S3
     *     User Guide*.
     *   - **Directory bucket permissions** - To grant access to this API operation on a directory bucket, we recommend that
     *     you use the `CreateSession` [^6] API operation for session-based authorization. Specifically, you grant the
     *     `s3express:CreateSession` permission to the directory bucket in a bucket policy or an IAM identity-based policy.
     *     Then, you make the `CreateSession` API call on the bucket to obtain a session token. With the session token in
     *     your request header, you can make API requests to this operation. After the session token expires, you make
     *     another `CreateSession` API call to generate a new session token for use. Amazon Web Services CLI or SDKs create
     *     session and refresh the session token automatically to avoid service interruptions when a session expires. For
     *     more information about authorization, see `CreateSession` [^7].
     *
     * - `Sorting order of returned objects`:
     *
     *   - **General purpose bucket** - For general purpose buckets, `ListObjectsV2` returns objects in lexicographical
     *     order based on their key names.
     *   - **Directory bucket** - For directory buckets, `ListObjectsV2` does not return objects in lexicographical order.
     *
     * - `HTTP Host header syntax`:
     *
     *   **Directory buckets ** - The HTTP Host header syntax is `*Bucket_name*.s3express-*az_id*.*region*.amazonaws.com`.
     *
     * ! This section describes the latest revision of this action. We recommend that you use this revised API operation for
     * ! application development. For backward compatibility, Amazon S3 continues to support the prior version of this API
     * ! operation, ListObjects [^8].
     *
     * The following operations are related to `ListObjectsV2`:
     *
     * - GetObject [^9]
     * - PutObject [^10]
     * - CreateBucket [^11]
     *
     * [^1]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/ListingKeysUsingAPIs.html
     * [^2]: https://docs.aws.amazon.com/AmazonS3/latest/API/API_ListBuckets.html
     * [^3]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/s3-express-Regions-and-Zones.html
     * [^4]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/using-with-s3-actions.html#using-with-s3-actions-related-to-bucket-subresources
     * [^5]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/s3-access-control.html
     * [^6]: https://docs.aws.amazon.com/AmazonS3/latest/API/API_CreateSession.html
     * [^7]: https://docs.aws.amazon.com/AmazonS3/latest/API/API_CreateSession.html
     * [^8]: https://docs.aws.amazon.com/AmazonS3/latest/API/API_ListObjects.html
     * [^9]: https://docs.aws.amazon.com/AmazonS3/latest/API/API_GetObject.html
     * [^10]: https://docs.aws.amazon.com/AmazonS3/latest/API/API_PutObject.html
     * [^11]: https://docs.aws.amazon.com/AmazonS3/latest/API/API_CreateBucket.html
     *
     * @see https://docs.aws.amazon.com/AmazonS3/latest/API/API_ListObjectsV2.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-s3-2006-03-01.html#listobjectsv2
     *
     * @param array{
     *   Bucket: string,
     *   Delimiter?: null|string,
     *   EncodingType?: null|EncodingType::*,
     *   MaxKeys?: null|int,
     *   Prefix?: null|string,
     *   ContinuationToken?: null|string,
     *   FetchOwner?: null|bool,
     *   StartAfter?: null|string,
     *   RequestPayer?: null|RequestPayer::*,
     *   ExpectedBucketOwner?: null|string,
     *   OptionalObjectAttributes?: null|array<OptionalObjectAttributes::*>,
     *   '@region'?: string|null,
     * }|ListObjectsV2Request $input
     *
     * @throws NoSuchBucketException
     */
    public function listObjectsV2($input): ListObjectsV2Output
    {
        $input = ListObjectsV2Request::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'ListObjectsV2', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'NoSuchBucket' => NoSuchBucketException::class,
        ]]));

        return new ListObjectsV2Output($response, $this, $input);
    }

    /**
     * Lists the parts that have been uploaded for a specific multipart upload.
     *
     * To use this operation, you must provide the `upload ID` in the request. You obtain this uploadID by sending the
     * initiate multipart upload request through CreateMultipartUpload [^1].
     *
     * The `ListParts` request returns a maximum of 1,000 uploaded parts. The limit of 1,000 parts is also the default
     * value. You can restrict the number of parts in a response by specifying the `max-parts` request parameter. If your
     * multipart upload consists of more than 1,000 parts, the response returns an `IsTruncated` field with the value of
     * `true`, and a `NextPartNumberMarker` element. To list remaining uploaded parts, in subsequent `ListParts` requests,
     * include the `part-number-marker` query string parameter and set its value to the `NextPartNumberMarker` field value
     * from the previous response.
     *
     * For more information on multipart uploads, see Uploading Objects Using Multipart Upload [^2] in the *Amazon S3 User
     * Guide*.
     *
     * > **Directory buckets** - For directory buckets, you must make requests for this API operation to the Zonal endpoint.
     * > These endpoints support virtual-hosted-style requests in the format
     * > `https://*bucket_name*.s3express-*az_id*.*region*.amazonaws.com/*key-name*`. Path-style requests are not supported.
     * > For more information, see Regional and Zonal endpoints [^3] in the *Amazon S3 User Guide*.
     *
     * - `Permissions`:
     *
     *   - **General purpose bucket permissions** - For information about permissions required to use the multipart upload
     *     API, see Multipart Upload and Permissions [^4] in the *Amazon S3 User Guide*.
     *
     *     If the upload was created using server-side encryption with Key Management Service (KMS) keys (SSE-KMS) or
     *     dual-layer server-side encryption with Amazon Web Services KMS keys (DSSE-KMS), you must have permission to the
     *     `kms:Decrypt` action for the `ListParts` request to succeed.
     *   - **Directory bucket permissions** - To grant access to this API operation on a directory bucket, we recommend that
     *     you use the `CreateSession` [^5] API operation for session-based authorization. Specifically, you grant the
     *     `s3express:CreateSession` permission to the directory bucket in a bucket policy or an IAM identity-based policy.
     *     Then, you make the `CreateSession` API call on the bucket to obtain a session token. With the session token in
     *     your request header, you can make API requests to this operation. After the session token expires, you make
     *     another `CreateSession` API call to generate a new session token for use. Amazon Web Services CLI or SDKs create
     *     session and refresh the session token automatically to avoid service interruptions when a session expires. For
     *     more information about authorization, see `CreateSession` [^6].
     *
     * - `HTTP Host header syntax`:
     *
     *   **Directory buckets ** - The HTTP Host header syntax is `*Bucket_name*.s3express-*az_id*.*region*.amazonaws.com`.
     *
     * The following operations are related to `ListParts`:
     *
     * - CreateMultipartUpload [^7]
     * - UploadPart [^8]
     * - CompleteMultipartUpload [^9]
     * - AbortMultipartUpload [^10]
     * - GetObjectAttributes [^11]
     * - ListMultipartUploads [^12]
     *
     * [^1]: https://docs.aws.amazon.com/AmazonS3/latest/API/API_CreateMultipartUpload.html
     * [^2]: https://docs.aws.amazon.com/AmazonS3/latest/dev/uploadobjusingmpu.html
     * [^3]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/s3-express-Regions-and-Zones.html
     * [^4]: https://docs.aws.amazon.com/AmazonS3/latest/dev/mpuAndPermissions.html
     * [^5]: https://docs.aws.amazon.com/AmazonS3/latest/API/API_CreateSession.html
     * [^6]: https://docs.aws.amazon.com/AmazonS3/latest/API/API_CreateSession.html
     * [^7]: https://docs.aws.amazon.com/AmazonS3/latest/API/API_CreateMultipartUpload.html
     * [^8]: https://docs.aws.amazon.com/AmazonS3/latest/API/API_UploadPart.html
     * [^9]: https://docs.aws.amazon.com/AmazonS3/latest/API/API_CompleteMultipartUpload.html
     * [^10]: https://docs.aws.amazon.com/AmazonS3/latest/API/API_AbortMultipartUpload.html
     * [^11]: https://docs.aws.amazon.com/AmazonS3/latest/API/API_GetObjectAttributes.html
     * [^12]: https://docs.aws.amazon.com/AmazonS3/latest/API/API_ListMultipartUploads.html
     *
     * @see http://docs.amazonwebservices.com/AmazonS3/latest/API/mpUploadListParts.html
     * @see https://docs.aws.amazon.com/AmazonS3/latest/API/API_ListParts.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-s3-2006-03-01.html#listparts
     *
     * @param array{
     *   Bucket: string,
     *   Key: string,
     *   MaxParts?: null|int,
     *   PartNumberMarker?: null|int,
     *   UploadId: string,
     *   RequestPayer?: null|RequestPayer::*,
     *   ExpectedBucketOwner?: null|string,
     *   SSECustomerAlgorithm?: null|string,
     *   SSECustomerKey?: null|string,
     *   SSECustomerKeyMD5?: null|string,
     *   '@region'?: string|null,
     * }|ListPartsRequest $input
     */
    public function listParts($input): ListPartsOutput
    {
        $input = ListPartsRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'ListParts', 'region' => $input->getRegion()]));

        return new ListPartsOutput($response, $this, $input);
    }

    /**
     * @see headObject
     *
     * @param array{
     *   Bucket: string,
     *   IfMatch?: null|string,
     *   IfModifiedSince?: null|\DateTimeImmutable|string,
     *   IfNoneMatch?: null|string,
     *   IfUnmodifiedSince?: null|\DateTimeImmutable|string,
     *   Key: string,
     *   Range?: null|string,
     *   ResponseCacheControl?: null|string,
     *   ResponseContentDisposition?: null|string,
     *   ResponseContentEncoding?: null|string,
     *   ResponseContentLanguage?: null|string,
     *   ResponseContentType?: null|string,
     *   ResponseExpires?: null|\DateTimeImmutable|string,
     *   VersionId?: null|string,
     *   SSECustomerAlgorithm?: null|string,
     *   SSECustomerKey?: null|string,
     *   SSECustomerKeyMD5?: null|string,
     *   RequestPayer?: null|RequestPayer::*,
     *   PartNumber?: null|int,
     *   ExpectedBucketOwner?: null|string,
     *   ChecksumMode?: null|ChecksumMode::*,
     *   '@region'?: string|null,
     * }|HeadObjectRequest $input
     */
    public function objectExists($input): ObjectExistsWaiter
    {
        $input = HeadObjectRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'HeadObject', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'NoSuchKey' => NoSuchKeyException::class,
        ]]));

        return new ObjectExistsWaiter($response, $this, $input);
    }

    /**
     * @see headObject
     *
     * @param array{
     *   Bucket: string,
     *   IfMatch?: null|string,
     *   IfModifiedSince?: null|\DateTimeImmutable|string,
     *   IfNoneMatch?: null|string,
     *   IfUnmodifiedSince?: null|\DateTimeImmutable|string,
     *   Key: string,
     *   Range?: null|string,
     *   ResponseCacheControl?: null|string,
     *   ResponseContentDisposition?: null|string,
     *   ResponseContentEncoding?: null|string,
     *   ResponseContentLanguage?: null|string,
     *   ResponseContentType?: null|string,
     *   ResponseExpires?: null|\DateTimeImmutable|string,
     *   VersionId?: null|string,
     *   SSECustomerAlgorithm?: null|string,
     *   SSECustomerKey?: null|string,
     *   SSECustomerKeyMD5?: null|string,
     *   RequestPayer?: null|RequestPayer::*,
     *   PartNumber?: null|int,
     *   ExpectedBucketOwner?: null|string,
     *   ChecksumMode?: null|ChecksumMode::*,
     *   '@region'?: string|null,
     * }|HeadObjectRequest $input
     */
    public function objectNotExists($input): ObjectNotExistsWaiter
    {
        $input = HeadObjectRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'HeadObject', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'NoSuchKey' => NoSuchKeyException::class,
        ]]));

        return new ObjectNotExistsWaiter($response, $this, $input);
    }

    /**
     * > This operation is not supported for directory buckets.
     *
     * Sets the `cors` configuration for your bucket. If the configuration exists, Amazon S3 replaces it.
     *
     * To use this operation, you must be allowed to perform the `s3:PutBucketCORS` action. By default, the bucket owner has
     * this permission and can grant it to others.
     *
     * You set this configuration on a bucket so that the bucket can service cross-origin requests. For example, you might
     * want to enable a request whose origin is `http://www.example.com` to access your Amazon S3 bucket at
     * `my.example.bucket.com` by using the browser's `XMLHttpRequest` capability.
     *
     * To enable cross-origin resource sharing (CORS) on a bucket, you add the `cors` subresource to the bucket. The `cors`
     * subresource is an XML document in which you configure rules that identify origins and the HTTP methods that can be
     * executed on your bucket. The document is limited to 64 KB in size.
     *
     * When Amazon S3 receives a cross-origin request (or a pre-flight OPTIONS request) against a bucket, it evaluates the
     * `cors` configuration on the bucket and uses the first `CORSRule` rule that matches the incoming browser request to
     * enable a cross-origin request. For a rule to match, the following conditions must be met:
     *
     * - The request's `Origin` header must match `AllowedOrigin` elements.
     * - The request method (for example, GET, PUT, HEAD, and so on) or the `Access-Control-Request-Method` header in case
     *   of a pre-flight `OPTIONS` request must be one of the `AllowedMethod` elements.
     * - Every header specified in the `Access-Control-Request-Headers` request header of a pre-flight request must match an
     *   `AllowedHeader` element.
     *
     * For more information about CORS, go to Enabling Cross-Origin Resource Sharing [^1] in the *Amazon S3 User Guide*.
     *
     * The following operations are related to `PutBucketCors`:
     *
     * - GetBucketCors [^2]
     * - DeleteBucketCors [^3]
     * - RESTOPTIONSobject [^4]
     *
     * [^1]: https://docs.aws.amazon.com/AmazonS3/latest/dev/cors.html
     * [^2]: https://docs.aws.amazon.com/AmazonS3/latest/API/API_GetBucketCors.html
     * [^3]: https://docs.aws.amazon.com/AmazonS3/latest/API/API_DeleteBucketCors.html
     * [^4]: https://docs.aws.amazon.com/AmazonS3/latest/API/RESTOPTIONSobject.html
     *
     * @see http://docs.amazonwebservices.com/AmazonS3/latest/API/RESTBucketPUTcors.html
     * @see https://docs.aws.amazon.com/AmazonS3/latest/API/API_PutBucketCors.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-s3-2006-03-01.html#putbucketcors
     *
     * @param array{
     *   Bucket: string,
     *   CORSConfiguration: CORSConfiguration|array,
     *   ContentMD5?: null|string,
     *   ChecksumAlgorithm?: null|ChecksumAlgorithm::*,
     *   ExpectedBucketOwner?: null|string,
     *   '@region'?: string|null,
     * }|PutBucketCorsRequest $input
     */
    public function putBucketCors($input): Result
    {
        $input = PutBucketCorsRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'PutBucketCors', 'region' => $input->getRegion()]));

        return new Result($response);
    }

    /**
     * > This operation is not supported for directory buckets.
     *
     * Enables notifications of specified events for a bucket. For more information about event notifications, see
     * Configuring Event Notifications [^1].
     *
     * Using this API, you can replace an existing notification configuration. The configuration is an XML file that defines
     * the event types that you want Amazon S3 to publish and the destination where you want Amazon S3 to publish an event
     * notification when it detects an event of the specified type.
     *
     * By default, your bucket has no event notifications configured. That is, the notification configuration will be an
     * empty `NotificationConfiguration`.
     *
     * `<NotificationConfiguration>`
     *
     * `</NotificationConfiguration>`
     *
     * This action replaces the existing notification configuration with the configuration you include in the request body.
     *
     * After Amazon S3 receives this request, it first verifies that any Amazon Simple Notification Service (Amazon SNS) or
     * Amazon Simple Queue Service (Amazon SQS) destination exists, and that the bucket owner has permission to publish to
     * it by sending a test notification. In the case of Lambda destinations, Amazon S3 verifies that the Lambda function
     * permissions grant Amazon S3 permission to invoke the function from the Amazon S3 bucket. For more information, see
     * Configuring Notifications for Amazon S3 Events [^2].
     *
     * You can disable notifications by adding the empty NotificationConfiguration element.
     *
     * For more information about the number of event notification configurations that you can create per bucket, see Amazon
     * S3 service quotas [^3] in *Amazon Web Services General Reference*.
     *
     * By default, only the bucket owner can configure notifications on a bucket. However, bucket owners can use a bucket
     * policy to grant permission to other users to set this configuration with the required `s3:PutBucketNotification`
     * permission.
     *
     * > The PUT notification is an atomic operation. For example, suppose your notification configuration includes SNS
     * > topic, SQS queue, and Lambda function configurations. When you send a PUT request with this configuration, Amazon
     * > S3 sends test messages to your SNS topic. If the message fails, the entire PUT action will fail, and Amazon S3 will
     * > not add the configuration to your bucket.
     *
     * If the configuration in the request body includes only one `TopicConfiguration` specifying only the
     * `s3:ReducedRedundancyLostObject` event type, the response will also include the `x-amz-sns-test-message-id` header
     * containing the message ID of the test notification sent to the topic.
     *
     * The following action is related to `PutBucketNotificationConfiguration`:
     *
     * - GetBucketNotificationConfiguration [^4]
     *
     * [^1]: https://docs.aws.amazon.com/AmazonS3/latest/dev/NotificationHowTo.html
     * [^2]: https://docs.aws.amazon.com/AmazonS3/latest/dev/NotificationHowTo.html
     * [^3]: https://docs.aws.amazon.com/general/latest/gr/s3.html#limits_s3
     * [^4]: https://docs.aws.amazon.com/AmazonS3/latest/API/API_GetBucketNotificationConfiguration.html
     *
     * @see https://docs.aws.amazon.com/AmazonS3/latest/API/API_PutBucketNotificationConfiguration.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-s3-2006-03-01.html#putbucketnotificationconfiguration
     *
     * @param array{
     *   Bucket: string,
     *   NotificationConfiguration: NotificationConfiguration|array,
     *   ExpectedBucketOwner?: null|string,
     *   SkipDestinationValidation?: null|bool,
     *   '@region'?: string|null,
     * }|PutBucketNotificationConfigurationRequest $input
     */
    public function putBucketNotificationConfiguration($input): Result
    {
        $input = PutBucketNotificationConfigurationRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'PutBucketNotificationConfiguration', 'region' => $input->getRegion()]));

        return new Result($response);
    }

    /**
     * > This operation is not supported for directory buckets.
     *
     * Sets the tags for a bucket.
     *
     * Use tags to organize your Amazon Web Services bill to reflect your own cost structure. To do this, sign up to get
     * your Amazon Web Services account bill with tag key values included. Then, to see the cost of combined resources,
     * organize your billing information according to resources with the same tag key values. For example, you can tag
     * several resources with a specific application name, and then organize your billing information to see the total cost
     * of that application across several services. For more information, see Cost Allocation and Tagging [^1] and Using
     * Cost Allocation in Amazon S3 Bucket Tags [^2].
     *
     * > When this operation sets the tags for a bucket, it will overwrite any current tags the bucket already has. You
     * > cannot use this operation to add tags to an existing list of tags.
     *
     * To use this operation, you must have permissions to perform the `s3:PutBucketTagging` action. The bucket owner has
     * this permission by default and can grant this permission to others. For more information about permissions, see
     * Permissions Related to Bucket Subresource Operations [^3] and Managing Access Permissions to Your Amazon S3 Resources
     * [^4].
     *
     * `PutBucketTagging` has the following special errors. For more Amazon S3 errors see, Error Responses [^5].
     *
     * - `InvalidTag` - The tag provided was not a valid tag. This error can occur if the tag did not pass input validation.
     *   For more information, see Using Cost Allocation in Amazon S3 Bucket Tags [^6].
     * - `MalformedXML` - The XML provided does not match the schema.
     * - `OperationAborted` - A conflicting conditional action is currently in progress against this resource. Please try
     *   again.
     * - `InternalError` - The service was unable to apply the provided tag to the bucket.
     *
     * The following operations are related to `PutBucketTagging`:
     *
     * - GetBucketTagging [^7]
     * - DeleteBucketTagging [^8]
     *
     * [^1]: https://docs.aws.amazon.com/awsaccountbilling/latest/aboutv2/cost-alloc-tags.html
     * [^2]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/CostAllocTagging.html
     * [^3]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/using-with-s3-actions.html#using-with-s3-actions-related-to-bucket-subresources
     * [^4]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/s3-access-control.html
     * [^5]: https://docs.aws.amazon.com/AmazonS3/latest/API/ErrorResponses.html
     * [^6]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/CostAllocTagging.html
     * [^7]: https://docs.aws.amazon.com/AmazonS3/latest/API/API_GetBucketTagging.html
     * [^8]: https://docs.aws.amazon.com/AmazonS3/latest/API/API_DeleteBucketTagging.html
     *
     * @see http://docs.amazonwebservices.com/AmazonS3/latest/API/RESTBucketPUTtagging.html
     * @see https://docs.aws.amazon.com/AmazonS3/latest/API/API_PutBucketTagging.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-s3-2006-03-01.html#putbuckettagging
     *
     * @param array{
     *   Bucket: string,
     *   ContentMD5?: null|string,
     *   ChecksumAlgorithm?: null|ChecksumAlgorithm::*,
     *   Tagging: Tagging|array,
     *   ExpectedBucketOwner?: null|string,
     *   '@region'?: string|null,
     * }|PutBucketTaggingRequest $input
     */
    public function putBucketTagging($input): Result
    {
        $input = PutBucketTaggingRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'PutBucketTagging', 'region' => $input->getRegion()]));

        return new Result($response);
    }

    /**
     * Adds an object to a bucket.
     *
     * > - Amazon S3 never adds partial objects; if you receive a success response, Amazon S3 added the entire object to the
     * >   bucket. You cannot use `PutObject` to only update a single piece of metadata for an existing object. You must put
     * >   the entire object with updated metadata if you want to update some values.
     * > - If your bucket uses the bucket owner enforced setting for Object Ownership, ACLs are disabled and no longer
     * >   affect permissions. All objects written to the bucket by any account will be owned by the bucket owner.
     * > - **Directory buckets** - For directory buckets, you must make requests for this API operation to the Zonal
     * >   endpoint. These endpoints support virtual-hosted-style requests in the format
     * >   `https://*bucket_name*.s3express-*az_id*.*region*.amazonaws.com/*key-name*`. Path-style requests are not
     * >   supported. For more information, see Regional and Zonal endpoints [^1] in the *Amazon S3 User Guide*.
     * >
     *
     * Amazon S3 is a distributed system. If it receives multiple write requests for the same object simultaneously, it
     * overwrites all but the last object written. However, Amazon S3 provides features that can modify this behavior:
     *
     * - **S3 Object Lock** - To prevent objects from being deleted or overwritten, you can use Amazon S3 Object Lock [^2]
     *   in the *Amazon S3 User Guide*.
     *
     *   > This functionality is not supported for directory buckets.
     *
     * - **S3 Versioning** - When you enable versioning for a bucket, if Amazon S3 receives multiple write requests for the
     *   same object simultaneously, it stores all versions of the objects. For each write request that is made to the same
     *   object, Amazon S3 automatically generates a unique version ID of that object being stored in Amazon S3. You can
     *   retrieve, replace, or delete any version of the object. For more information about versioning, see Adding Objects
     *   to Versioning-Enabled Buckets [^3] in the *Amazon S3 User Guide*. For information about returning the versioning
     *   state of a bucket, see GetBucketVersioning [^4].
     *
     *   > This functionality is not supported for directory buckets.
     *
     *
     * - `Permissions`:
     *
     *   - **General purpose bucket permissions** - The following permissions are required in your policies when your
     *     `PutObject` request includes specific headers.
     *
     *     - **`s3:PutObject`** - To successfully complete the `PutObject` request, you must always have the `s3:PutObject`
     *       permission on a bucket to add an object to it.
     *     - **`s3:PutObjectAcl`** - To successfully change the objects ACL of your `PutObject` request, you must have the
     *       `s3:PutObjectAcl`.
     *     - **`s3:PutObjectTagging`** - To successfully set the tag-set with your `PutObject` request, you must have the
     *       `s3:PutObjectTagging`.
     *
     *   - **Directory bucket permissions** - To grant access to this API operation on a directory bucket, we recommend that
     *     you use the `CreateSession` [^5] API operation for session-based authorization. Specifically, you grant the
     *     `s3express:CreateSession` permission to the directory bucket in a bucket policy or an IAM identity-based policy.
     *     Then, you make the `CreateSession` API call on the bucket to obtain a session token. With the session token in
     *     your request header, you can make API requests to this operation. After the session token expires, you make
     *     another `CreateSession` API call to generate a new session token for use. Amazon Web Services CLI or SDKs create
     *     session and refresh the session token automatically to avoid service interruptions when a session expires. For
     *     more information about authorization, see `CreateSession` [^6].
     *
     *     If the object is encrypted with SSE-KMS, you must also have the `kms:GenerateDataKey` and `kms:Decrypt`
     *     permissions in IAM identity-based policies and KMS key policies for the KMS key.
     *
     * - `Data integrity with Content-MD5`:
     *
     *   - **General purpose bucket** - To ensure that data is not corrupted traversing the network, use the `Content-MD5`
     *     header. When you use this header, Amazon S3 checks the object against the provided MD5 value and, if they do not
     *     match, Amazon S3 returns an error. Alternatively, when the object's ETag is its MD5 digest, you can calculate the
     *     MD5 while putting the object to Amazon S3 and compare the returned ETag to the calculated MD5 value.
     *   - **Directory bucket** - This functionality is not supported for directory buckets.
     *
     * - `HTTP Host header syntax`:
     *
     *   **Directory buckets ** - The HTTP Host header syntax is `*Bucket_name*.s3express-*az_id*.*region*.amazonaws.com`.
     *
     * For more information about related Amazon S3 APIs, see the following:
     *
     * - CopyObject [^7]
     * - DeleteObject [^8]
     *
     * [^1]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/s3-express-Regions-and-Zones.html
     * [^2]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/object-lock.html
     * [^3]: https://docs.aws.amazon.com/AmazonS3/latest/dev/AddingObjectstoVersioningEnabledBuckets.html
     * [^4]: https://docs.aws.amazon.com/AmazonS3/latest/API/API_GetBucketVersioning.html
     * [^5]: https://docs.aws.amazon.com/AmazonS3/latest/API/API_CreateSession.html
     * [^6]: https://docs.aws.amazon.com/AmazonS3/latest/API/API_CreateSession.html
     * [^7]: https://docs.aws.amazon.com/AmazonS3/latest/API/API_CopyObject.html
     * [^8]: https://docs.aws.amazon.com/AmazonS3/latest/API/API_DeleteObject.html
     *
     * @see http://docs.amazonwebservices.com/AmazonS3/latest/API/RESTObjectPUT.html
     * @see https://docs.aws.amazon.com/AmazonS3/latest/API/API_PutObject.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-s3-2006-03-01.html#putobject
     *
     * @param array{
     *   ACL?: null|ObjectCannedACL::*,
     *   Body?: null|string|resource|(callable(int): string)|iterable<string>,
     *   Bucket: string,
     *   CacheControl?: null|string,
     *   ContentDisposition?: null|string,
     *   ContentEncoding?: null|string,
     *   ContentLanguage?: null|string,
     *   ContentLength?: null|int,
     *   ContentMD5?: null|string,
     *   ContentType?: null|string,
     *   ChecksumAlgorithm?: null|ChecksumAlgorithm::*,
     *   ChecksumCRC32?: null|string,
     *   ChecksumCRC32C?: null|string,
     *   ChecksumSHA1?: null|string,
     *   ChecksumSHA256?: null|string,
     *   Expires?: null|\DateTimeImmutable|string,
     *   IfMatch?: null|string,
     *   IfNoneMatch?: null|string,
     *   GrantFullControl?: null|string,
     *   GrantRead?: null|string,
     *   GrantReadACP?: null|string,
     *   GrantWriteACP?: null|string,
     *   Key: string,
     *   WriteOffsetBytes?: null|int,
     *   Metadata?: null|array<string, string>,
     *   ServerSideEncryption?: null|ServerSideEncryption::*,
     *   StorageClass?: null|StorageClass::*,
     *   WebsiteRedirectLocation?: null|string,
     *   SSECustomerAlgorithm?: null|string,
     *   SSECustomerKey?: null|string,
     *   SSECustomerKeyMD5?: null|string,
     *   SSEKMSKeyId?: null|string,
     *   SSEKMSEncryptionContext?: null|string,
     *   BucketKeyEnabled?: null|bool,
     *   RequestPayer?: null|RequestPayer::*,
     *   Tagging?: null|string,
     *   ObjectLockMode?: null|ObjectLockMode::*,
     *   ObjectLockRetainUntilDate?: null|\DateTimeImmutable|string,
     *   ObjectLockLegalHoldStatus?: null|ObjectLockLegalHoldStatus::*,
     *   ExpectedBucketOwner?: null|string,
     *   '@region'?: string|null,
     * }|PutObjectRequest $input
     *
     * @throws InvalidRequestException
     * @throws InvalidWriteOffsetException
     * @throws TooManyPartsException
     * @throws EncryptionTypeMismatchException
     */
    public function putObject($input): PutObjectOutput
    {
        $input = PutObjectRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'PutObject', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'InvalidRequest' => InvalidRequestException::class,
            'InvalidWriteOffset' => InvalidWriteOffsetException::class,
            'TooManyParts' => TooManyPartsException::class,
            'EncryptionTypeMismatch' => EncryptionTypeMismatchException::class,
        ]]));

        return new PutObjectOutput($response);
    }

    /**
     * > This operation is not supported for directory buckets.
     *
     * Uses the `acl` subresource to set the access control list (ACL) permissions for a new or existing object in an S3
     * bucket. You must have the `WRITE_ACP` permission to set the ACL of an object. For more information, see What
     * permissions can I grant? [^1] in the *Amazon S3 User Guide*.
     *
     * This functionality is not supported for Amazon S3 on Outposts.
     *
     * Depending on your application needs, you can choose to set the ACL on an object using either the request body or the
     * headers. For example, if you have an existing application that updates a bucket ACL using the request body, you can
     * continue to use that approach. For more information, see Access Control List (ACL) Overview [^2] in the *Amazon S3
     * User Guide*.
     *
     * ! If your bucket uses the bucket owner enforced setting for S3 Object Ownership, ACLs are disabled and no longer
     * ! affect permissions. You must use policies to grant access to your bucket and the objects in it. Requests to set
     * ! ACLs or update ACLs fail and return the `AccessControlListNotSupported` error code. Requests to read ACLs are still
     * ! supported. For more information, see Controlling object ownership [^3] in the *Amazon S3 User Guide*.
     *
     * - `Permissions`:
     *
     *   You can set access permissions using one of the following methods:
     *
     *   - Specify a canned ACL with the `x-amz-acl` request header. Amazon S3 supports a set of predefined ACLs, known as
     *     canned ACLs. Each canned ACL has a predefined set of grantees and permissions. Specify the canned ACL name as the
     *     value of `x-amz-ac`l. If you use this header, you cannot use other access control-specific headers in your
     *     request. For more information, see Canned ACL [^4].
     *   - Specify access permissions explicitly with the `x-amz-grant-read`, `x-amz-grant-read-acp`,
     *     `x-amz-grant-write-acp`, and `x-amz-grant-full-control` headers. When using these headers, you specify explicit
     *     access permissions and grantees (Amazon Web Services accounts or Amazon S3 groups) who will receive the
     *     permission. If you use these ACL-specific headers, you cannot use `x-amz-acl` header to set a canned ACL. These
     *     parameters map to the set of permissions that Amazon S3 supports in an ACL. For more information, see Access
     *     Control List (ACL) Overview [^5].
     *
     *     You specify each grantee as a type=value pair, where the type is one of the following:
     *
     *     - `id` – if the value specified is the canonical user ID of an Amazon Web Services account
     *     - `uri` – if you are granting permissions to a predefined group
     *     - `emailAddress` – if the value specified is the email address of an Amazon Web Services account
     *
     *       > Using email addresses to specify a grantee is only supported in the following Amazon Web Services Regions:
     *       >
     *       > - US East (N. Virginia)
     *       > - US West (N. California)
     *       > - US West (Oregon)
     *       > - Asia Pacific (Singapore)
     *       > - Asia Pacific (Sydney)
     *       > - Asia Pacific (Tokyo)
     *       > - Europe (Ireland)
     *       > - South America (São Paulo)
     *       >
     *       > For a list of all the Amazon S3 supported Regions and endpoints, see Regions and Endpoints [^6] in the Amazon
     *       > Web Services General Reference.
     *
     *
     *     For example, the following `x-amz-grant-read` header grants list objects permission to the two Amazon Web
     *     Services accounts identified by their email addresses.
     *
     *     `x-amz-grant-read: emailAddress="xyz@amazon.com", emailAddress="abc@amazon.com" `
     *
     *   You can use either a canned ACL or specify access permissions explicitly. You cannot do both.
     * - `Grantee Values`:
     *
     *   You can specify the person (grantee) to whom you're assigning access rights (using request elements) in the
     *   following ways:
     *
     *   - By the person's ID:
     *
     *     `<Grantee xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
     *     xsi:type="CanonicalUser"><ID><>ID<></ID><DisplayName><>GranteesEmail<></DisplayName>
     *     </Grantee>`
     *
     *     DisplayName is optional and ignored in the request.
     *   - By URI:
     *
     *     `<Grantee xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
     *     xsi:type="Group"><URI><>http://acs.amazonaws.com/groups/global/AuthenticatedUsers<></URI></Grantee>`
     *   - By Email address:
     *
     *     `<Grantee xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
     *     xsi:type="AmazonCustomerByEmail"><EmailAddress><>Grantees@email.com<></EmailAddress>lt;/Grantee>`
     *
     *     The grantee is resolved to the CanonicalUser and, in a response to a GET Object acl request, appears as the
     *     CanonicalUser.
     *
     *     > Using email addresses to specify a grantee is only supported in the following Amazon Web Services Regions:
     *     >
     *     > - US East (N. Virginia)
     *     > - US West (N. California)
     *     > - US West (Oregon)
     *     > - Asia Pacific (Singapore)
     *     > - Asia Pacific (Sydney)
     *     > - Asia Pacific (Tokyo)
     *     > - Europe (Ireland)
     *     > - South America (São Paulo)
     *     >
     *     > For a list of all the Amazon S3 supported Regions and endpoints, see Regions and Endpoints [^7] in the Amazon
     *     > Web Services General Reference.
     *
     *
     * - `Versioning`:
     *
     *   The ACL of an object is set at the object version level. By default, PUT sets the ACL of the current version of an
     *   object. To set the ACL of a different version, use the `versionId` subresource.
     *
     * The following operations are related to `PutObjectAcl`:
     *
     * - CopyObject [^8]
     * - GetObject [^9]
     *
     * [^1]: https://docs.aws.amazon.com/AmazonS3/latest/dev/acl-overview.html#permissions
     * [^2]: https://docs.aws.amazon.com/AmazonS3/latest/dev/acl-overview.html
     * [^3]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/about-object-ownership.html
     * [^4]: https://docs.aws.amazon.com/AmazonS3/latest/dev/acl-overview.html#CannedACL
     * [^5]: https://docs.aws.amazon.com/AmazonS3/latest/dev/acl-overview.html
     * [^6]: https://docs.aws.amazon.com/general/latest/gr/rande.html#s3_region
     * [^7]: https://docs.aws.amazon.com/general/latest/gr/rande.html#s3_region
     * [^8]: https://docs.aws.amazon.com/AmazonS3/latest/API/API_CopyObject.html
     * [^9]: https://docs.aws.amazon.com/AmazonS3/latest/API/API_GetObject.html
     *
     * @see http://docs.amazonwebservices.com/AmazonS3/latest/API/RESTObjectPUTacl.html
     * @see https://docs.aws.amazon.com/AmazonS3/latest/API/API_PutObjectAcl.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-s3-2006-03-01.html#putobjectacl
     *
     * @param array{
     *   ACL?: null|ObjectCannedACL::*,
     *   AccessControlPolicy?: null|AccessControlPolicy|array,
     *   Bucket: string,
     *   ContentMD5?: null|string,
     *   ChecksumAlgorithm?: null|ChecksumAlgorithm::*,
     *   GrantFullControl?: null|string,
     *   GrantRead?: null|string,
     *   GrantReadACP?: null|string,
     *   GrantWrite?: null|string,
     *   GrantWriteACP?: null|string,
     *   Key: string,
     *   RequestPayer?: null|RequestPayer::*,
     *   VersionId?: null|string,
     *   ExpectedBucketOwner?: null|string,
     *   '@region'?: string|null,
     * }|PutObjectAclRequest $input
     *
     * @throws NoSuchKeyException
     */
    public function putObjectAcl($input): PutObjectAclOutput
    {
        $input = PutObjectAclRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'PutObjectAcl', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'NoSuchKey' => NoSuchKeyException::class,
        ]]));

        return new PutObjectAclOutput($response);
    }

    /**
     * > This operation is not supported for directory buckets.
     *
     * Sets the supplied tag-set to an object that already exists in a bucket. A tag is a key-value pair. For more
     * information, see Object Tagging [^1].
     *
     * You can associate tags with an object by sending a PUT request against the tagging subresource that is associated
     * with the object. You can retrieve tags by sending a GET request. For more information, see GetObjectTagging [^2].
     *
     * For tagging-related restrictions related to characters and encodings, see Tag Restrictions [^3]. Note that Amazon S3
     * limits the maximum number of tags to 10 tags per object.
     *
     * To use this operation, you must have permission to perform the `s3:PutObjectTagging` action. By default, the bucket
     * owner has this permission and can grant this permission to others.
     *
     * To put tags of any other version, use the `versionId` query parameter. You also need permission for the
     * `s3:PutObjectVersionTagging` action.
     *
     * `PutObjectTagging` has the following special errors. For more Amazon S3 errors see, Error Responses [^4].
     *
     * - `InvalidTag` - The tag provided was not a valid tag. This error can occur if the tag did not pass input validation.
     *   For more information, see Object Tagging [^5].
     * - `MalformedXML` - The XML provided does not match the schema.
     * - `OperationAborted` - A conflicting conditional action is currently in progress against this resource. Please try
     *   again.
     * - `InternalError` - The service was unable to apply the provided tag to the object.
     *
     * The following operations are related to `PutObjectTagging`:
     *
     * - GetObjectTagging [^6]
     * - DeleteObjectTagging [^7]
     *
     * [^1]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/object-tagging.html
     * [^2]: https://docs.aws.amazon.com/AmazonS3/latest/API/API_GetObjectTagging.html
     * [^3]: https://docs.aws.amazon.com/awsaccountbilling/latest/aboutv2/allocation-tag-restrictions.html
     * [^4]: https://docs.aws.amazon.com/AmazonS3/latest/API/ErrorResponses.html
     * [^5]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/object-tagging.html
     * [^6]: https://docs.aws.amazon.com/AmazonS3/latest/API/API_GetObjectTagging.html
     * [^7]: https://docs.aws.amazon.com/AmazonS3/latest/API/API_DeleteObjectTagging.html
     *
     * @see https://docs.aws.amazon.com/AmazonS3/latest/API/API_PutObjectTagging.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-s3-2006-03-01.html#putobjecttagging
     *
     * @param array{
     *   Bucket: string,
     *   Key: string,
     *   VersionId?: null|string,
     *   ContentMD5?: null|string,
     *   ChecksumAlgorithm?: null|ChecksumAlgorithm::*,
     *   Tagging: Tagging|array,
     *   ExpectedBucketOwner?: null|string,
     *   RequestPayer?: null|RequestPayer::*,
     *   '@region'?: string|null,
     * }|PutObjectTaggingRequest $input
     */
    public function putObjectTagging($input): PutObjectTaggingOutput
    {
        $input = PutObjectTaggingRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'PutObjectTagging', 'region' => $input->getRegion()]));

        return new PutObjectTaggingOutput($response);
    }

    /**
     * Uploads a part in a multipart upload.
     *
     * > In this operation, you provide new data as a part of an object in your request. However, you have an option to
     * > specify your existing Amazon S3 object as a data source for the part you are uploading. To upload a part from an
     * > existing object, you use the UploadPartCopy [^1] operation.
     *
     * You must initiate a multipart upload (see CreateMultipartUpload [^2]) before you can upload any part. In response to
     * your initiate request, Amazon S3 returns an upload ID, a unique identifier that you must include in your upload part
     * request.
     *
     * Part numbers can be any number from 1 to 10,000, inclusive. A part number uniquely identifies a part and also defines
     * its position within the object being created. If you upload a new part using the same part number that was used with
     * a previous part, the previously uploaded part is overwritten.
     *
     * For information about maximum and minimum part sizes and other multipart upload specifications, see Multipart upload
     * limits [^3] in the *Amazon S3 User Guide*.
     *
     * > After you initiate multipart upload and upload one or more parts, you must either complete or abort multipart
     * > upload in order to stop getting charged for storage of the uploaded parts. Only after you either complete or abort
     * > multipart upload, Amazon S3 frees up the parts storage and stops charging you for the parts storage.
     *
     * For more information on multipart uploads, go to Multipart Upload Overview [^4] in the *Amazon S3 User Guide *.
     *
     * > **Directory buckets** - For directory buckets, you must make requests for this API operation to the Zonal endpoint.
     * > These endpoints support virtual-hosted-style requests in the format
     * > `https://*bucket_name*.s3express-*az_id*.*region*.amazonaws.com/*key-name*`. Path-style requests are not supported.
     * > For more information, see Regional and Zonal endpoints [^5] in the *Amazon S3 User Guide*.
     *
     * - `Permissions`:
     *
     *   - **General purpose bucket permissions** - To perform a multipart upload with encryption using an Key Management
     *     Service key, the requester must have permission to the `kms:Decrypt` and `kms:GenerateDataKey` actions on the
     *     key. The requester must also have permissions for the `kms:GenerateDataKey` action for the
     *     `CreateMultipartUpload` API. Then, the requester needs permissions for the `kms:Decrypt` action on the
     *     `UploadPart` and `UploadPartCopy` APIs.
     *
     *     These permissions are required because Amazon S3 must decrypt and read data from the encrypted file parts before
     *     it completes the multipart upload. For more information about KMS permissions, see Protecting data using
     *     server-side encryption with KMS [^6] in the *Amazon S3 User Guide*. For information about the permissions
     *     required to use the multipart upload API, see Multipart upload and permissions [^7] and Multipart upload API and
     *     permissions [^8] in the *Amazon S3 User Guide*.
     *   - **Directory bucket permissions** - To grant access to this API operation on a directory bucket, we recommend that
     *     you use the `CreateSession` [^9] API operation for session-based authorization. Specifically, you grant the
     *     `s3express:CreateSession` permission to the directory bucket in a bucket policy or an IAM identity-based policy.
     *     Then, you make the `CreateSession` API call on the bucket to obtain a session token. With the session token in
     *     your request header, you can make API requests to this operation. After the session token expires, you make
     *     another `CreateSession` API call to generate a new session token for use. Amazon Web Services CLI or SDKs create
     *     session and refresh the session token automatically to avoid service interruptions when a session expires. For
     *     more information about authorization, see `CreateSession` [^10].
     *
     *     If the object is encrypted with SSE-KMS, you must also have the `kms:GenerateDataKey` and `kms:Decrypt`
     *     permissions in IAM identity-based policies and KMS key policies for the KMS key.
     *
     * - `Data integrity`:
     *
     *   **General purpose bucket** - To ensure that data is not corrupted traversing the network, specify the `Content-MD5`
     *   header in the upload part request. Amazon S3 checks the part data against the provided MD5 value. If they do not
     *   match, Amazon S3 returns an error. If the upload request is signed with Signature Version 4, then Amazon Web
     *   Services S3 uses the `x-amz-content-sha256` header as a checksum instead of `Content-MD5`. For more information see
     *   Authenticating Requests: Using the Authorization Header (Amazon Web Services Signature Version 4) [^11].
     *
     *   > **Directory buckets** - MD5 is not supported by directory buckets. You can use checksum algorithms to check
     *   > object integrity.
     *
     * - `Encryption`:
     *
     *   - **General purpose bucket** - Server-side encryption is for data encryption at rest. Amazon S3 encrypts your data
     *     as it writes it to disks in its data centers and decrypts it when you access it. You have mutually exclusive
     *     options to protect data using server-side encryption in Amazon S3, depending on how you choose to manage the
     *     encryption keys. Specifically, the encryption key options are Amazon S3 managed keys (SSE-S3), Amazon Web
     *     Services KMS keys (SSE-KMS), and Customer-Provided Keys (SSE-C). Amazon S3 encrypts data with server-side
     *     encryption using Amazon S3 managed keys (SSE-S3) by default. You can optionally tell Amazon S3 to encrypt data at
     *     rest using server-side encryption with other key options. The option you use depends on whether you want to use
     *     KMS keys (SSE-KMS) or provide your own encryption key (SSE-C).
     *
     *     Server-side encryption is supported by the S3 Multipart Upload operations. Unless you are using a
     *     customer-provided encryption key (SSE-C), you don't need to specify the encryption parameters in each UploadPart
     *     request. Instead, you only need to specify the server-side encryption parameters in the initial Initiate
     *     Multipart request. For more information, see CreateMultipartUpload [^12].
     *
     *     If you request server-side encryption using a customer-provided encryption key (SSE-C) in your initiate multipart
     *     upload request, you must provide identical encryption information in each part upload using the following request
     *     headers.
     *
     *     - x-amz-server-side-encryption-customer-algorithm
     *     - x-amz-server-side-encryption-customer-key
     *     - x-amz-server-side-encryption-customer-key-MD5
     *
     *     For more information, see Using Server-Side Encryption [^13] in the *Amazon S3 User Guide*.
     *   - **Directory buckets ** - For directory buckets, there are only two supported options for server-side encryption:
     *     server-side encryption with Amazon S3 managed keys (SSE-S3) (`AES256`) and server-side encryption with KMS keys
     *     (SSE-KMS) (`aws:kms`).
     *
     * - `Special errors`:
     *
     *   - Error Code: `NoSuchUpload`
     *
     *     - Description: The specified multipart upload does not exist. The upload ID might be invalid, or the multipart
     *       upload might have been aborted or completed.
     *     - HTTP Status Code: 404 Not Found
     *     - SOAP Fault Code Prefix: Client
     *
     *
     * - `HTTP Host header syntax`:
     *
     *   **Directory buckets ** - The HTTP Host header syntax is `*Bucket_name*.s3express-*az_id*.*region*.amazonaws.com`.
     *
     * The following operations are related to `UploadPart`:
     *
     * - CreateMultipartUpload [^14]
     * - CompleteMultipartUpload [^15]
     * - AbortMultipartUpload [^16]
     * - ListParts [^17]
     * - ListMultipartUploads [^18]
     *
     * [^1]: https://docs.aws.amazon.com/AmazonS3/latest/API/API_UploadPartCopy.html
     * [^2]: https://docs.aws.amazon.com/AmazonS3/latest/API/API_CreateMultipartUpload.html
     * [^3]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/qfacts.html
     * [^4]: https://docs.aws.amazon.com/AmazonS3/latest/dev/mpuoverview.html
     * [^5]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/s3-express-Regions-and-Zones.html
     * [^6]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/UsingKMSEncryption.html
     * [^7]: https://docs.aws.amazon.com/AmazonS3/latest/dev/mpuAndPermissions.html
     * [^8]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/mpuoverview.html#mpuAndPermissions
     * [^9]: https://docs.aws.amazon.com/AmazonS3/latest/API/API_CreateSession.html
     * [^10]: https://docs.aws.amazon.com/AmazonS3/latest/API/API_CreateSession.html
     * [^11]: https://docs.aws.amazon.com/AmazonS3/latest/API/sigv4-auth-using-authorization-header.html
     * [^12]: https://docs.aws.amazon.com/AmazonS3/latest/API/API_CreateMultipartUpload.html
     * [^13]: https://docs.aws.amazon.com/AmazonS3/latest/dev/UsingServerSideEncryption.html
     * [^14]: https://docs.aws.amazon.com/AmazonS3/latest/API/API_CreateMultipartUpload.html
     * [^15]: https://docs.aws.amazon.com/AmazonS3/latest/API/API_CompleteMultipartUpload.html
     * [^16]: https://docs.aws.amazon.com/AmazonS3/latest/API/API_AbortMultipartUpload.html
     * [^17]: https://docs.aws.amazon.com/AmazonS3/latest/API/API_ListParts.html
     * [^18]: https://docs.aws.amazon.com/AmazonS3/latest/API/API_ListMultipartUploads.html
     *
     * @see http://docs.amazonwebservices.com/AmazonS3/latest/API/mpUploadUploadPart.html
     * @see https://docs.aws.amazon.com/AmazonS3/latest/API/API_UploadPart.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-s3-2006-03-01.html#uploadpart
     *
     * @param array{
     *   Body?: null|string|resource|(callable(int): string)|iterable<string>,
     *   Bucket: string,
     *   ContentLength?: null|int,
     *   ContentMD5?: null|string,
     *   ChecksumAlgorithm?: null|ChecksumAlgorithm::*,
     *   ChecksumCRC32?: null|string,
     *   ChecksumCRC32C?: null|string,
     *   ChecksumSHA1?: null|string,
     *   ChecksumSHA256?: null|string,
     *   Key: string,
     *   PartNumber: int,
     *   UploadId: string,
     *   SSECustomerAlgorithm?: null|string,
     *   SSECustomerKey?: null|string,
     *   SSECustomerKeyMD5?: null|string,
     *   RequestPayer?: null|RequestPayer::*,
     *   ExpectedBucketOwner?: null|string,
     *   '@region'?: string|null,
     * }|UploadPartRequest $input
     */
    public function uploadPart($input): UploadPartOutput
    {
        $input = UploadPartRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'UploadPart', 'region' => $input->getRegion()]));

        return new UploadPartOutput($response);
    }

    /**
     * Uploads a part by copying data from an existing object as data source. To specify the data source, you add the
     * request header `x-amz-copy-source` in your request. To specify a byte range, you add the request header
     * `x-amz-copy-source-range` in your request.
     *
     * For information about maximum and minimum part sizes and other multipart upload specifications, see Multipart upload
     * limits [^1] in the *Amazon S3 User Guide*.
     *
     * > Instead of copying data from an existing object as part data, you might use the UploadPart [^2] action to upload
     * > new data as a part of an object in your request.
     *
     * You must initiate a multipart upload before you can upload any part. In response to your initiate request, Amazon S3
     * returns the upload ID, a unique identifier that you must include in your upload part request.
     *
     * For conceptual information about multipart uploads, see Uploading Objects Using Multipart Upload [^3] in the *Amazon
     * S3 User Guide*. For information about copying objects using a single atomic action vs. a multipart upload, see
     * Operations on Objects [^4] in the *Amazon S3 User Guide*.
     *
     * > **Directory buckets** - For directory buckets, you must make requests for this API operation to the Zonal endpoint.
     * > These endpoints support virtual-hosted-style requests in the format
     * > `https://*bucket_name*.s3express-*az_id*.*region*.amazonaws.com/*key-name*`. Path-style requests are not supported.
     * > For more information, see Regional and Zonal endpoints [^5] in the *Amazon S3 User Guide*.
     *
     * - `Authentication and authorization`:
     *
     *   All `UploadPartCopy` requests must be authenticated and signed by using IAM credentials (access key ID and secret
     *   access key for the IAM identities). All headers with the `x-amz-` prefix, including `x-amz-copy-source`, must be
     *   signed. For more information, see REST Authentication [^6].
     *
     *   **Directory buckets** - You must use IAM credentials to authenticate and authorize your access to the
     *   `UploadPartCopy` API operation, instead of using the temporary security credentials through the `CreateSession` API
     *   operation.
     *
     *   Amazon Web Services CLI or SDKs handles authentication and authorization on your behalf.
     * - `Permissions`:
     *
     *   You must have `READ` access to the source object and `WRITE` access to the destination bucket.
     *
     *   - **General purpose bucket permissions** - You must have the permissions in a policy based on the bucket types of
     *     your source bucket and destination bucket in an `UploadPartCopy` operation.
     *
     *     - If the source object is in a general purpose bucket, you must have the **`s3:GetObject`** permission to read
     *       the source object that is being copied.
     *     - If the destination bucket is a general purpose bucket, you must have the **`s3:PutObject`** permission to write
     *       the object copy to the destination bucket.
     *     - To perform a multipart upload with encryption using an Key Management Service key, the requester must have
     *       permission to the `kms:Decrypt` and `kms:GenerateDataKey` actions on the key. The requester must also have
     *       permissions for the `kms:GenerateDataKey` action for the `CreateMultipartUpload` API. Then, the requester needs
     *       permissions for the `kms:Decrypt` action on the `UploadPart` and `UploadPartCopy` APIs. These permissions are
     *       required because Amazon S3 must decrypt and read data from the encrypted file parts before it completes the
     *       multipart upload. For more information about KMS permissions, see Protecting data using server-side encryption
     *       with KMS [^7] in the *Amazon S3 User Guide*. For information about the permissions required to use the
     *       multipart upload API, see Multipart upload and permissions [^8] and Multipart upload API and permissions [^9]
     *       in the *Amazon S3 User Guide*.
     *
     *   - **Directory bucket permissions** - You must have permissions in a bucket policy or an IAM identity-based policy
     *     based on the source and destination bucket types in an `UploadPartCopy` operation.
     *
     *     - If the source object that you want to copy is in a directory bucket, you must have the
     *       **`s3express:CreateSession`** permission in the `Action` element of a policy to read the object. By default,
     *       the session is in the `ReadWrite` mode. If you want to restrict the access, you can explicitly set the
     *       `s3express:SessionMode` condition key to `ReadOnly` on the copy source bucket.
     *     - If the copy destination is a directory bucket, you must have the **`s3express:CreateSession`** permission in
     *       the `Action` element of a policy to write the object to the destination. The `s3express:SessionMode` condition
     *       key cannot be set to `ReadOnly` on the copy destination.
     *
     *     If the object is encrypted with SSE-KMS, you must also have the `kms:GenerateDataKey` and `kms:Decrypt`
     *     permissions in IAM identity-based policies and KMS key policies for the KMS key.
     *
     *     For example policies, see Example bucket policies for S3 Express One Zone [^10] and Amazon Web Services Identity
     *     and Access Management (IAM) identity-based policies for S3 Express One Zone [^11] in the *Amazon S3 User Guide*.
     *
     * - `Encryption`:
     *
     *   - **General purpose buckets ** - For information about using server-side encryption with customer-provided
     *     encryption keys with the `UploadPartCopy` operation, see CopyObject [^12] and UploadPart [^13].
     *   - **Directory buckets ** - For directory buckets, there are only two supported options for server-side encryption:
     *     server-side encryption with Amazon S3 managed keys (SSE-S3) (`AES256`) and server-side encryption with KMS keys
     *     (SSE-KMS) (`aws:kms`). For more information, see Protecting data with server-side encryption [^14] in the *Amazon
     *     S3 User Guide*.
     *
     *     > For directory buckets, when you perform a `CreateMultipartUpload` operation and an `UploadPartCopy` operation,
     *     > the request headers you provide in the `CreateMultipartUpload` request must match the default encryption
     *     > configuration of the destination bucket.
     *
     *     S3 Bucket Keys aren't supported, when you copy SSE-KMS encrypted objects from general purpose buckets to
     *     directory buckets, from directory buckets to general purpose buckets, or between directory buckets, through
     *     UploadPartCopy [^15]. In this case, Amazon S3 makes a call to KMS every time a copy request is made for a
     *     KMS-encrypted object.
     *
     * - `Special errors`:
     *
     *   - Error Code: `NoSuchUpload`
     *
     *     - Description: The specified multipart upload does not exist. The upload ID might be invalid, or the multipart
     *       upload might have been aborted or completed.
     *     - HTTP Status Code: 404 Not Found
     *
     *   - Error Code: `InvalidRequest`
     *
     *     - Description: The specified copy source is not supported as a byte-range copy source.
     *     - HTTP Status Code: 400 Bad Request
     *
     *
     * - `HTTP Host header syntax`:
     *
     *   **Directory buckets ** - The HTTP Host header syntax is `*Bucket_name*.s3express-*az_id*.*region*.amazonaws.com`.
     *
     * The following operations are related to `UploadPartCopy`:
     *
     * - CreateMultipartUpload [^16]
     * - UploadPart [^17]
     * - CompleteMultipartUpload [^18]
     * - AbortMultipartUpload [^19]
     * - ListParts [^20]
     * - ListMultipartUploads [^21]
     *
     * [^1]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/qfacts.html
     * [^2]: https://docs.aws.amazon.com/AmazonS3/latest/API/API_UploadPart.html
     * [^3]: https://docs.aws.amazon.com/AmazonS3/latest/dev/uploadobjusingmpu.html
     * [^4]: https://docs.aws.amazon.com/AmazonS3/latest/dev/ObjectOperations.html
     * [^5]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/s3-express-Regions-and-Zones.html
     * [^6]: https://docs.aws.amazon.com/AmazonS3/latest/dev/RESTAuthentication.html
     * [^7]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/UsingKMSEncryption.html
     * [^8]: https://docs.aws.amazon.com/AmazonS3/latest/dev/mpuAndPermissions.html
     * [^9]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/mpuoverview.html#mpuAndPermissions
     * [^10]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/s3-express-security-iam-example-bucket-policies.html
     * [^11]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/s3-express-security-iam-identity-policies.html
     * [^12]: https://docs.aws.amazon.com/AmazonS3/latest/API/API_CopyObject.html
     * [^13]: https://docs.aws.amazon.com/AmazonS3/latest/API/API_UploadPart.html
     * [^14]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/s3-express-serv-side-encryption.html
     * [^15]: https://docs.aws.amazon.com/AmazonS3/latest/API/API_UploadPartCopy.html
     * [^16]: https://docs.aws.amazon.com/AmazonS3/latest/API/API_CreateMultipartUpload.html
     * [^17]: https://docs.aws.amazon.com/AmazonS3/latest/API/API_UploadPart.html
     * [^18]: https://docs.aws.amazon.com/AmazonS3/latest/API/API_CompleteMultipartUpload.html
     * [^19]: https://docs.aws.amazon.com/AmazonS3/latest/API/API_AbortMultipartUpload.html
     * [^20]: https://docs.aws.amazon.com/AmazonS3/latest/API/API_ListParts.html
     * [^21]: https://docs.aws.amazon.com/AmazonS3/latest/API/API_ListMultipartUploads.html
     *
     * @see http://docs.amazonwebservices.com/AmazonS3/latest/API/mpUploadUploadPartCopy.html
     * @see https://docs.aws.amazon.com/AmazonS3/latest/API/API_UploadPartCopy.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-s3-2006-03-01.html#uploadpartcopy
     *
     * @param array{
     *   Bucket: string,
     *   CopySource: string,
     *   CopySourceIfMatch?: null|string,
     *   CopySourceIfModifiedSince?: null|\DateTimeImmutable|string,
     *   CopySourceIfNoneMatch?: null|string,
     *   CopySourceIfUnmodifiedSince?: null|\DateTimeImmutable|string,
     *   CopySourceRange?: null|string,
     *   Key: string,
     *   PartNumber: int,
     *   UploadId: string,
     *   SSECustomerAlgorithm?: null|string,
     *   SSECustomerKey?: null|string,
     *   SSECustomerKeyMD5?: null|string,
     *   CopySourceSSECustomerAlgorithm?: null|string,
     *   CopySourceSSECustomerKey?: null|string,
     *   CopySourceSSECustomerKeyMD5?: null|string,
     *   RequestPayer?: null|RequestPayer::*,
     *   ExpectedBucketOwner?: null|string,
     *   ExpectedSourceBucketOwner?: null|string,
     *   '@region'?: string|null,
     * }|UploadPartCopyRequest $input
     */
    public function uploadPartCopy($input): UploadPartCopyOutput
    {
        $input = UploadPartCopyRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'UploadPartCopy', 'region' => $input->getRegion()]));

        return new UploadPartCopyOutput($response);
    }

    protected function getAwsErrorFactory(): AwsErrorFactoryInterface
    {
        return new XmlAwsErrorFactory();
    }

    protected function getEndpoint(string $uri, array $query, ?string $region): string
    {
        $uriParts = explode('/', $uri, 3);
        $bucket = explode('?', $uriParts[1] ?? '', 2)[0];
        $uriWithOutBucket = substr($uriParts[1] ?? '', \strlen($bucket)) . ($uriParts[2] ?? '');
        $bucketLen = \strlen($bucket);
        $configuration = $this->getConfiguration();

        if (
            $bucketLen < 3 || $bucketLen > 63
            || filter_var($bucket, \FILTER_VALIDATE_IP) // Cannot look like an IP address
            || !preg_match('/^[a-z0-9]([a-z0-9\-]*[a-z0-9])?$/', $bucket) // Bucket cannot have dot (because of TLS)
            || filter_var(parse_url($configuration->get('endpoint'), \PHP_URL_HOST), \FILTER_VALIDATE_IP) // Custom endpoint cannot look like an IP address
            || filter_var($configuration->get('pathStyleEndpoint'), \FILTER_VALIDATE_BOOLEAN)
        ) {
            return parent::getEndpoint($uri, $query, $region);
        }

        return preg_replace('|https?://|', '${0}' . $bucket . '.', parent::getEndpoint('/' . $uriWithOutBucket, $query, $region));
    }

    protected function getEndpointMetadata(?string $region): array
    {
        if (null === $region) {
            return [
                'endpoint' => 'https://s3.amazonaws.com',
                'signRegion' => 'us-east-1',
                'signService' => 's3',
                'signVersions' => ['s3v4'],
            ];
        }

        switch ($region) {
            case 'af-south-1':
            case 'ap-east-1':
            case 'ap-northeast-1':
            case 'ap-northeast-2':
            case 'ap-northeast-3':
            case 'ap-south-1':
            case 'ap-south-2':
            case 'ap-southeast-1':
            case 'ap-southeast-2':
            case 'ap-southeast-3':
            case 'ap-southeast-4':
            case 'ap-southeast-5':
            case 'ca-central-1':
            case 'ca-west-1':
            case 'eu-central-1':
            case 'eu-central-2':
            case 'eu-north-1':
            case 'eu-south-1':
            case 'eu-south-2':
            case 'eu-west-1':
            case 'eu-west-2':
            case 'eu-west-3':
            case 'il-central-1':
            case 'me-central-1':
            case 'me-south-1':
            case 'sa-east-1':
            case 'us-east-1':
            case 'us-east-2':
            case 'us-gov-east-1':
            case 'us-gov-west-1':
            case 'us-west-1':
            case 'us-west-2':
                return [
                    'endpoint' => "https://s3.$region.amazonaws.com",
                    'signRegion' => $region,
                    'signService' => 's3',
                    'signVersions' => ['s3v4'],
                ];
            case 'cn-north-1':
            case 'cn-northwest-1':
                return [
                    'endpoint' => "https://s3.$region.amazonaws.com.cn",
                    'signRegion' => $region,
                    'signService' => 's3',
                    'signVersions' => ['s3v4'],
                ];
            case 's3-external-1':
                return [
                    'endpoint' => 'https://s3-external-1.amazonaws.com',
                    'signRegion' => 'us-east-1',
                    'signService' => 's3',
                    'signVersions' => ['s3v4'],
                ];
            case 'fips-ca-central-1':
                return [
                    'endpoint' => 'https://s3-fips.ca-central-1.amazonaws.com',
                    'signRegion' => 'ca-central-1',
                    'signService' => 's3',
                    'signVersions' => ['s3v4'],
                ];
            case 'fips-ca-west-1':
                return [
                    'endpoint' => 'https://s3-fips.ca-west-1.amazonaws.com',
                    'signRegion' => 'ca-west-1',
                    'signService' => 's3',
                    'signVersions' => ['s3v4'],
                ];
            case 'fips-us-east-1':
                return [
                    'endpoint' => 'https://s3-fips.us-east-1.amazonaws.com',
                    'signRegion' => 'us-east-1',
                    'signService' => 's3',
                    'signVersions' => ['s3v4'],
                ];
            case 'fips-us-east-2':
                return [
                    'endpoint' => 'https://s3-fips.us-east-2.amazonaws.com',
                    'signRegion' => 'us-east-2',
                    'signService' => 's3',
                    'signVersions' => ['s3v4'],
                ];
            case 'fips-us-west-1':
                return [
                    'endpoint' => 'https://s3-fips.us-west-1.amazonaws.com',
                    'signRegion' => 'us-west-1',
                    'signService' => 's3',
                    'signVersions' => ['s3v4'],
                ];
            case 'fips-us-west-2':
                return [
                    'endpoint' => 'https://s3-fips.us-west-2.amazonaws.com',
                    'signRegion' => 'us-west-2',
                    'signService' => 's3',
                    'signVersions' => ['s3v4'],
                ];
            case 'fips-us-gov-east-1':
                return [
                    'endpoint' => 'https://s3-fips.us-gov-east-1.amazonaws.com',
                    'signRegion' => 'us-gov-east-1',
                    'signService' => 's3',
                    'signVersions' => ['s3v4'],
                ];
            case 'fips-us-gov-west-1':
                return [
                    'endpoint' => 'https://s3-fips.us-gov-west-1.amazonaws.com',
                    'signRegion' => 'us-gov-west-1',
                    'signService' => 's3',
                    'signVersions' => ['s3v4'],
                ];
            case 'us-iso-east-1':
            case 'us-iso-west-1':
                return [
                    'endpoint' => "https://s3.$region.c2s.ic.gov",
                    'signRegion' => $region,
                    'signService' => 's3',
                    'signVersions' => ['s3v4'],
                ];
            case 'us-isob-east-1':
                return [
                    'endpoint' => 'https://s3.us-isob-east-1.sc2s.sgov.gov',
                    'signRegion' => 'us-isob-east-1',
                    'signService' => 's3',
                    'signVersions' => ['s3v4'],
                ];
            case 'fips-us-iso-east-1':
                return [
                    'endpoint' => 'https://s3-fips.us-iso-east-1.c2s.ic.gov',
                    'signRegion' => 'us-iso-east-1',
                    'signService' => 's3',
                    'signVersions' => ['s3v4'],
                ];
            case 'fips-us-iso-west-1':
                return [
                    'endpoint' => 'https://s3-fips.us-iso-west-1.c2s.ic.gov',
                    'signRegion' => 'us-iso-west-1',
                    'signService' => 's3',
                    'signVersions' => ['s3v4'],
                ];
            case 'fips-us-isob-east-1':
                return [
                    'endpoint' => 'https://s3-fips.us-isob-east-1.sc2s.sgov.gov',
                    'signRegion' => 'us-isob-east-1',
                    'signService' => 's3',
                    'signVersions' => ['s3v4'],
                ];
        }

        return [
            'endpoint' => 'https://s3.amazonaws.com',
            'signRegion' => 'us-east-1',
            'signService' => 's3',
            'signVersions' => ['s3v4'],
        ];
    }

    protected function getServiceCode(): string
    {
        @trigger_error('Using the client with an old version of Core is deprecated. Run "composer update async-aws/core".', \E_USER_DEPRECATED);

        return 's3';
    }

    protected function getSignatureScopeName(): string
    {
        @trigger_error('Using the client with an old version of Core is deprecated. Run "composer update async-aws/core".', \E_USER_DEPRECATED);

        return 's3';
    }

    protected function getSignatureVersion(): string
    {
        @trigger_error('Using the client with an old version of Core is deprecated. Run "composer update async-aws/core".', \E_USER_DEPRECATED);

        return 's3v4';
    }

    protected function getSignerFactories(): array
    {
        return [
            's3v4' => function (string $service, string $region) {
                $configuration = $this->getConfiguration();
                $options = [];

                // We need async-aws/core: 1.8 or above to use sendChunkedBody.
                if (Configuration::optionExists('sendChunkedBody')) {
                    $options['sendChunkedBody'] = filter_var($configuration->get('sendChunkedBody'), \FILTER_VALIDATE_BOOLEAN);
                }

                return new SignerV4ForS3($service, $region, $options);
            },
        ] + parent::getSignerFactories();
    }
}
