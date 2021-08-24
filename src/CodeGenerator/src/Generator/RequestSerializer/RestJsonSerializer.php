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
use AsyncAws\CodeGenerator\Generator\Composer\RequirementsRegistry;
use AsyncAws\CodeGenerator\Generator\GeneratorHelper;
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

    private $requirementRegistry;

    public function __construct(NamespaceRegistry $namespaceRegistry, RequirementsRegistry $requirementRegistry)
    {
        $this->namespaceRegistry = $namespaceRegistry;
        $this->requirementRegistry = $requirementRegistry;
    }

    public function getHeaders(Operation $operation): string
    {
        return '["content-type" => "application/json"]';
    }

    public function generateRequestBody(Operation $operation, StructureShape $shape): array
    {
        if (null !== $payloadProperty = $shape->getPayload()) {
            if ($shape->getMember($payloadProperty)->isRequired()) {
                $body = 'if (null === $v = $this->PROPERTY) {
                    throw new InvalidArgument(sprintf(\'Missing parameter "NAME" for "%s". The value cannot be null.\', __CLASS__));
                }
                $body = $v;';
            } else {
                $body = '$body = $this->PROPERTY ?? "";';
            }

            return [strtr($body, [
                'PROPERTY' => GeneratorHelper::normalizeName($payloadProperty),
                'NAME' => $payloadProperty,
            ]), false];
        }

        $this->requirementRegistry->addRequirement('ext-json');

        return ['$bodyPayload = $this->requestBody(); $body = empty($bodyPayload) ? "{}" : \json_encode($bodyPayload, ' . \JSON_THROW_ON_ERROR . ');', true];
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
                    throw new InvalidArgument(sprintf(\'Missing parameter "NAME" for "%s". The value cannot be null.\', __CLASS__));
                }
                MEMBER_CODE';
                $inputElement = '$v';
            } else {
                $body = 'if (null !== $v = $this->PROPERTY) {
                    MEMBER_CODE
                }';
                $inputElement = '$v';
            }

            $deprecation = '';
            if ($member->isDeprecated()) {
                $deprecation = strtr('@trigger_error(\sprintf(\'The property "NAME" of "%s" is deprecated by AWS.\', __CLASS__), E_USER_DEPRECATED);', ['NAME' => $member->getName()]);
            }

            return strtr($body, [
                'PROPERTY' => GeneratorHelper::normalizeName($member->getName()),
                'NAME' => $member->getName(),
                'MEMBER_CODE' => $deprecation . $this->dumpArrayElement(sprintf('["%s"]', $name = $this->getName($member)), $inputElement, $name, $shape, $member->isRequired()),
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

    protected function dumpArrayBoolean(string $output, string $input, Shape $shape): string
    {
        return strtr('$payloadOUTPUT = (bool) INPUT;', [
            'OUTPUT' => $output,
            'INPUT' => $input,
        ]);
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
            throw new \RuntimeException('Guessing the name for this member not yet implemented');
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
                return $this->dumpArrayMap($output, $input, $contextProperty, $shape);
        }

        switch ($shape->getType()) {
            case 'string':
            case 'integer':
            case 'float':
            case 'long':
            case 'double':
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
        static $counter = -1;

        try {
            ++$counter;
            $cleanCounter = $counter ?: '';

            return strtr('
                $indexCOUNTER = -1;
                $payloadOUTPUT = [];
                foreach (INPUT as $listValueCOUNTER) {
                    $indexCOUNTER++;
                    MEMBER_CODE
                }
            ',
                [
                    'INPUT' => $input,
                    'OUTPUT' => $output,
                    'COUNTER' => $cleanCounter ?: '',
                    'MEMBER_CODE' => $memberCode = $this->dumpArrayElement(sprintf('%s[$index' . $cleanCounter . ']', $output), '$listValue' . $cleanCounter, $contextProperty, $memberShape, true),
                ]);
        } finally {
            --$counter;
        }
    }

    private function dumpArrayMap(string $output, string $input, $contextProperty, MapShape $shape): string
    {
        $mapKeyShape = $shape->getKey()->getShape();
        if (!empty($mapKeyShape->getEnum())) {
            $enumClassName = $this->namespaceRegistry->getEnum($mapKeyShape);
            $validateEnum = strtr('if (!ENUM_CLASS::exists($mapKey)) {
                    throw new InvalidArgument(sprintf(\'Invalid key for "%s". The value "%s" is not a valid "ENUM_CLASS".\', __CLASS__, $mapKey));
                }', [
                'ENUM_CLASS' => $enumClassName->getName(),
            ]);
        } else {
            $validateEnum = '';
        }

        $memberCode = $this->dumpArrayElement($output . '[$name]', '$mv', $contextProperty, $shape->getValue()->getShape());

        return strtr('
if (empty(INPUT)) {
    $payloadOUTPUT = new \stdClass();
} else {
    $payloadOUTPUT = [];
    foreach (INPUT as $name => $mv) {
        VALIDATE_ENUM
        MEMBER_CODE
    }
}',
            [
                'VALIDATE_ENUM' => $validateEnum,
                'OUTPUT' => $output,
                'INPUT' => $input,
                'MEMBER_CODE' => $memberCode,
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

if (!\defined('JSON_THROW_ON_ERROR')) {
    \define('JSON_THROW_ON_ERROR', 4194304);
}
