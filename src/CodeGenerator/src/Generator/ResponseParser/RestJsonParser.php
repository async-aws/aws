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
        if (null !== $payloadProperty = $shape->getPayload()) {
            return strtr('$this->PROPERTY_NAME = $response->getContent(false);', ['PROPERTY_NAME' => $payloadProperty]);
        }

        $properties = [];
        foreach ($shape->getMembers() as $member) {
            if (\in_array($member->getLocation(), ['header', 'headers'])) {
                continue;
            }

            $properties[] = strtr('$this->PROPERTY_NAME = PROPERTY_ACCESSOR;', [
                'PROPERTY_NAME' => $member->getName(),
                'PROPERTY_ACCESSOR' => $this->parseElement(sprintf('$data[\'%s\']', $this->getInputAccessorName($member)), $member->getShape(), $member->isRequired()),
            ]);
        }

        if (empty($properties)) {
            return '';
        }

        $body = '$data = $response->toArray(false);' . "\n";
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

    private function parseElement(string $input, Shape $shape, bool $required)
    {
        switch (true) {
            case $shape instanceof ListShape:
                return $this->parseResponseList($shape, $input, $required);
            case $shape instanceof StructureShape:
                return $this->parseResponseStructure($shape, $input, $required);
            case $shape instanceof MapShape:
                return $this->parseResponseMap($shape, $input, $required);
        }

        switch ($shape->getType()) {
            case 'string':
            case 'long':
                return $this->parseResponseString($input, $required);
            case 'integer':
                return $this->parseResponseInteger($input, $required);
            case 'float':
            case 'double':
                return $this->parseResponseFloat($input, $required);
            case 'boolean':
                return $this->parseResponseBool($input, $required);
            case 'blob':
                return $this->parseResponseBlob($input, $required);
            case 'timestamp':
                return $this->parseResponseTimestamp($shape, $input, $required);
        }

        throw new \RuntimeException(sprintf('Type %s is not yet implemented', $shape->getType()));
    }

    private function parseResponseStructure(StructureShape $shape, string $input, bool $required): string
    {
        $properties = [];
        foreach ($shape->getMembers() as $member) {
            $properties[] = strtr('PROPERTY_NAME => PROPERTY_ACCESSOR,', [
                'PROPERTY_NAME' => var_export($member->getName(), true),
                'PROPERTY_ACCESSOR' => $this->parseElement(sprintf('%s[\'%s\']', $input, $this->getInputAccessorName($member)), $member->getShape(), $member->isRequired()),
            ]);
        }

        return strtr('new CLASS_NAME([
            PROPERTIES
        ])', [
            'CLASS_NAME' => $this->namespaceRegistry->getObject($shape)->getName(),
            'PROPERTIES' => implode("\n", $properties),
        ]);
    }

    private function parseResponseString(string $input, bool $required): string
    {
        if ($required) {
            return strtr('(string) INPUT', ['INPUT' => $input]);
        }

        return strtr('isset(INPUT) ? (string) INPUT : null', ['INPUT' => $input]);
    }

    private function parseResponseInteger(string $input, bool $required): string
    {
        if ($required) {
            return strtr('(int) INPUT', ['INPUT' => $input]);
        }

        return strtr('isset(INPUT) ? (int) INPUT : null', ['INPUT' => $input]);
    }

    private function parseResponseFloat(string $input, bool $required): string
    {
        if ($required) {
            return strtr('(float) INPUT', ['INPUT' => $input]);
        }

        return strtr('isset(INPUT) ? (float) INPUT : null', ['INPUT' => $input]);
    }

    private function parseResponseBool(string $input, bool $required): string
    {
        if ($required) {
            return strtr('filter_var(INPUT, FILTER_VALIDATE_BOOLEAN)', ['INPUT' => $input]);
        }

        return strtr('isset(INPUT) ? filter_var(INPUT, FILTER_VALIDATE_BOOLEAN) : null', ['INPUT' => $input]);
    }

    private function parseResponseBlob(string $input, bool $required): string
    {
        if ($required) {
            return strtr('base64_decode((string) INPUT)', ['INPUT' => $input]);
        }

        return strtr('isset(INPUT) ? base64_decode((string) INPUT) : null', ['INPUT' => $input]);
    }

    private function parseResponseTimestamp(Shape $shape, string $input, bool $required): string
    {
        if ('unixTimestamp' === $shape->get('timestampFormat')) {
            if ($required) {
                return strtr('\DateTimeImmutable::setTimestamp((string) INPUT)', ['INPUT' => $input]);
            }

            return strtr('isset(INPUT) ? \DateTimeImmutable::setTimestamp((string) INPUT) : null', ['INPUT' => $input]);
        }

        if ($required) {
            return strtr('new \DateTimeImmutable((string) INPUT)', ['INPUT' => $input]);
        }

        return strtr('isset(INPUT) ? new \DateTimeImmutable((string) INPUT) : null', ['INPUT' => $input]);
    }

    private function parseResponseList(ListShape $shape, string $input, bool $required): string
    {
        $shapeMember = $shape->getMember();
        if ($shapeMember->getShape() instanceof StructureShape) {
            $listAccessorRequired = true;
            $body = '(function(array $json): array {
            $items = [];
            foreach (INPUT_PROPERTY as $item) {
               $items[] = LIST_ACCESSOR;
            }

            return $items;
        })(INPUT)';
        } else {
            $listAccessorRequired = false;
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

        if (!$required) {
            $body = '!INPUT ? [] : ' . $body;
        }

        return strtr($body, [
            'LIST_ACCESSOR' => $this->parseElement('$item', $shapeMember->getShape(), $listAccessorRequired),
            'INPUT' => $input,
            'INPUT_PROPERTY' => $shape->isFlattened() ? '$json' : '$json' . ($shapeMember->getLocationName() ? '->' . $shapeMember->getLocationName() : ''),
        ]);
    }

    private function parseResponseMap(MapShape $shape, string $input, bool $required): string
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

        if (!$required) {
            $body = '!INPUT ? [] : ' . $body;
        }

        return strtr($body, [
            'MAP_KEY' => var_export($locationName, true),
            'MAP_ACCESSOR' => $this->parseElement(sprintf('$item[\'%s\']', $this->getInputAccessorName($shapeValue)), $shapeValue->getShape(), false),
            'INPUT' => $input,
        ]);
    }
}
