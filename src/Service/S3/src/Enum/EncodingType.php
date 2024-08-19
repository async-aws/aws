<?php

namespace AsyncAws\S3\Enum;

/**
 * Encoding type used by Amazon S3 to encode the object keys [^1] in the response. Responses are encoded only in UTF-8.
 * An object key can contain any Unicode character. However, the XML 1.0 parser can't parse certain characters, such as
 * characters with an ASCII value from 0 to 10. For characters that aren't supported in XML 1.0, you can add this
 * parameter to request that Amazon S3 encode the keys in the response. For more information about characters to avoid
 * in object key names, see Object key naming guidelines [^2].
 *
 * > When using the URL encoding type, non-ASCII characters that are used in an object's key name will be
 * > percent-encoded according to UTF-8 code values. For example, the object `test_file(3).png` will appear as
 * > `test_file%283%29.png`.
 *
 * [^1]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/object-keys.html
 * [^2]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/object-keys.html#object-key-guidelines
 */
final class EncodingType
{
    public const URL = 'url';

    public static function exists(string $value): bool
    {
        return isset([
            self::URL => true,
        ][$value]);
    }
}
