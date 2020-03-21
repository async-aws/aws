<?php

declare(strict_types=1);

namespace AsyncAws\Core\Credentials;

use AsyncAws\Core\Configuration;

/**
 * Provides Credentials from Configuration data.
 *
 * @author Jérémy Derussé <jeremy@derusse.com>
 */
final class ConfigurationProvider implements CredentialProvider
{
    public function getCredentials(Configuration $configuration): ?Credentials
    {
        if (
            $configuration->has(Configuration::OPTION_ACCESS_KEY_ID)
            && $configuration->has(Configuration::OPTION_SECRET_ACCESS_KEY)
        ) {
            /** @psalm-suppress PossiblyNullArgument */
            return new Credentials(
                $configuration->get(Configuration::OPTION_ACCESS_KEY_ID),
                $configuration->get(Configuration::OPTION_SECRET_ACCESS_KEY),
                $configuration->get(Configuration::OPTION_SESSION_TOKEN)
            );
        }

        return null;
    }
}
