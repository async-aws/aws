<?php

namespace AsyncAws\S3\Signer;

use AsyncAws\Core\Credentials\Credentials;
use AsyncAws\Core\Signer\Request;
use AsyncAws\Core\Signer\SignerV4;
use AsyncAws\Core\Stream\StringStream;

/**
 * Version4 of signer dedicated for service S3.
 *
 * @author Jérémy Derussé <jeremy@derusse.com>
 */
class SignerV4ForS3 extends SignerV4
{
    public function sign(Request $request, ?Credentials $credentials): void
    {
        if (!$request->hasHeader('content-md5') && ($body = $request->getBody()) instanceof StringStream) {
            $request->setHeader('content-md5', base64_encode(hash('md5', $body->stringify(), true)));
        }

        parent::sign($request, $credentials);
    }
}
