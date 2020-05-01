<?php

namespace AsyncAws\Core\Exception\Http;

use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * @author Nicolas Grekas <p@tchwork.com>
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 * @author Jérémy Derussé <jeremy@derusse.com>
 *
 * @internal
 */
trait HttpExceptionTrait
{
    /**
     * @var ResponseInterface
     */
    private $response;

    /**
     * @var ?string
     */
    private $awsCode;

    /**
     * @var ?string
     */
    private $awsType;

    /**
     * @var ?string
     */
    private $awsMessage;

    /**
     * @var ?string
     */
    private $awsDetail;

    public function __construct(ResponseInterface $response)
    {
        $this->response = $response;
        /** @var int $code */
        $code = $response->getInfo('http_code');
        /** @var string $url */
        $url = $response->getInfo('url');
        $content = $response->getContent(false);
        $message = sprintf('HTTP %d returned for "%s".', $code, $url);

        $this->awsType = $response->getHeaders(false)['x-amzn-errortype'][0] ?? null;

        // Try json_decode it first, fallback to XML
        if ($body = json_decode($content, true)) {
            $this->parseJson($body);
        } else {
            try {
                set_error_handler(static function ($errno, $errstr, $errfile, $errline) {
                    throw new \RuntimeException($errstr, $errno);
                });

                try {
                    $xml = new \SimpleXMLElement($content);
                } finally {
                    restore_error_handler();
                }
                $this->parseXml($xml);
            } catch (\Throwable $e) {
                // Not XML ¯\_(ツ)_/¯
            }
        }

        $message .= <<<TEXT


Code:    $this->awsCode
Message: $this->awsMessage
Type:    $this->awsType
Detail:  $this->awsDetail

TEXT;

        parent::__construct($message, $code);
    }

    public function getResponse(): ResponseInterface
    {
        return $this->response;
    }

    public function getAwsCode(): ?string
    {
        return $this->awsCode;
    }

    public function getAwsType(): ?string
    {
        return $this->awsType;
    }

    public function getAwsMessage(): ?string
    {
        return $this->awsMessage;
    }

    public function getAwsDetail(): ?string
    {
        return $this->awsDetail;
    }

    private function parseXml(\SimpleXMLElement $xml): void
    {
        if (0 < $xml->Error->count()) {
            $this->awsType = $xml->Error->Type->__toString();
            $this->awsCode = $xml->Error->Code->__toString();
            $this->awsMessage = $xml->Error->Message->__toString();
            $this->awsDetail = $xml->Error->Detail->__toString();
        } elseif (1 === $xml->Code->count() && 1 === $xml->Message->count()) {
            $this->awsType = $this->awsDetail = '';
            $this->awsCode = $xml->Code->__toString();
            $this->awsMessage = $xml->Message->__toString();
        }
    }

    private function parseJson($body): void
    {
        if (isset($body['message'])) {
            $this->awsMessage = $body['message'];
        } elseif (isset($body['Message'])) {
            $this->awsMessage = $body['Message'];
        }

        if (isset($body['Type'])) {
            $this->awsType = $body['Type'];
        } elseif (isset($body['__type'])) {
            $parts = explode('#', $body['__type'], 2);
            $this->awsCode = $parts[1] ?? $parts[0];
            $this->awsType = $body['__type'];
        }
    }
}
