<?php

namespace AsyncAws\Core\Signer;

use AsyncAws\Core\Credentials\Credentials;
use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Request;
use AsyncAws\Core\RequestContext;
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

    public function presign(Request $request, Credentials $credentials, RequestContext $context): void
    {
        $now = $context->getCurrentDate() ?? new \DateTimeImmutable();
        $expires = $context->getExpirationDate() ?? (new \DateTimeImmutable($now->format(\DateTimeInterface::ATOM)))->add(new \DateInterval('PT1H'));

        $this->handleSignature($request, $credentials, $now, $expires, true);
    }

    public function sign(Request $request, Credentials $credentials, RequestContext $context): void
    {
        $now = $context->getCurrentDate() ?? new \DateTimeImmutable();

        $this->handleSignature($request, $credentials, $now, $now, false);
    }

    protected function buildBodyDigest(Request $request, bool $isPresign): string
    {
        if ($request->hasHeader('x-amz-content-sha256')) {
            $hash = ((array) $request->getHeader('x-amz-content-sha256'))[0];
        } else {
            $hash = $request->getBody()->hash();
        }

        if ('UNSIGNED-PAYLOAD' === $hash) {
            $request->setHeader('x-amz-content-sha256', $hash);
        }

        return $hash;
    }

    private function handleSignature(Request $request, Credentials $credentials, \DateTimeInterface $now, \DateTimeInterface $expires, bool $isPresign): void
    {
        $this->removePresign($request);
        $this->sanitizeHostForHeader($request);
        $this->assignAmzQueryValues($request, $credentials, $isPresign);

        $this->buildTime($request, $now, $expires, $isPresign);
        $credentialScope = $this->buildCredentialString($request, $credentials, $now, $isPresign);
        $credentialString = \implode('/', $credentialScope);
        $signingKey = $this->buildSigningKey($credentials, $credentialScope);

        // signature is passed by reference to convertBodyToStream
        $signature = '';
        if ($isPresign) {
            // Should be called before `buildBodyDigest` because this method may alter the body
            $this->convertBodyToQuery($request);
        } else {
            $this->convertBodyToStream($request, $now, $credentialString, $signingKey, $signature);
        }

        $bodyDigest = $this->buildBodyDigest($request, $isPresign);

        if ($isPresign) {
            // Should be called after `buildBodyDigest` because header this method may remove `x-amz-content-sha256`
            $this->convertHeaderToQuery($request);
        }

        $canonicalHeaders = $this->buildCanonicalHeaders($request, $isPresign);
        $canonicalRequest = $this->buildCanonicalRequest($request, $canonicalHeaders, $bodyDigest);
        $stringToSign = $this->buildStringToSign($now, $credentialString, $canonicalRequest);
        $signature = $this->buildSignature($stringToSign, $signingKey);

        if ($isPresign) {
            $request->setQueryAttribute('X-Amz-Signature', $signature);
        } else {
            $request->setHeader('authorization', sprintf(
                '%s Credential=%s/%s, SignedHeaders=%s, Signature=%s',
                self::ALGORITHM_REQUEST,
                $credentials->getAccessKeyId(),
                implode('/', $credentialScope),
                implode(';', \array_keys($canonicalHeaders)),
                $signature
            ));
        }
    }

    private function removePresign(Request $request): void
    {
        $request->removeQueryAttribute('X-Amz-Algorithm');
        $request->removeQueryAttribute('X-Amz-Signature');
        $request->removeQueryAttribute('X-Amz-Security-Token');
        $request->removeQueryAttribute('X-Amz-Date');
        $request->removeQueryAttribute('X-Amz-Expires');
        $request->removeQueryAttribute('X-Amz-Credential');
        $request->removeQueryAttribute('X-Amz-SignedHeaders');
    }

    private function sanitizeHostForHeader(Request $request): void
    {
        if (false === $parsedUrl = parse_url($request->getEndpoint())) {
            throw new InvalidArgument(sprintf('The endpoint "%s" is invalid.', $request->getEndpoint()));
        }

        $host = $parsedUrl['host'];
        if (isset($parsedUrl['port'])) {
            $host .= ':' . $parsedUrl['port'];
        }

        $request->setHeader('host', $host);
    }

    private function assignAmzQueryValues(Request $request, Credentials $credentials, bool $isPresign): void
    {
        if ($isPresign) {
            $request->setQueryAttribute('X-Amz-Algorithm', self::ALGORITHM_REQUEST);
            if (null !== $sessionToken = $credentials->getSessionToken()) {
                $request->setQueryAttribute('X-Amz-Security-Token', $sessionToken);
            }

            return;
        }

        if (null !== $sessionToken = $credentials->getSessionToken()) {
            $request->setHeader('x-amz-security-token', $sessionToken);
        }
    }

    private function buildTime(Request $request, \DateTimeInterface $now, \DateTimeInterface $expires, bool $isPresign): void
    {
        if ($isPresign) {
            $duration = $expires->getTimestamp() - $now->getTimestamp();
            if ($duration > 604800) {
                throw new InvalidArgument('The expiration date of presigned URL must be less than one week');
            }
            if ($duration < 0) {
                throw new InvalidArgument('The expiration date of presigned URL must be in the future');
            }

            $request->setQueryAttribute('X-Amz-Date', gmdate('Ymd\THis\Z', $now->getTimestamp()));
            $request->setQueryAttribute('X-Amz-Expires', $duration);
        } else {
            $request->setHeader('X-Amz-Date', gmdate('Ymd\THis\Z', $now->getTimestamp()));
        }
    }

    private function buildCredentialString(Request $request, Credentials $credentials, \DateTimeInterface $now, bool $isPresign): array
    {
        $credentialScope = [gmdate('Ymd', $now->getTimestamp()), $this->region, $this->scopeName, 'aws4_request'];

        if ($isPresign) {
            $request->setQueryAttribute('X-Amz-Credential', $credentials->getAccessKeyId() . '/' . implode('/', $credentialScope));
        }

        return $credentialScope;
    }

    private function convertHeaderToQuery(Request $request): void
    {
        foreach ($request->getHeaders() as $name => $value) {
            if ('x-amz' === substr($name, 0, 5)) {
                $request->setQueryAttribute($name, $value);
            }

            if (isset(self::BLACKLIST_HEADERS[$name])) {
                $request->removeHeader($name);
            }
        }
        $request->removeHeader('x-amz-content-sha256');
    }

    private function convertBodyToQuery(Request $request): void
    {
        if ('POST' !== $request->getMethod()) {
            return;
        }

        $request->setMethod('GET');
        if ('application/x-www-form-urlencoded' === $request->getHeader('Content-Type')) {
            \parse_str($request->getBody()->stringify(), $params);
            foreach ($params as $name => $value) {
                $request->setQueryAttribute($name, $value);
            }
        }

        $request->removeHeader('content-type');
        $request->removeHeader('content-length');
        $request->setBody(StringStream::create(''));
    }

    private function convertBodyToStream(Request $request, \DateTimeInterface $now, string $credentialString, string $signingKey, string &$signature): void
    {
        $body = $request->getBody();
        if ($request->hasHeader('content-length')) {
            $contentLength = (int) ((array) $request->getHeader('content-length'))[0];
        } else {
            $contentLength = $body->length();
        }

        // no need to stream small body. It's simple to convert it to string directly
        if ($contentLength < self::CHUNK_SIZE) {
            $request->setBody($body = StringStream::create($body));

            return;
        }

        // Convert the body into a chunked stream
        $request->setHeader('content-encoding', 'aws-chunked');
        $request->setHeader('x-amz-decoded-content-length', $contentLength);
        $request->setHeader('x-amz-content-sha256', 'STREAMING-' . self::ALGORITHM_CHUNK);

        // Compute size of content + metadata used sign each Chunk
        $chunkCount = (int) ceil($contentLength / self::CHUNK_SIZE);
        $fullChunkCount = $chunkCount * self::CHUNK_SIZE === $contentLength ? $chunkCount : ($chunkCount - 1);
        $metaLength = \strlen(";chunk-signature=\r\n\r\n") + 64;
        $request->setHeader('content-length', $contentLength + $fullChunkCount * ($metaLength + \strlen((string) dechex(self::CHUNK_SIZE))) + ($chunkCount - $fullChunkCount) * ($metaLength + \strlen((string) dechex($contentLength % self::CHUNK_SIZE))) + $metaLength + 1);

        $body = IterableStream::create((function (Stream $body) use ($now, $credentialString, $signingKey, &$signature): iterable {
            foreach (FixedSizeStream::create($body, self::CHUNK_SIZE) as $chunk) {
                $stringToSign = $this->buildChunkStringToSign($now, $credentialString, $signature, $chunk);
                $signature = $this->buildSignature($stringToSign, $signingKey);

                yield sprintf("%s;chunk-signature=%s\r\n", dechex(\strlen($chunk)), $signature) . "$chunk\r\n";
            }

            $stringToSign = $this->buildChunkStringToSign($now, $credentialString, $signature, '');
            $signature = $this->buildSignature($stringToSign, $signingKey);

            yield sprintf("%s;chunk-signature=%s\r\n\r\n", dechex(0), $signature);
        })($body));

        $request->setBody($body);
    }

    private function buildCanonicalHeaders(Request $request, bool $isPresign): array
    {
        // Case-insensitively aggregate all of the headers.
        $canonicalHeaders = [];
        foreach ($request->getHeaders() as $key => $values) {
            $key = strtolower($key);
            if (isset(self::BLACKLIST_HEADERS[$key])) {
                continue;
            }

            if (!\is_array($values) && false !== \strpos($values, ',')) {
                $values = array_map('trim', \explode(',', $values));
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

        if ($isPresign) {
            $request->setQueryAttribute('X-Amz-SignedHeaders', \implode(';', array_keys($canonicalHeaders)));
        }

        return $canonicalHeaders;
    }

    private function buildCanonicalRequest(Request $request, array $canonicalHeaders, string $bodyDigest): string
    {
        return implode("\n", [
            $request->getMethod(),
            $this->buildCanonicalPath($request),
            $this->buildCanonicalQuery($request),
            \implode("\n", array_values($canonicalHeaders)),
            '', // empty line after headers
            implode(';', \array_keys($canonicalHeaders)),
            $bodyDigest,
        ]);
    }

    private function buildCanonicalQuery(Request $request): string
    {
        $query = $request->getQuery();

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

    private function buildCanonicalPath(Request $request): string
    {
        $doubleEncoded = rawurlencode(ltrim($request->getUri(), '/'));

        return '/' . str_replace('%2F', '/', $doubleEncoded);
    }

    private function buildStringToSign(\DateTimeInterface $now, string $credentialString, string $canonicalRequest): string
    {
        return implode("\n", [
            self::ALGORITHM_REQUEST,
            gmdate('Ymd\THis\Z', $now->getTimestamp()),
            $credentialString,
            hash('sha256', $canonicalRequest),
        ]);
    }

    private function buildChunkStringToSign(\DateTimeInterface $now, string $credentialString, string $signature, string $chunk): string
    {
        static $emptyHash;
        $emptyHash = $emptyHash ?? hash('sha256', '');

        return implode("\n", [
            self::ALGORITHM_CHUNK,
            gmdate('Ymd\THis\Z', $now->getTimestamp()),
            $credentialString,
            $signature,
            $emptyHash,
            hash('sha256', $chunk),
        ]);
    }

    private function buildSigningKey(Credentials $credentials, array $credentialScope): string
    {
        $signingKey = 'AWS4' . $credentials->getSecretKey();
        foreach ($credentialScope as $scopePart) {
            $signingKey = hash_hmac('sha256', $scopePart, $signingKey, true);
        }

        return $signingKey;
    }

    private function buildSignature(string $stringToSign, string $signingKey): string
    {
        return hash_hmac('sha256', $stringToSign, $signingKey);
    }
}
