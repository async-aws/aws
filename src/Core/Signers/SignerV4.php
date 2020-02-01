<?php

namespace AsyncAws\Core\Signers;

use AsyncAws\Core\Credentials\Credentials;
use AsyncAws\Core\Exception\InvalidArgument;

/**
 * Version4 of signer
 *
 * @author Jérémy Derussé <jeremy@derusse.com>
 */
class SignerV4 implements Signer
{
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
    private const ALGORITHM = 'AWS4-HMAC-SHA256';

    private $service;
    private $region;

    public function __construct(string $service, string $region)
    {
        $this->service = $service;
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

        $canonicalHeaders = $this->getCanonicalizedHeaders($request);

        $canonicalRequest = implode(
            "\n",
            [
                $request->getMethod(),
                $this->getCanonicalizedPath($parsedUrl),
                $this->getCanonicalizedQuery($parsedUrl),
                \implode("\n", array_values($canonicalHeaders)),
                '',
                implode(';', \array_keys($canonicalHeaders)),
                $this->getCanonicalizedBody($request),
            ]
        );

        $credentialScope = [substr($amzDate, 0, 8), $this->region, $this->service, 'aws4_request'];

        $signingKey = 'AWS4' . $credentials->getSecretKey();
        foreach ($credentialScope as $scopePart) {
            $signingKey = hash_hmac('sha256', $scopePart, $signingKey, true);
        }

        $stringToSign = implode(
            "\n",
            [
                self::ALGORITHM,
                $amzDate,
                implode('/', $credentialScope),
                hash('sha256', $canonicalRequest),
            ]
        );

        $authorizationHeader = sprintf(
            '%s Credential=%s/%s, SignedHeaders=%s, Signature=%s',
            self::ALGORITHM,
            $credentials->getAccessKeyId(),
            implode('/', $credentialScope),
            implode(';', \array_keys($canonicalHeaders)),
            hash_hmac('sha256', $stringToSign, $signingKey)
        );

        $request->setHeader('authorization', $authorizationHeader);
    }

    protected function getCanonicalizedBody(Request $request): string
    {
        return hash('sha256', $request->getBody());
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
        foreach ($request->getHeaders() as $key => $value) {
            $key = strtolower($key);
            if (isset(self::BLACKLIST_HEADERS[$key])) {
                continue;
            }

            $canonicalHeaders[$key] = "$key:$value";
        }
        ksort($canonicalHeaders);

        return $canonicalHeaders;
    }
}
