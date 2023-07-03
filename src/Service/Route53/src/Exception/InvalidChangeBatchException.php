<?php

namespace AsyncAws\Route53\Exception;

use AsyncAws\Core\Exception\Http\ClientException;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * This exception contains a list of messages that might contain one or more error messages. Each error message
 * indicates one error in the change batch.
 */
final class InvalidChangeBatchException extends ClientException
{
    /**
     * @var string[]
     */
    private $messages;

    /**
     * @return string[]
     */
    public function getMessages(): array
    {
        return $this->messages;
    }

    protected function populateResult(ResponseInterface $response): void
    {
        $data = new \SimpleXMLElement($response->getContent(false));
        if (0 < $data->Error->count()) {
            $data = $data->Error;
        }
        $this->messages = !$data->messages ? [] : $this->populateResultErrorMessages($data->messages);
        if (null !== $v = (($v = $data->message) ? (string) $v : null)) {
            $this->message = $v;
        }
    }

    /**
     * @return string[]
     */
    private function populateResultErrorMessages(\SimpleXMLElement $xml): array
    {
        $items = [];
        foreach ($xml->Message as $item) {
            $a = ($v = $item) ? (string) $v : null;
            if (null !== $a) {
                $items[] = $a;
            }
        }

        return $items;
    }
}
