<?php

declare(strict_types=1);

namespace AsyncAws\Core;

/**
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 * @author Jérémy Derussé <jeremy@derusse.com>
 */
interface ApiInterface
{
    /**
     * Get the configuration for this client.
     */
    public function getConfiguration(): Configuration;
}
