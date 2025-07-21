<?php

declare(strict_types=1);

namespace AsyncAws\CodeGenerator\Generator\RequestSerializer;

use AsyncAws\CodeGenerator\Definition\DocumentShape;
use AsyncAws\CodeGenerator\Definition\ListShape;
use AsyncAws\CodeGenerator\Definition\Member;
use AsyncAws\CodeGenerator\Definition\Operation;
use AsyncAws\CodeGenerator\Definition\Shape;
use AsyncAws\CodeGenerator\Definition\StructureMember;
use AsyncAws\CodeGenerator\Definition\StructureShape;
use AsyncAws\CodeGenerator\Generator\Composer\RequirementsRegistry;
use AsyncAws\CodeGenerator\Generator\GeneratorHelper;
use AsyncAws\CodeGenerator\Generator\Naming\NamespaceRegistry;
use AsyncAws\Core\Exception\InvalidArgument;

/**
 * @author Jérémy Derussé <jeremy@derusse.com>
 *
 * @internal
 */
class RestXmlSerializer implements Serializer
{
    use UseClassesTrait;

    /**
     * @var NamespaceRegistry
     */
    private $namespaceRegistry;

    /**
     * @var RequirementsRegistry
     */
    private $requirementsRegistry;

    public function __construct(NamespaceRegistry $namespaceRegistry, RequirementsRegistry $requirementsRegistry)
    {
        $this->namespaceRegistry = $namespaceRegistry;
        $this->requirementsRegistry = $requirementsRegistry;
    }

    public function getHeaders(Operation $operation, bool $withPayload): string
    {
        if (!$withPayload) {
            return '[]';
        }

        return "['content-type' => 'application/xml']";
    }

    public function generateRequestBody(Operation $operation, StructureShape $shape): SerializerResultBody
    {
        $this->usedClassesInit();
        if (null !== $payloadProperty = $shape->getPayload()) {
            $member = $shape->getMember($payloadProperty);
            if ($member->isStreaming()) {
                if ($shape->getMember($payloadProperty)->isRequired()) {
                    $this->usedClassesAdd(InvalidArgument::class);
                    $body = 'if (null === $v = $this->PROPERTY) {
                        throw new InvalidArgument(sprintf(\'Missing parameter "NAME" for "%s". The value cannot be null.\', __CLASS__));
                    }
                    $body = $v;';
                } else {
                    $body = '$body = $this->PROPERTY ?? "";';
                }

                return new SerializerResultBody(strtr($body, [
                    'PROPERTY' => GeneratorHelper::normalizeName($payloadProperty),
                    'NAME' => $payloadProperty,
                ]), false, $this->usedClassesFlush());
            }
        }

        if (null !== $location = $operation->getInputLocation()) {
            $xmlnsValue = '';
            $xmlnsAttribute = '';
            if (null !== $ns = $operation->getInputXmlNamespaceUri()) {
                $xmlnsValue = $ns;
                $xmlnsAttribute = 'xmlns';
            }

            $requestBody = trim(strtr('
                $document->appendChild($child = $document->createElement(NODE_NAME));
                SET_XMLNS_CODE
                $this->requestBody($child, $document);
            ', [
                'NODE_NAME' => var_export($location, true),
                'SET_XMLNS_CODE' => $xmlnsValue ? strtr('$child->setAttribute(NS_ATTRIBUTE, NS_VALUE);', ['NS_ATTRIBUTE' => var_export($xmlnsAttribute, true), 'NS_VALUE' => var_export($xmlnsValue, true)]) : '',
            ]));
        } else {
            $requestBody = '$this->requestBody($document, $document);';
        }

        $this->requirementsRegistry->addRequirement('ext-dom');

        return new SerializerResultBody('
            $document = new \DOMDocument(\'1.0\', \'UTF-8\');
            $document->formatOutput = false;
            ' . $requestBody . '
            $body = $document->hasChildNodes() ? $document->saveXML() : "";
        ', true, $this->usedClassesFlush(), ['node' => \DOMNode::class]);
    }

    public function generateRequestBuilder(StructureShape $shape, bool $needsChecks): SerializerResultBuilder
    {
        $this->usedClassesInit();
        $body = implode("\n", array_map(function (StructureMember $member) use ($needsChecks) {
            if (null !== $member->getLocation()) {
                return '';
            }

            if ($member->isIdempotencyToken()) {
                $this->requirementsRegistry->addRequirement('symfony/polyfill-uuid', '^1.13.1');
                $body = 'if (null === $v = $this->PROPERTY) {
                    $v = uuid_create(UUID_TYPE_RANDOM);
                }
                MEMBER_CODE';
                $inputElement = '$v';
            } elseif ($member->isRequired()) {
                $this->usedClassesAdd(InvalidArgument::class);
                if ($needsChecks) {
                    $body = 'if (null === $v = $this->PROPERTY) {
                        throw new InvalidArgument(sprintf(\'Missing parameter "NAME" for "%s". The value cannot be null.\', __CLASS__));
                    }
                    MEMBER_CODE';
                } else {
                    $body = '$v = $this->PROPERTY;
                    MEMBER_CODE';
                }
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
                'MEMBER_CODE' => $deprecation . $this->dumpXmlShape($member, $member->getShape(), '$node', $inputElement),
            ]);
        }, $shape->getMembers()));

        return new SerializerResultBuilder('void', $body, $this->usedClassesFlush(), ['node' => \DOMElement::class, 'document' => \DOMDocument::class]);
    }

    private function dumpXmlShape(Member $member, Shape $shape, string $output, string $input): string
    {
        switch (true) {
            case $shape instanceof StructureShape:
                return $this->dumpXmlShapeStructure($member, $shape, $output, $input);
            case $shape instanceof ListShape:
                return $this->dumpXmlShapeList($member, $shape, $output, $input);
            case $shape instanceof DocumentShape:
                throw new \LogicException('Document shapes are not supported in the XML serializer for now.');
        }

        switch ($shape->getType()) {
            case 'blob':
                return $this->dumpXmlShapeBlob($member, $output, $input);
            case 'string':
                return $this->dumpXmlShapeString($member, $shape, $output, $input);
            case 'integer':
            case 'long':
            case 'float':
            case 'double':
                return $this->dumpXmlShapeNumeric($member, $output, $input);
            case 'boolean':
                return $this->dumpXmlShapeBoolean($member, $output, $input);
            case 'timestamp':
                return $this->dumpXmlShapeTimestamp($member, $output, $input);
        }

        throw new \RuntimeException(\sprintf('Type %s is not yet implemented', $shape->getType()));
    }

    private function dumpXmlShapeStructure(Member $member, StructureShape $shape, string $output, string $input): string
    {
        $xmlnsValue = '';
        $xmlnsAttribute = '';
        if ($member instanceof StructureMember && null !== $ns = $member->getXmlNamespaceUri()) {
            $xmlnsValue = $ns;
            $xmlnsAttribute = (null !== $prefix = $member->getXmlNamespacePrefix()) ? 'xmlns:' . $prefix : 'xmlns';
        } elseif (null !== $ns = $shape->getXmlNamespaceUri()) {
            $xmlnsValue = $ns;
            $xmlnsAttribute = (null !== $prefix = $shape->getXmlNamespacePrefix()) ? 'xmlns:' . $prefix : 'xmlns';
        }

        return strtr('
            OUTPUT->appendChild($child = $document->createElement(NODE_NAME));
            SET_XMLNS_CODE
            INPUT->requestBody($child, $document);
        ', [
            'OUTPUT' => $output,
            'NODE_NAME' => var_export($member->getLocationName() ?? ($member instanceof StructureMember ? $member->getName() : 'member'), true),
            'SET_XMLNS_CODE' => $xmlnsValue ? strtr('$child->setAttribute(NS_ATTRIBUTE, NS_VALUE);', ['NS_ATTRIBUTE' => var_export($xmlnsAttribute, true), 'NS_VALUE' => var_export($xmlnsValue, true)]) : '',
            'INPUT' => $input,
        ]);
    }

    private function dumpXmlShapeList(Member $member, ListShape $shape, string $output, string $input): string
    {
        if ($shape->isFlattened()) {
            return strtr('foreach (INPUT as $item) {
                    MEMBER_CODE
                }
            ', [
                'INPUT' => $input,
                'MEMBER_CODE' => $this->dumpXmlShape($member, $shape->getMember()->getShape(), $output, '$item'),
            ]);
        }

        return strtr('
            OUTPUT->appendChild($nodeList = $document->createElement(NODE_NAME));
            foreach (INPUT as $item) {
                MEMBER_CODE
            }
        ', [
            'OUTPUT' => $output,
            'NODE_NAME' => var_export($member->getLocationName() ?? ($member instanceof StructureMember ? $member->getName() : 'member'), true),
            'INPUT' => $input,
            'MEMBER_CODE' => $this->dumpXmlShape($shape->getMember(), $shape->getMember()->getShape(), '$nodeList', '$item'),
        ]);
    }

    private function dumpXmlShapeBlob(Member $member, string $output, string $input): string
    {
        if ($member instanceof StructureMember && $member->isXmlAttribute()) {
            $body = 'OUTPUT->setAttribute(NODE_NAME, base64_encode(INPUT));';
        } else {
            $body = 'OUTPUT->appendChild($document->createElement(NODE_NAME, base64_encode(INPUT)));';
        }

        $replacements = [
            'INPUT' => $input,
            'OUTPUT' => $output,
            'NODE_NAME' => var_export($member->getLocationName() ?? ($member instanceof StructureMember ? $member->getName() : 'member'), true),
        ];

        return strtr($body, $replacements);
    }

    private function dumpXmlShapeString(Member $member, Shape $shape, string $output, string $input): string
    {
        if ($member instanceof StructureMember && $member->isXmlAttribute()) {
            $body = 'OUTPUT->setAttribute(NODE_NAME, INPUT);';
        } else {
            $body = 'OUTPUT->appendChild($document->createElement(NODE_NAME, INPUT));';
        }

        $replacements = [
            'INPUT' => $input,
            'OUTPUT' => $output,
            'NODE_NAME' => var_export($member->getLocationName() ?? ($member instanceof StructureMember ? $member->getName() : 'member'), true),
        ];
        if (!empty($shape->getEnum())) {
            $enumClassName = $this->namespaceRegistry->getEnum($shape);
            $this->usedClassesAdd(InvalidArgument::class);
            $body = 'if (!ENUM_CLASS::exists(INPUT)) {
                    throw new InvalidArgument(sprintf(\'Invalid parameter "PROPERTY" for "%s". The value "%s" is not a valid "ENUM_CLASS".\', __CLASS__, INPUT));
                }
            ' . $body;
            $replacements += [
                'ENUM_CLASS' => $enumClassName->getName(),
                'PROPERTY' => $member->getLocationName() ?? ($member instanceof StructureMember ? $member->getName() : 'member'),
            ];
        }

        return strtr($body, $replacements);
    }

    private function dumpXmlShapeNumeric(Member $member, string $output, string $input): string
    {
        if ($member instanceof StructureMember && $member->isXmlAttribute()) {
            $body = 'OUTPUT->setAttribute(NODE_NAME, (string) INPUT);';
        } else {
            $body = 'OUTPUT->appendChild($document->createElement(NODE_NAME, (string) INPUT));';
        }

        $replacements = [
            'INPUT' => $input,
            'OUTPUT' => $output,
            'NODE_NAME' => var_export($member->getLocationName() ?? ($member instanceof StructureMember ? $member->getName() : 'member'), true),
        ];

        return strtr($body, $replacements);
    }

    private function dumpXmlShapeBoolean(Member $member, string $output, string $input): string
    {
        if ($member instanceof StructureMember && $member->isXmlAttribute()) {
            throw new \InvalidArgumentException('Boolean Shape with xmlAttribute is not yet implemented.');
        }

        $body = 'OUTPUT->appendChild($document->createElement(NODE_NAME, INPUT ? \'true\' : \'false\'));';

        return strtr($body, [
            'INPUT' => $input,
            'OUTPUT' => $output,
            'NODE_NAME' => var_export($member->getLocationName() ?? ($member instanceof StructureMember ? $member->getName() : 'member'), true),
        ]);
    }

    private function dumpXmlShapeTimestamp(Member $member, string $output, string $input): string
    {
        $format = $member->getShape()->get('timestampFormat') ?? 'iso8601';
        switch ($format) {
            case 'iso8601':
                $format = 'ATOM';

                break;
            case 'rfc822':
                $format = 'RFC822';

                break;
            default:
                throw new \RuntimeException(\sprintf('Timestamp format %s is not yet implemented', $format));
        }

        if ($member instanceof StructureMember && $member->isXmlAttribute()) {
            $body = 'OUTPUT->setAttribute(NODE_NAME, INPUT->format(\DateTimeInterface::FORMAT));';
        } else {
            $body = 'OUTPUT->appendChild($document->createElement(NODE_NAME, INPUT->format(\DateTimeInterface::FORMAT)));';
        }

        $replacements = [
            'INPUT' => $input,
            'OUTPUT' => $output,
            'FORMAT' => $format,
            'NODE_NAME' => var_export($member->getLocationName() ?? ($member instanceof StructureMember ? $member->getName() : 'member'), true),
        ];

        return strtr($body, $replacements);
    }
}
