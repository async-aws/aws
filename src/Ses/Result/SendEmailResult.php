<?php

declare(strict_types=1);

namespace WorkingTitle\Ses\Result;

use Symfony\Contracts\HttpClient\ResponseInterface;

class SendEmailResult
{
    public function __construct(ResponseInterface $response)
    {
        $result = new \SimpleXMLElement($response->getContent());
        if (200 !== $response->getStatusCode()) {
            throw new HttpTransportException(sprintf('Unable to send an email: %s (code %s).', $result->Error->Message, $result->Error->Code), $response);
        }

        $property = $payload['Action'].'Result';

        $sentMessage->setMessageId($result->{$property}->MessageId);

        return $response;
    }
}
