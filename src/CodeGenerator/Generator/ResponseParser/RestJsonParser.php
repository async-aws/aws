<?php

declare(strict_types=1);

namespace AsyncAws\CodeGenerator\Generator\ResponseParser;

use AsyncAws\CodeGenerator\Definition\ListShape;
use AsyncAws\CodeGenerator\Definition\MapShape;
use AsyncAws\CodeGenerator\Definition\Member;
use AsyncAws\CodeGenerator\Definition\Shape;
use AsyncAws\CodeGenerator\Definition\StructureMember;
use AsyncAws\CodeGenerator\Definition\StructureShape;
use AsyncAws\CodeGenerator\Generator\Naming\NamespaceRegistry;

/**
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 *
 * @internal
 */
class RestJsonParser implements Parser
{
    /**
     * @var NamespaceRegistry
     */
    private $namespaceRegistry;

    public function __construct(NamespaceRegistry $namespaceRegistry)
    {
        $this->namespaceRegistry = $namespaceRegistry;
    }

    public function generate(StructureShape $shape): string
    {
        $properties = [];

        foreach ($shape->getMembers() as $member) {
            if (\in_array($member->getLocation(), ['header', 'headers'])) {
                continue;
            }

            $properties[] = strtr('$this->PROPERTY_NAME = PROPERTY_ACCESSOR;', [
                'PROPERTY_NAME' => $member->getName(),
                'PROPERTY_ACCESSOR' => $this->parseElement(sprintf('$data[\'%s\']', $this->getInputAccessorName($member)), $member->getShape()),
            ]);
        }

        if (empty($properties)) {
            return '';
        }

        $body = '$data = json_decode($response->getContent(false), true);' . "\n";
        if (null !== $wrapper = $shape->getResultWrapper()) {
            $body .= strtr('$data = $data[WRAPPER];' . "\n", ['WRAPPER' => var_export($wrapper, true)]);
        }
        $body .= "\n" . implode("\n", $properties);

        return $body;
    }

    private function getInputAccessorName(Member $member)
    {
        if ($member instanceof StructureMember) {
            $shape = $member->getShape();
            if ($shape instanceof ListShape && $shape->isFlattened()) {
                return $member->getLocationName() ?? $shape->getMember()->getLocationName() ?? $member->getName();
            }

            return $member->getLocationName() ?? $member->getName();
        }

        if (null === $member->getLocationName()) {
            throw new \RuntimeException('This should not happen');
        }

        return $member->getLocationName();
    }

    private function parseElement(string $input, Shape $shape)
    {
        switch (true) {
            case $shape instanceof ListShape:
                return $this->parseResponseList($shape, $input);
            case $shape instanceof StructureShape:
                return $this->parseResponseStructure($shape, $input);
            case $shape instanceof MapShape:
                return $this->parseResponseMap($shape, $input);
        }

        switch ($shape->getType()) {
            case 'string':
            case 'long':
                return $this->parseResponseString($input);
            case 'integer':
                return $this->parseResponseInteger($input);
            case 'float':
            case 'double':
                return $this->parseResponseFloat($input);
            case 'boolean':
                return $this->parseResponseBool($input);
            case 'blob':
                return $this->parseResponseBlob($input);
            case 'timestamp':
                return $this->parseResponseTimestamp($shape, $input);
        }

        throw new \RuntimeException(sprintf('Type %s is not yet implemented', $shape->getType()));
    }

    private function parseResponseStructure(StructureShape $shape, string $input): string
    {
        $properties = [];
        foreach ($shape->getMembers() as $member) {
            $properties[] = strtr('PROPERTY_NAME => PROPERTY_ACCESSOR,', [
                'PROPERTY_NAME' => var_export($member->getName(), true),
                'PROPERTY_ACCESSOR' => $this->parseElement(sprintf('%s[\'%s\']', $input, $this->getInputAccessorName($member)), $member->getShape()),
            ]);
        }

        return strtr('new CLASS_NAME([
            PROPERTIES
        ])', [
            'CLASS_NAME' => $this->namespaceRegistry->getResult($shape)->getName(),
            'PROPERTIES' => implode("\n", $properties),
        ]);
    }

    private function parseResponseString(string $input): string
    {
        return strtr('($v = INPUT) ? (string) $v : null', ['INPUT' => $input]);
    }

    private function parseResponseInteger(string $input): string
    {
        return strtr('($v = INPUT) ? (int) (string) $v : null', ['INPUT' => $input]);
    }

    private function parseResponseFloat(string $input): string
    {
        return strtr('($v = INPUT) ? (float) (string) $v : null', ['INPUT' => $input]);
    }

    private function parseResponseBool(string $input): string
    {
        return strtr('($v = INPUT) ? (string) $v === \'true\' : null', ['INPUT' => $input]);
    }

    private function parseResponseBlob(string $input): string
    {
        return strtr('($v = INPUT) ? base64_decode((string) $v) : null', ['INPUT' => $input]);
    }

    private function parseResponseTimestamp(Shape $shape, string $input): string
    {
        if ('unixTimestamp' === $shape->get('timestampFormat')) {
            return strtr('($v = INPUT) ? \DateTimeImmutable::setTimestamp((string) $v) : null', ['INPUT' => $input]);
        }

        return strtr('($v = INPUT) ? new \DateTimeImmutable((string) $v) : null', ['INPUT' => $input]);
    }

    private function parseResponseList(ListShape $shape, string $input): string
    {
        $shapeMember = $shape->getMember();
        if ($shapeMember->getShape() instanceof StructureShape) {
            $body = '(function(array $json): array {
            $items = [];
            foreach (INPUT_PROPERTY as $item) {
               $items[] = LIST_ACCESSOR;
            }

            return $items;
        })(INPUT)';
        } else {
            $body = '(function(array $json): array {
            $items = [];
            foreach (INPUT_PROPERTY as $item) {
                $a = LIST_ACCESSOR;
                if (null !== $a) {
                    $items[] = $a;
                }
            }

            return $items;
        })(INPUT)';
        }

        return strtr($body, [
            'LIST_ACCESSOR' => $this->parseElement('$item', $shapeMember->getShape()),
            'INPUT' => $input,
            'INPUT_PROPERTY' => $shape->isFlattened() ? '$json' : '$json' . ($shapeMember->getLocationName() ? '->' . $shapeMember->getLocationName() : ''),
        ]);
    }

    private function parseResponseMap(MapShape $shape, string $input): string
    {
        if (null === $locationName = $shape->getKey()->getLocationName()) {
            throw new \RuntimeException('This is not implemented yet');
        }

        $shapeValue = $shape->getValue();
        if ($shapeValue->getShape() instanceof StructureShape) {
            $body = '(function(array $json): array {
                $items = [];
                foreach ($json as $item) {
                    $items[$item[MAP_KEY]] = MAP_ACCESSOR;
                }

                return $items;
            })(INPUT)';
        } else {
            $body = '(function(array $json): array {
                $items = [];
                foreach ($json as $item) {
                    $a = MAP_ACCESSOR;
                    if (null !== $a) {
                        $items[$item[MAP_KEY]] = $a;
                    }
                }

                return $items;
            })(INPUT)';
        }

        return strtr($body, [
            'MAP_KEY' => var_export($locationName, true),
            'MAP_ACCESSOR' => $this->parseElement(sprintf('$item[\'%s\']', $this->getInputAccessorName($shapeValue)), $shapeValue->getShape()),
            'INPUT' => $input,
        ]);
    }
}
