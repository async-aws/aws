<?php

namespace AsyncAws\Core\Signers;

use AsyncAws\Core\Credentials\Credentials;

/**
 * Version4 of signer dedicated for service S3.
 *
 * @author Jérémy Derussé <jeremy@derusse.com>
 */
class SignerV4ForS3 extends SignerV4
{
    public function sign(Request $request, ?Credentials $credentials): void
    {
        if (!$request->hasHeader('x-amz-content-sha256')) {
            $request->setHeader('x-amz-content-sha256', $this->getCanonicalizedBody($request));
        }
        if (!$request->hasHeader('content-md5')) {
            $request->setHeader('content-md5', base64_encode(hash('md5', $request->getBody(), true)));
        }

        parent::sign($request, $credentials);
    }
}
