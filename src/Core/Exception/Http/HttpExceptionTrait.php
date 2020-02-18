<?php

namespace AsyncAws\Core\Exception\Http;

use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * @author Nicolas Grekas <p@tchwork.com>
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
        $code = $response->getInfo('http_code');
        $url = $response->getInfo('url');
        $message = sprintf('HTTP %d returned for "%s".', $code, $url);

        $httpCodeFound = false;
        $isJson = false;
        foreach (array_reverse($response->getInfo('response_headers')) as $h) {
            if (0 === strpos($h, 'HTTP/')) {
                if ($httpCodeFound) {
                    break;
                }

                $message = sprintf('%s returned for "%s".', $h, $url);
                $httpCodeFound = true;
            }

            if (0 === stripos($h, 'content-type:')) {
                if (preg_match('/\bjson\b/i', $h)) {
                    $isJson = true;
                }

                if ($httpCodeFound) {
                    break;
                }
            }
        }

        // Try to guess a better error message using common API error formats
        // The MIME type isn't explicitly checked because some formats inherit from others
        // Ex: JSON:API follows RFC 7807 semantics, Hydra can be used in any JSON-LD-compatible format
        if ($isJson && $body = json_decode($response->getContent(false), true)) {
            if (isset($body['hydra:title']) || isset($body['hydra:description'])) {
                // see http://www.hydra-cg.com/spec/latest/core/#description-of-http-status-codes-and-errors
                $separator = isset($body['hydra:title'], $body['hydra:description']) ? "\n\n" : '';
                $message = ($body['hydra:title'] ?? '') . $separator . ($body['hydra:description'] ?? '');
            } elseif (isset($body['title']) || isset($body['detail'])) {
                // see RFC 7807 and https://jsonapi.org/format/#error-objects
                $separator = isset($body['title'], $body['detail']) ? "\n\n" : '';
                $message = ($body['title'] ?? '') . $separator . ($body['detail'] ?? '');
            }
        } else {
            try {
                $body = $response->getContent(false);
                $xml = new \SimpleXMLElement($body);
                $message .= $this->parseXml($xml);
            } catch (\Throwable $e) {
                // Not XML ¯\_(ツ)_/¯
            }
        }

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

    private function parseXml(\SimpleXMLElement $xml): string
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
        } else {
            return '';
        }

        return <<<TEXT


Code:    $this->awsCode
Message: $this->awsMessage
Type:    $this->awsType
Detail:  $this->awsDetail

TEXT;
    }
}
