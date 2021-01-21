<?php

namespace AsyncAws\Core\AwsError;

use AsyncAws\Core\Exception\RuntimeException;
use AsyncAws\Core\Exception\UnexpectedValue;
use AsyncAws\Core\Exception\UnparsableResponse;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * @internal
 */
class AwsErrorFactory
{
    public static function createFromResponse(ResponseInterface $response): AwsError
    {
        $content = $response->getContent(false);
        $headers = $response->getHeaders(false);

        return self::createFromContent($content, $headers);
    }

    public static function createFromContent(string $content, array $headers): AwsError
    {
        try {
            // Try json_decode it first, fallback to XML
            if ($body = json_decode($content, true)) {
                return self::parseJson($body, $headers);
            }

            /** @phpstan-ignore-next-line */
            set_error_handler(static function ($errno, $errstr) {
                throw new RuntimeException($errstr, $errno);
            });

            try {
                $xml = new \SimpleXMLElement($content);
            } finally {
                restore_error_handler();
            }

            return self::parseXml($xml);
        } catch (\Throwable $e) {
            throw new UnparsableResponse('Failed to parse AWS error: ' . $content, 0, $e);
        }
    }

    private static function parseXml(\SimpleXMLElement $xml): AwsError
    {
        if (0 < $xml->Error->count()) {
            return new AwsError(
                $xml->Error->Code->__toString(),
                $xml->Error->Message->__toString(),
                $xml->Error->Type->__toString(),
                $xml->Error->Detail->__toString()
            );
        }

        if (1 === $xml->Code->count() && 1 === $xml->Message->count()) {
            return new AwsError(
                $xml->Code->__toString(),
                $xml->Message->__toString(),
                null,
                null
            );
        }

        throw new UnexpectedValue('XML does not contains AWS Error');
    }

    private static function parseJson(array $body, array $headers): AwsError
    {
        $code = null;

        $message = $body['message'] ?? $body['Message'] ?? null;
        if (isset($headers['x-amzn-errortype'][0])) {
            $code = explode(':', $headers['x-amzn-errortype'][0], 2)[0];
        }

        $type = $body['type'] ?? $body['Type'] ?? null;
        if (isset($body['__type'])) {
            $parts = explode('#', $body['__type'], 2);
            $code = $parts[1] ?? $parts[0];
            $type = $parts[0];
        }

        if (null !== $code || null !== $message) {
            return new AwsError($code, $message, $type, null);
        }

        throw new UnexpectedValue('JSON does not contains AWS Error');
    }
}
