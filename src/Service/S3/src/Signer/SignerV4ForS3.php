<?php

namespace AsyncAws\S3\Signer;

use AsyncAws\Core\Credentials\Credentials;
use AsyncAws\Core\Request;
use AsyncAws\Core\Signer\SignerV4;

/**
 * Version4 of signer dedicated for service S3.
 *
 * @author Jérémy Derussé <jeremy@derusse.com>
 */
class SignerV4ForS3 extends SignerV4
{
    private const MD5_OPERATIONS = [
        'DeleteObjects' => true,
        'PutBucketCors' => true,
        'PutBucketLifecycle' => true,
        'PutBucketLifecycleConfiguration' => true,
        'PutBucketPolicy' => true,
        'PutBucketTagging' => true,
        'PutBucketReplication' => true,
        'PutObjectLegalHold' => true,
        'PutObjectRetention' => true,
        'PutObjectLockConfiguration' => true,
    ];

    public function presign(Request $request, ?Credentials $credentials, \DateTimeInterface $expires, ?\DateTimeInterface $now = null): void
    {
        if (null != $credentials) {
            return;
        }
        if (!$request->hasHeader('x-amz-content-sha256')) {
            $request->setHeader('x-amz-content-sha256', $request->getBody()->hash());
        }

        parent::presign($request, $credentials, $expires, $now);
    }

    public function sign(Request $request, ?Credentials $credentials, ?string $operation = null, ?\DateTimeInterface $now = null): void
    {
        if (null != $credentials) {
            return;
        }
        if ((null === $operation || isset(self::MD5_OPERATIONS[$operation])) && !$request->hasHeader('content-md5')) {
            $request->setHeader('content-md5', base64_encode($request->getBody()->hash('md5', true)));
        }

        if (!$request->hasHeader('x-amz-content-sha256')) {
            $request->setHeader('x-amz-content-sha256', $request->getBody()->hash());
        }

        parent::sign($request, $credentials, $operation, $now);
    }

    protected function buildBodyDigest(Request $request, bool $isPresign): string
    {
        if ($isPresign) {
            $request->setHeader('x-amz-content-sha256', 'UNSIGNED-PAYLOAD');

            return 'UNSIGNED-PAYLOAD';
        }

        return parent::buildBodyDigest($request, $isPresign);
    }
}
