<?php

namespace AsyncAws\Core\AwsError;

use AsyncAws\Core\Exception\RuntimeException;
use AsyncAws\Core\Exception\UnexpectedValue;
use AsyncAws\Core\Exception\UnparsableResponse;

final class XmlAwsErrorFactory implements AwsErrorFactoryInterface
{
    use AwsErrorFactoryFromResponseTrait;

    public function createFromContent(string $content, array $headers): AwsError
    {
        try {
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

        // EC2 error envelope: <Response><Errors><Error><Code/><Message/></Error></Errors><RequestID/></Response>
        if (0 < $xml->Errors->count() && 0 < $xml->Errors->Error->count()) {
            return new AwsError(
                $xml->Errors->Error->Code->__toString(),
                $xml->Errors->Error->Message->__toString(),
                null,
                null
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
}
