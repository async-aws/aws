<?php

namespace AsyncAws\S3\Signers;

use AsyncAws\Core\Credentials\Credentials;
use AsyncAws\Core\Signers\Request;
use AsyncAws\Core\Signers\SignerV4;

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
            $request->setHeader('content-md5', $this->getMd5Body($request));
        }

        parent::sign($request, $credentials);
    }
}
