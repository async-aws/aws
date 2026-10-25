<?php

declare(strict_types=1);

namespace AsyncAws\Core\Stream;

/**
 * Stream for a Result.
 *
 * Its content can be read more than once, unless it is a `ReadOnceResultStream`, as
 * returned for large bodies by a client configured with `responseBufferMaxSize`.
 */
interface ResultStream
{
    /**
     * Download the response in chunks.
     *
     *     $fileHandler = fopen('/output.pdf', 'w');
     *     foreach ($result->getBody()->getChunks() as $chunk) {
     *       fwrite($fileHandler, $chunk);
     *     }
     *
     * @return iterable<string>
     */
    public function getChunks(): iterable;

    /**
     * Download content and return a string.
     */
    public function getContentAsString(): string;

    /**
     * Return content as a resource.
     *
     * @return resource
     */
    public function getContentAsResource();
}
