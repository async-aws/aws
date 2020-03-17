<?php

declare(strict_types=1);

namespace AsyncAws\CodeGenerator\Generator\RequestSerializer;

use AsyncAws\CodeGenerator\Definition\ListShape;
use AsyncAws\CodeGenerator\Definition\MapShape;
use AsyncAws\CodeGenerator\Definition\Member;
use AsyncAws\CodeGenerator\Definition\Operation;
use AsyncAws\CodeGenerator\Definition\Shape;
use AsyncAws\CodeGenerator\Definition\StructureMember;
use AsyncAws\CodeGenerator\Definition\StructureShape;
use AsyncAws\CodeGenerator\Generator\Naming\NamespaceRegistry;

/**
 * Serialize a request body to a nice nested array.
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 *
 * @internal
 */
class RestJsonSerializer implements Serializer
{
    private $namespaceRegistry;

    public function __construct(NamespaceRegistry $namespaceRegistry)
    {
        $this->namespaceRegistry = $namespaceRegistry;
    }

    public function getContentType(): string
    {
        return 'application/json';
    }

    public function generateRequestBody(Operation $operation, StructureShape $shape): array
    {
        if (null !== $payloadProperty = $shape->getPayload()) {
            if ($shape->getMember($payloadProperty)->isRequired()) {
                $body = 'if (null === $v = $this->PROPERTY) {
                    throw new InvalidArgument(sprintf(\'Missing parameter "PROPERTY" for "%s". The value cannot be null.\', __CLASS__));
                }
                $body = $v;';
            } else {
                $body = '$body = $this->PROPERTY ?? "";';
            }

            return [strtr($body, ['PROPERTY' => $payloadProperty]), false];
        }

        return ['$bodyPayload = $this->requestBody(); $body = empty($bodyPayload) ? "{}" : json_encode($bodyPayload);', true];
    }

    public function generateRequestBuilder(StructureShape $shape): array
    {
        $body = implode("\n", array_map(function (StructureMember $member) {
            if (null !== $member->getLocation()) {
                return '';
            }
            $shape = $member->getShape();
            if ($member->isRequired()) {
                $body = 'if (null === $v = $this->PROPERTY) {
                    throw new InvalidArgument(sprintf(\'Missing parameter "PROPERTY" for "%s". The value cannot be null.\', __CLASS__));
                }
                MEMBER_CODE';
                $inputElement = '$v';
            } elseif ($shape instanceof ListShape || $shape instanceof MapShape) {
                $body = 'MEMBER_CODE';
                $inputElement = '$this->' . $member->getName();
            } else {
                $body = 'if (null !== $v = $this->PROPERTY) {
                    MEMBER_CODE
                }';
                $inputElement = '$v';
            }

            return strtr($body, [
                'PROPERTY' => $member->getName(),
                'MEMBER_CODE' => $this->dumpArrayElement(sprintf('["%s"]', $name = $this->getName($member)), $inputElement, $name, $shape, $member->isRequired()),
            ]);
        }, $shape->getMembers()));

        return ['array', strtr('
                $payload = [];
                CHILDREN_CODE

                return $payload;
            ', [
            'CHILDREN_CODE' => $body,
        ])];
    }

    private function getQueryName(Member $member, string $default): string
    {
        if (null !== $member->getQueryName()) {
            return $member->getQueryName();
        }
        if (null !== $member->getLocationName()) {
            return $member->getLocationName();
        }
        $shape = $member->getShape();
        if ($member->isFlattened() && $shape instanceof ListShape && null !== $memberLocation = $shape->getMember()->getLocationName()) {
            return $memberLocation;
        }

        return $default;
    }

    private function getName(Member $member): string
    {
        if ($member instanceof StructureMember) {
            $name = $this->getQueryName($member, $member->getName());
        } else {
            throw new \RuntimeException('Guessing the name fot this member not yet implemented');
        }

        return $name;
    }

    private function dumpArrayElement(string $output, string $input, string $contextProperty, Shape $shape, bool $isRequired = false)
    {
        switch (true) {
            case $shape instanceof StructureShape:
                return $this->dumpArrayStructure($output, $input, $shape);
            case $shape instanceof ListShape:
                return $this->dumpArrayList($output, $input, $contextProperty, $shape);
            case $shape instanceof MapShape:
                throw new \RuntimeException('MapShapes are not implemented');
        }

        switch ($shape->getType()) {
            case 'string':
            case 'integer':
            case 'long':
                return $this->dumpArrayScalar($output, $input, $contextProperty, $shape);
            case 'boolean':
                return $this->dumpArrayBoolean($output, $input, $shape);
            case 'timestamp':
                return $this->dumpArrayTimestamp($output, $input, $shape);
            case 'blob':
                return $this->dumpArrayBlob($output, $input, $shape, $isRequired);
        }

        throw new \RuntimeException(sprintf('Type %s is not yet implemented', $shape->getType()));
    }

    private function dumpArrayStructure(string $output, string $input, StructureShape $shape): string
    {
        return strtr('$payloadOUTPUT = INPUT->requestBody();', [
            'OUTPUT' => $output,
            'INPUT' => $input,
        ]);
    }

    private function dumpArrayList(string $output, string $input, string $contextProperty, ListShape $shape): string
    {
        $memberShape = $shape->getMember()->getShape();

        return strtr('
            $index = -1;
            foreach (INPUT as $listValue) {
                $index++;
                MEMBER_CODE
            }
        ',
            [
                'INPUT' => $input,
                'MEMBER_CODE' => $memberCode = $this->dumpArrayElement(sprintf('%s[$index]', $output), '$listValue', $contextProperty, $memberShape, true),
            ]);
    }

    private function dumpArrayScalar(string $output, string $input, string $contextProperty, Shape $shape): string
    {
        $body = '$payloadOUTPUT = INPUT;';
        $replacements = [
            'OUTPUT' => $output,
            'INPUT' => $input,
        ];
        if (!empty($shape->getEnum())) {
            $enumClassName = $this->namespaceRegistry->getEnum($shape);
            $body = 'if (!ENUM_CLASS::exists(INPUT)) {
                    throw new InvalidArgument(sprintf(\'Invalid parameter "PROPERTY" for "%s". The value "%s" is not a valid "ENUM_CLASS".\', __CLASS__, INPUT));
                }
            ' . $body;
            $replacements += [
                'ENUM_CLASS' => $enumClassName->getName(),
                'PROPERTY' => $contextProperty,
            ];
        }

        return strtr($body, $replacements);
    }

    private function dumpArrayBoolean(string $output, string $input, Shape $shape): string
    {
        return strtr('$payloadOUTPUT = INPUT ? "true" : "false";', [
            'OUTPUT' => $output,
            'INPUT' => $input,
        ]);
    }

    private function dumpArrayTimestamp(string $output, string $input, Shape $shape): string
    {
        return strtr('$payloadOUTPUT = INPUT->format(\DateTimeInterface::ATOM);', [
            'OUTPUT' => $output,
            'INPUT' => $input,
        ]);
    }

    private function dumpArrayBlob(string $output, string $input, Shape $shape, bool $isRequired): string
    {
        return strtr('$payloadOUTPUT = base64_encode(INPUT);', [
            'OUTPUT' => $output,
            'INPUT' => ($isRequired || false === strpos($input, '->')) ? $input : $input . ' ?? \'\'',
        ]);
    }
}
