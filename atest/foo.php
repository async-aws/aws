<?php

declare(strict_types=1);

/**
 * @see http://docs.amazonwebservices.com/AmazonS3/latest/API/RESTObjectPUT.html
 * @param array{
 *     foo: string,
 *     bar: int
 * } $data
 */
function foo(array $data): void {

}

foo(['bar'=>5]);
