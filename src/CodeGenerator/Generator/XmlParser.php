<?php

declare(strict_types=1);

namespace AsyncAws\CodeGenerator\Generator;

use AsyncAws\CodeGenerator\Definition\ListShape;
use AsyncAws\CodeGenerator\Definition\ServiceDefinition;
use AsyncAws\CodeGenerator\Definition\Shape;
use AsyncAws\CodeGenerator\Definition\StructureShape;

/**
 * @author Jérémy Derussé <jeremy@derusse.com>
 *
 * @internal
 */
class XmlParser
{
    /**
     * @var ServiceDefinition
     */
    private $definition;

    public function parseXmlResponseRoot(ServiceDefinition $definition, Shape $shape): string
    {
        $this->definition = $definition;
        $properties = [];

        foreach ($shape['members'] as $memberName => $memberData) {
            if (\in_array(($memberData['location'] ?? null), ['header', 'headers'])) {
                continue;
            }

            $properties[] = strtr('$this->PROPERTY_NAME = PROPERTY_ACCESSOR;', [
                'PROPERTY_NAME' => $memberName,
                'PROPERTY_ACCESSOR' => $this->parseXmlResponse('$data', $memberName, $memberData),
            ]);
        }

        return implode("\n", $properties);
    }

    private function parseXmlResponse(string $currentInput, ?string $memberName, array $memberData)
    {
        if (!empty($memberData['xmlAttribute'])) {
            list($ns, $name) = explode(':', $memberData['locationName'] . ':');
            if (empty($name)) {
                // No namespace
                $name = $ns;
                $input = $currentInput . '[' . var_export($name, true) . '][0] ?? null';
            } else {
                $input = $currentInput . '->attributes(' . var_export($ns, true) . ', true)[' . var_export($name, true) . '][0] ?? null';
            }
        } elseif (isset($memberData['locationName'])) {
            $input = $currentInput . '->' . $memberData['locationName'];
        } elseif ($memberName) {
            $input = $currentInput . '->' . $memberName;
        } else {
            $input = $currentInput;
        }

        $shapeName = $memberData['shape'];
        $shape = $this->definition->getShape($shapeName);
        switch ($shape['type']) {
            case 'list':
                /** @var ListShape $shape */
                return $this->parseXmlResponseList($shape, $input);
            case 'structure':
                /** @var StructureShape $shape */
                return $this->parseXmlResponseStructure($shape, $input);
            case 'map':
                return $this->parseXmlResponseMap($shape, $input);
            case 'string':
            case 'boolean':
            case 'integer':
            case 'long':
            case 'timestamp':
            case 'blob':
                return $this->parseXmlResponseScalar($shape, $input);
            default:
                throw new \RuntimeException(sprintf('Type %s is not yet implemented', $shape['type']));
        }
    }

    private function parseXmlResponseStructure(StructureShape $shape, string $input): string
    {
        $properties = [];
        foreach ($shape['members'] as $memberName => $memberData) {
            $properties[] = strtr('PROPERTY_NAME => PROPERTY_ACCESSOR,', [
                'PROPERTY_NAME' => var_export($memberName, true),
                'PROPERTY_ACCESSOR' => $this->parseXmlResponse($input, $memberName, $memberData),
            ]);
        }

        return strtr('new CLASS_NAME([
            PROPERTIES
        ])', [
            'CLASS_NAME' => GeneratorHelper::safeClassName($shape->getName()),
            'PROPERTIES' => implode("\n", $properties),
        ]);
    }

    private function parseXmlResponseScalar(Shape $shape, string $input): string
    {
        return strtr('$this->xmlValueOrNull(PROPERTY_ACCESSOR, PROPERTY_TYPE)', [
            'PROPERTY_ACCESSOR' => $input,
            'PROPERTY_TYPE' => \var_export(GeneratorHelper::toPhpType($shape['type']), true),
        ]);
    }

    private function parseXmlResponseList(ListShape $shape, string $input): string
    {
        return strtr('(function(\SimpleXMLElement $xml): array {
            if (0 === $xml->count() || 0 === $xml->LOCATION_NAME->count()) {
                return [];
            }
            $items = [];
            foreach ($xml->LOCATION_NAME as $item) {
               $items[] = LIST_ACCESSOR;
            }

            return $items;
        })(INPUT)', [
            'LIST_ACCESSOR' => $this->parseXmlResponse('$item', null, ['shape' => $shape->getMember()['shape']]),
            'INPUT' => $input,
            'LOCATION_NAME' => $shape->getMember()['locationName'] ?? $shape->getMember()['shape'],
        ]);
    }

    private function parseXmlResponseMap(Shape $shape, string $input): string
    {
        if (!isset($shape['key']['locationName'])) {
            throw new \RuntimeException('This is not implemented yet');
        }

        return strtr('(function(\SimpleXMLElement $xml): array {
            $items = [];
            foreach ($xml as $item) {
               $items[$item->MAP_KEY->__toString()] = MAP_ACCESSOR;
            }

            return $items;
        })(INPUT)', [
            'MAP_KEY' => $shape['key']['locationName'],
            'MAP_ACCESSOR' => $this->parseXmlResponse('$item', null, $shape['value']),
            'INPUT' => $input,
        ]);
    }
}
