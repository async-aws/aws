<?php

namespace AsyncAws\Core\Signer;

use AsyncAws\Core\Credentials\Credentials;
use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Stream\FixedSizeStream;
use AsyncAws\Core\Stream\IterableStream;
use AsyncAws\Core\Stream\Stream;
use AsyncAws\Core\Stream\StringStream;

/**
 * Version4 of signer.
 *
 * @author Jérémy Derussé <jeremy@derusse.com>
 */
class SignerV4 implements Signer
{
    private const ALGORITHM_REQUEST = 'AWS4-HMAC-SHA256';
    private const ALGORITHM_CHUNK = self::ALGORITHM_REQUEST . '-PAYLOAD';
    private const CHUNK_SIZE = 64 * 1024;

    private const BLACKLIST_HEADERS = [
        'cache-control' => true,
        'content-type' => true,
        'content-length' => true,
        'expect' => true,
        'max-forwards' => true,
        'pragma' => true,
        'range' => true,
        'te' => true,
        'if-match' => true,
        'if-none-match' => true,
        'if-modified-since' => true,
        'if-unmodified-since' => true,
        'if-range' => true,
        'accept' => true,
        'authorization' => true,
        'proxy-authorization' => true,
        'from' => true,
        'referer' => true,
        'user-agent' => true,
        'x-amzn-trace-id' => true,
        'aws-sdk-invocation-id' => true,
        'aws-sdk-retry' => true,
    ];

    private $scopeName;

    private $region;

    public function __construct(string $scopeName, string $region)
    {
        $this->scopeName = $scopeName;
        $this->region = $region;
    }

    public function sign(Request $request, ?Credentials $credentials): void
    {
        if (null === $credentials) {
            return;
        }

        if (false === $parsedUrl = parse_url($request->getUrl())) {
            throw new InvalidArgument(sprintf('The endpoint "%s" is invalid.', $request->getUrl()));
        }
        if (null !== $sessionToken = $credentials->getSessionToken()) {
            $request->setHeader('x-amz-security-token', $sessionToken);
        }

        $request->setHeader('host', $parsedUrl['host']);
        $request->setHeader('x-amz-date', $amzDate = gmdate('Ymd\THis\Z'));
        $credentialScope = [substr($amzDate, 0, 8), $this->region, $this->scopeName, 'aws4_request'];

        $signingKey = 'AWS4' . $credentials->getSecretKey();
        foreach ($credentialScope as $scopePart) {
            $signingKey = hash_hmac('sha256', $scopePart, $signingKey, true);
        }

        $signature = '';
        $this->prepareBody($request, $amzDate, implode('/', $credentialScope), $signature, $signingKey);

        $canonicalHeaders = $this->getCanonicalizedHeaders($request);

        $canonicalRequest = implode("\n", [
            $request->getMethod(),
            $this->getCanonicalizedPath($parsedUrl),
            $this->getCanonicalizedQuery($parsedUrl),
            \implode("\n", array_values($canonicalHeaders)),
            '', // empty line after headers
            implode(';', \array_keys($canonicalHeaders)),
            $request->getHeader('x-amz-content-sha256'),
        ]);

        $signature = hash_hmac('sha256', implode("\n", [
            self::ALGORITHM_REQUEST,
            $amzDate,
            implode('/', $credentialScope),
            hash('sha256', $canonicalRequest),
        ]), $signingKey);

        $authorizationHeader = sprintf(
            '%s Credential=%s/%s, SignedHeaders=%s, Signature=%s',
            self::ALGORITHM_REQUEST,
            $credentials->getAccessKeyId(),
            implode('/', $credentialScope),
            implode(';', \array_keys($canonicalHeaders)),
            $signature
        );

        $request->setHeader('authorization', $authorizationHeader);
    }

    private function prepareBody(Request $request, string $amzDate, string $credentialScope, string &$signature, string $signingKey): void
    {
        $body = $request->getBody();

        if ($request->hasHeader('content-length')) {
            $contentLength = (int) ((array) $request->getHeader('content-length'))[0];
        } else {
            $contentLength = $body->length();
        }

        // we can't manage signature of undefined length. Let's convert it to string
        if (null === $contentLength) {
            $request->setBody($body = StringStream::create($body));
            $contentLength = $body->length();
        }

        // no need to stream small body
        if ($contentLength < self::CHUNK_SIZE) {
            $request->setBody($body = StringStream::create($body));
            $request->setHeader('x-amz-content-sha256', hash('sha256', $body->stringify()));
            $request->setHeader('content-length', $contentLength);

            return;
        }

        $request->setHeader('content-encoding', 'aws-chunked');
        $request->setHeader('x-amz-decoded-content-length', $contentLength);
        $request->setHeader('x-amz-content-sha256', 'STREAMING-' . self::ALGORITHM_CHUNK);

        // Compute size of content + metadata used sign each Chunk
        $chunkCount = (int) ceil($contentLength / self::CHUNK_SIZE);
        $fullChunkCount = $chunkCount * self::CHUNK_SIZE === $contentLength ? $chunkCount : ($chunkCount - 1);
        $metaLength = \strlen(";chunk-signature=\r\n\r\n") + 64;
        $request->setHeader('content-length', $contentLength + $fullChunkCount * ($metaLength + \strlen((string) dechex(self::CHUNK_SIZE))) + ($chunkCount - $fullChunkCount) * ($metaLength + \strlen((string) dechex($contentLength % self::CHUNK_SIZE))) + $metaLength + 1);

        $body = IterableStream::create((static function (Stream $body) use ($amzDate, $credentialScope, $signingKey, &$signature): iterable {
            $emptyHash = hash('sha256', '');
            foreach (FixedSizeStream::create($body, self::CHUNK_SIZE) as $chunk) {
                $signature = hash_hmac('sha256', implode("\n", [
                    self::ALGORITHM_CHUNK,
                    $amzDate,
                    $credentialScope,
                    $signature,
                    $emptyHash,
                    hash('sha256', $chunk),
                ]), $signingKey);

                yield sprintf("%s;chunk-signature=%s\r\n", dechex(\strlen($chunk)), $signature) . "$chunk\r\n";
            }

            $signature = hash_hmac('sha256', implode("\n", [
                self::ALGORITHM_CHUNK,
                $amzDate,
                $credentialScope,
                $signature,
                $emptyHash,
                $emptyHash,
            ]), $signingKey);

            yield sprintf("%s;chunk-signature=%s\r\n\r\n", dechex(0), $signature);
        })($body));

        $request->setBody($body);
    }

    private function getCanonicalizedQuery(array $parseUrl): string
    {
        \parse_str($parseUrl['query'] ?? '', $query);
        unset($query['X-Amz-Signature']);

        if (!$query) {
            return '';
        }

        ksort($query);
        $encodedQuery = [];
        foreach ($query as $key => $values) {
            if (!\is_array($values)) {
                $encodedQuery[] = rawurlencode($key) . '=' . rawurlencode($values);

                continue;
            }

            sort($values);
            foreach ($values as $value) {
                $encodedQuery[] = rawurlencode($key) . '=' . rawurlencode($value);
            }
        }

        return implode('&', $encodedQuery);
    }

    private function getCanonicalizedPath(array $parseUrl): string
    {
        $doubleEncoded = rawurlencode(ltrim($parseUrl['path'] ?? '/', '/'));

        return '/' . str_replace('%2F', '/', $doubleEncoded);
    }

    private function getCanonicalizedHeaders(Request $request): array
    {
        // Case-insensitively aggregate all of the headers.
        $canonicalHeaders = [];
        foreach ($request->getHeaders() as $key => $values) {
            $key = strtolower($key);
            if (isset(self::BLACKLIST_HEADERS[$key])) {
                continue;
            }

            if (\is_array($values)) {
                sort($values);
                $value = \implode(',', $values);
            } else {
                $value = $values;
            }

            $canonicalHeaders[$key] = "$key:$value";
        }
        ksort($canonicalHeaders);

        return $canonicalHeaders;
    }
}
