<?php

namespace AsyncAws\Core\Signers;

use AsyncAws\Core\Credentials\Credentials;

/**
 * Version4 of signer dedicated for service S3
 *
 * @author Jérémy Derussé <jeremy@derusse.com>
 */
class SignerV4ForS3 extends SignerV4
{
    public function sign(Request $request, ?Credentials $credentials): void
    {
        $request->setHeader('x-amz-content-sha256', $this->getCanonicalizedBody($request));

        parent::sign($request, $credentials);
    }
}
