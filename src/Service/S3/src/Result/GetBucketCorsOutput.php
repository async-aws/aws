<?php

namespace AsyncAws\S3\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\S3\ValueObject\CORSRule;

class GetBucketCorsOutput extends Result
{
    /**
     * A set of origins and methods (cross-origin access that you want to allow). You can add up to 100 rules to the
     * configuration.
     *
     * @var CORSRule[]
     */
    private $corsRules;

    /**
     * @return CORSRule[]
     */
    public function getCorsRules(): array
    {
        $this->initialize();

        return $this->corsRules;
    }

    protected function populateResult(Response $response): void
    {
        $data = new \SimpleXMLElement($response->getContent());
        $this->corsRules = (0 === ($v = $data->CORSRule)->count()) ? [] : $this->populateResultCORSRules($v);
    }

    /**
     * @return string[]
     */
    private function populateResultAllowedHeaders(\SimpleXMLElement $xml): array
    {
        $items = [];
        foreach ($xml as $item) {
            $items[] = (string) $item;
        }

        return $items;
    }

    /**
     * @return string[]
     */
    private function populateResultAllowedMethods(\SimpleXMLElement $xml): array
    {
        $items = [];
        foreach ($xml as $item) {
            $items[] = (string) $item;
        }

        return $items;
    }

    /**
     * @return string[]
     */
    private function populateResultAllowedOrigins(\SimpleXMLElement $xml): array
    {
        $items = [];
        foreach ($xml as $item) {
            $items[] = (string) $item;
        }

        return $items;
    }

    private function populateResultCORSRule(\SimpleXMLElement $xml): CORSRule
    {
        return new CORSRule([
            'ID' => (null !== $v = $xml->ID[0]) ? (string) $v : null,
            'AllowedHeaders' => (0 === ($v = $xml->AllowedHeader)->count()) ? null : $this->populateResultAllowedHeaders($v),
            'AllowedMethods' => $this->populateResultAllowedMethods($xml->AllowedMethod),
            'AllowedOrigins' => $this->populateResultAllowedOrigins($xml->AllowedOrigin),
            'ExposeHeaders' => (0 === ($v = $xml->ExposeHeader)->count()) ? null : $this->populateResultExposeHeaders($v),
            'MaxAgeSeconds' => (null !== $v = $xml->MaxAgeSeconds[0]) ? (int) (string) $v : null,
        ]);
    }

    /**
     * @return CORSRule[]
     */
    private function populateResultCORSRules(\SimpleXMLElement $xml): array
    {
        $items = [];
        foreach ($xml as $item) {
            $items[] = $this->populateResultCORSRule($item);
        }

        return $items;
    }

    /**
     * @return string[]
     */
    private function populateResultExposeHeaders(\SimpleXMLElement $xml): array
    {
        $items = [];
        foreach ($xml as $item) {
            $items[] = (string) $item;
        }

        return $items;
    }
}
