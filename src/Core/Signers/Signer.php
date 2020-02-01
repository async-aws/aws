<?php

namespace AsyncAws\Core\Signers;

use AsyncAws\Core\Credentials\Credentials;

/**
 * Interface for signing a request
 *
 * @author Jérémy Derussé <jeremy@derusse.com>
 */
interface Signer
{
    public function sign(Request $request, ?Credentials $credentials): void;
}
