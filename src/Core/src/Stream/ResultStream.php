<?php

declare(strict_types=1);

namespace AsyncAws\Core\Stream;

/**
 * Stream for a Result.
 *
 * Most implementations buffer responses into temporary storage so content can be
 * replayed. Some operations can opt into non-buffered response streams; those
 * streams are one-shot and non-rewindable.
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
