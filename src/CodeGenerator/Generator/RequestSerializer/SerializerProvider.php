<?php

declare(strict_types=1);

namespace AsyncAws\CodeGenerator\Generator\RequestSerializer;

use AsyncAws\CodeGenerator\Definition\ServiceDefinition;

/**
 * Provide the correct serializer according to the definition.
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 *
 * @internal
 */
class SerializerProvider
{
    public function get(ServiceDefinition $definition): Serializer
    {
        switch ($definition->getProtocol()) {
            case 'rest-xml':
                return new RestXmlSerializer();
            case 'rest-json':
                return new RestJsonSerializer();
            case 'query':
                return new QuerySerializer();
            case 'json':
            default:
                throw new \LogicException(sprintf('Serializer for "%s" is not implemented yet', $definition->getProtocol()));
        }
    }
}
