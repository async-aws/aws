<?php

namespace AsyncAws\Translate\Enum;

/**
 * You can optionally specify the desired level of formality for translations to supported target languages. The
 * formality setting controls the level of formal language usage (also known as register) in the translation output. You
 * can set the value to informal or formal. If you don't specify a value for formality, or if the target language
 * doesn't support formality, the translation will ignore the formality setting.
 * If you specify multiple target languages for the job, translate ignores the formality setting for any unsupported
 * target language.
 * For a list of target languages that support formality, see Setting Formality in the Amazon Translate Developer Guide.
 *
 * @see https://en.wikipedia.org/wiki/Register_(sociolinguistics)
 * @see https://docs.aws.amazon.com/translate/latest/dg/customizing-translations-formality.html
 */
final class Formality
{
    public const FORMAL = 'FORMAL';
    public const INFORMAL = 'INFORMAL';

    public static function exists(string $value): bool
    {
        return isset([
            self::FORMAL => true,
            self::INFORMAL => true,
        ][$value]);
    }
}
