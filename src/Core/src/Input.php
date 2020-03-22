<?php

namespace AsyncAws\Core;

/**
 * Representation of a AWS Request.
 *
 * @author Jérémy Derussé <jeremy@derusse.com>
 *
 * @internal
 */
interface Input
{
    public function request(): Request;
}
