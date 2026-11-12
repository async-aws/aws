<?php

namespace AsyncAws\CognitoIdentityProvider\Enum;

/**
 * Controls whether revoking a session also revokes the sessions that are linked to it. `NONE` revokes only the targeted
 * session. `LINKED` also revokes the directly linked session or sessions, interpreted relative to the type of the
 * targeted session: revoking a token session also revokes the parent SSO session it came through, and revoking an SSO
 * session also revokes the token sessions that came through it. The set of linked sessions is eventually consistent, so
 * associated sessions are typically revoked within a few seconds.
 */
final class RevokeAssociatedSessionsType
{
    public const LINKED = 'LINKED';
    public const NONE = 'NONE';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::LINKED => true,
            self::NONE => true,
        ][$value]);
    }
}
