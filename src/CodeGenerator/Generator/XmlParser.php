<?php

declare(strict_types=1);

namespace AsyncAws\CodeGenerator\Generator;

use AsyncAws\CodeGenerator\Definition\ServiceDefinition;
use AsyncAws\CodeGenerator\Definition\Shape;

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
            $input = $currentInput . '[' . var_export($memberData['locationName'], true) . ']';
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
                return $this->parseXmlResponseList($shapeName, $input);
            case 'structure':
                return $this->parseXmlResponseStructure($shapeName, $input);
            case 'map':
                return $this->parseXmlResponseMap($shapeName, $input);
            case 'string':
            case 'boolean':
            case 'integer':
            case 'long':
            case 'timestamp':
            case 'blob':
                return $this->parseXmlResponseScalar($shapeName, $input);
            default:
                throw new \RuntimeException(sprintf('Type %s is not yet implemented', $shape['type']));
        }
    }

    private function parseXmlResponseStructure(string $shapeName, string $input): string
    {
        $shape = $this->definition->getShape($shapeName);

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
            'CLASS_NAME' => GeneratorHelper::safeClassName($shapeName),
            'PROPERTIES' => implode("\n", $properties),
        ]);
    }

    private function parseXmlResponseScalar(string $shapeName, string $input): string
    {
        $shape = $this->definition->getShape($shapeName);

        return strtr('$this->xmlValueOrNull(PROPERTY_ACCESSOR, PROPERTY_TYPE)', [
            'PROPERTY_ACCESSOR' => $input,
            'PROPERTY_TYPE' => \var_export(GeneratorHelper::toPhpType($shape['type']), true),
        ]);
    }

    private function parseXmlResponseList(string $shapeName, string $input): string
    {
        $shape = $this->definition->getShape($shapeName);

        return strtr('(function(\SimpleXMLElement $xml): array {
            $items = [];
            foreach ($xml as $item) {
               $items[] = LIST_ACCESSOR;
            }

            return $items;
        })(INPUT)', [
            'LIST_ACCESSOR' => $this->parseXmlResponse('$item', null, ['shape' => $shape['member']['shape']]),
            'INPUT' => $input,
        ]);
    }

    private function parseXmlResponseMap(string $shapeName, string $input): string
    {
        $shape = $this->definition->getShape($shapeName);
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
