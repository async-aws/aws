<?php

declare(strict_types=1);

namespace AsyncAws\Core;

/**
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 * @author Jérémy Derussé <jeremy@derusse.com>
 */
interface ApiInterface
{
    public function getConfiguration(): Configuration;

    /**
     * @param string[]|string[][]                    $headers headers names provided as keys or as part of values
     * @param string|resource|callable|iterable|null $body
     */
    public function request(string $method, $body = '', $headers = [], ?string $endpoint = null): Result;
}
