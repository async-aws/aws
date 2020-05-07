<?php

namespace AsyncAws\S3\Signer;

use AsyncAws\Core\Credentials\Credentials;
use AsyncAws\Core\Request;
use AsyncAws\Core\RequestContext;
use AsyncAws\Core\Signer\SignerV4;
use AsyncAws\Core\Signer\SigningContext;
use AsyncAws\Core\Stream\FixedSizeStream;
use AsyncAws\Core\Stream\IterableStream;
use AsyncAws\Core\Stream\RequestStream;
use AsyncAws\Core\Stream\RewindableStream;
use AsyncAws\Core\Stream\StringStream;

/**
 * Version4 of signer dedicated for service S3.
 *
 * @author Jérémy Derussé <jeremy@derusse.com>
 */
class SignerV4ForS3 extends SignerV4
{
    private const ALGORITHM_CHUNK = 'AWS4-HMAC-SHA256-PAYLOAD';
    private const CHUNK_SIZE = 64 * 1024;

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

    public function sign(Request $request, Credentials $credentials, RequestContext $context): void
    {
        if ((null === ($operation = $context->getOperation()) || isset(self::MD5_OPERATIONS[$operation])) && !$request->hasHeader('content-md5')) {
            $request->setHeader('content-md5', base64_encode($request->getBody()->hash('md5', true)));
        }

        if (!$request->hasHeader('x-amz-content-sha256')) {
            $request->setHeader('x-amz-content-sha256', $request->getBody()->hash());
        }

        parent::sign($request, $credentials, $context);
    }

    protected function buildBodyDigest(Request $request, bool $isPresign): string
    {
        if ($isPresign) {
            $request->setHeader('x-amz-content-sha256', 'UNSIGNED-PAYLOAD');

            return 'UNSIGNED-PAYLOAD';
        }

        return parent::buildBodyDigest($request, $isPresign);
    }

    protected function convertBodyToStream(SigningContext $context): void
    {
        $request = $context->getRequest();
        $body = $request->getBody();
        if ($request->hasHeader('content-length')) {
            $contentLength = (int) $request->getHeader('content-length');
        } else {
            $contentLength = $body->length();
        }

        // If content length is unknown, use the rewindable stream to read it once locally in order to get the length
        if (null === $contentLength) {
            $request->setBody($body = RewindableStream::create($body));
            $body->read();
            $contentLength = $body->length();
        }

        // no need to stream small body. It's simple to convert it to string directly
        if ($contentLength < self::CHUNK_SIZE) {
            $request->setBody($body = StringStream::create($body));

            return;
        }

        // Convert the body into a chunked stream
        $request->setHeader('content-encoding', 'aws-chunked');
        $request->setHeader('x-amz-decoded-content-length', (string) $contentLength);
        $request->setHeader('x-amz-content-sha256', 'STREAMING-' . self::ALGORITHM_CHUNK);

        // Compute size of content + metadata used sign each Chunk
        $chunkCount = (int) ceil($contentLength / self::CHUNK_SIZE);
        $fullChunkCount = $chunkCount * self::CHUNK_SIZE === $contentLength ? $chunkCount : ($chunkCount - 1);
        $metaLength = \strlen(";chunk-signature=\r\n\r\n") + 64;
        $request->setHeader('content-length', (string) ($contentLength + $fullChunkCount * ($metaLength + \strlen((string) dechex(self::CHUNK_SIZE))) + ($chunkCount - $fullChunkCount) * ($metaLength + \strlen((string) dechex($contentLength % self::CHUNK_SIZE))) + $metaLength + 1));
        $body = IterableStream::create((function (RequestStream $body) use ($context): iterable {
            $now = $context->getNow();
            $credentialString = $context->getCredentialString();
            $signingKey = $context->getSigningKey();
            $signature = $context->getSignature();
            foreach (FixedSizeStream::create($body, self::CHUNK_SIZE) as $chunk) {
                $stringToSign = $this->buildChunkStringToSign($now, $credentialString, $signature, $chunk);
                $context->setSignature($signature = $this->buildSignature($stringToSign, $signingKey));
                yield sprintf("%s;chunk-signature=%s\r\n", dechex(\strlen($chunk)), $signature) . "$chunk\r\n";
            }

            $stringToSign = $this->buildChunkStringToSign($now, $credentialString, $signature, '');
            $context->setSignature($signature = $this->buildSignature($stringToSign, $signingKey));

            yield sprintf("%s;chunk-signature=%s\r\n\r\n", dechex(0), $signature);
        })($body));

        $request->setBody($body);
    }

    private function buildChunkStringToSign(\DateTimeImmutable $now, string $credentialString, string $signature, string $chunk): string
    {
        static $emptyHash;
        $emptyHash = $emptyHash ?? hash('sha256', '');

        return implode("\n", [
            self::ALGORITHM_CHUNK,
            $now->format('Ymd\THis\Z'),
            $credentialString,
            $signature,
            $emptyHash,
            hash('sha256', $chunk),
        ]);
    }

    private function buildSignature(string $stringToSign, string $signingKey): string
    {
        return hash_hmac('sha256', $stringToSign, $signingKey);
    }
}
