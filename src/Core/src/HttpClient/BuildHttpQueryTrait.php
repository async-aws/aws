<?php

namespace AsyncAws\Core\HttpClient;

/**
 * @author Jérémy Derussé <jeremy@derusse.com>
 */
trait BuildHttpQueryTrait
{
    /**
     * Build a query string from an array of key value pairs
     * This function does not modify the provided keys when an array is encountered (like `http_build_query()` would).
     *
     * @param array<string, string|string[]> $query
     */
    private function buildHttpQuery(array $query): string
    {
        if (empty($query)) {
            return '';
        }

        $qs = '';
        foreach ($query as $k => $v) {
            $k = rawurlencode($k);
            if (!\is_array($v)) {
                $qs .= $k . '=' . rawurlencode($v) . '&';
            } else {
                foreach ($v as $vv) {
                    $qs .= $k . '=' . rawurlencode($vv) . '&';
                }
            }
        }

        return $qs ? substr($qs, 0, -1) : '';
    }
}
