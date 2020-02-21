<?php

declare(strict_types=1);

namespace AsyncAws\CodeGenerator\Generator\CodeGenerator;

use AsyncAws\CodeGenerator\Definition\ListMember;
use AsyncAws\CodeGenerator\Definition\ListShape;
use AsyncAws\CodeGenerator\Definition\Member;
use AsyncAws\CodeGenerator\Definition\Shape;
use AsyncAws\CodeGenerator\Definition\StructureMember;
use AsyncAws\CodeGenerator\Definition\StructureShape;

/**
 * @author Jérémy Derussé <jeremy@derusse.com>
 *
 * @internal
 */
class XmlDumper
{
    public function dumpXml(StructureMember $member, string $payloadProperty): string
    {
        return strtr('
            $document = new \DOMDocument(\'1.0\', \'UTF-8\');
            $document->formatOutput = true;

            CHILDREN_CODE

            return $document->saveXML();
        ', [
            'ROOT_NODE' => \var_export($member->getLocationName(), true),
            'ROOT_URI' => (null !== $ns = $member->getXmlNamespaceUri()) ? strtr('$root->setAttribute(\'xmlns\', NS);', ['NS' => \var_export($ns, true)]) : '',
            'CHILDREN_CODE' => $this->dumpXmlMember($member, '$document', '$this'),
            'PAYLOAD_PROPERTY' => $payloadProperty,
        ]);
    }

    private function dumpXmlMember(Member $member, string $output, string $input): string
    {
        switch (true) {
            case $member instanceof StructureMember:
                return $this->dumpXmlMemberStructure($member, $output, $input);
            case $member instanceof ListMember:
                return $this->dumpXmlMemberList($member, $output, $input);
        }

        throw new \RuntimeException(sprintf('Type %s is not yet implemented', \get_class($member)));
    }

    private function dumpXmlMemberStructure(StructureMember $member, string $output, string $input): string
    {
        if ($member->isRequired() || $member->getShape() instanceof ListShape) {
            if ('$this' === $input) {
                $body = '
                    if (null === INPUT_E = INPUT->INPUT_NAME) {
                        throw new InvalidArgument(sprintf(\'Missing parameter "INPUT_NAME" in "%s". The value cannot be null.\', __CLASS__));
                    }
                    SHAPE_CODE
                ';
            } else {
                $body = '
                    INPUT_E = INPUT->INPUT_NAME;
                    SHAPE_CODE
                ';
            }
        } else {
            $body = '
                if (null !== INPUT_E = INPUT->INPUT_NAME) {
                    SHAPE_CODE
                }
            ';
        }

        return strtr($body, [
            'INPUT' => $input,
            'INPUT_E' => $inputElement = ('$this' === $input ? '$input' : $input . '_' . $member->getName()),
            'INPUT_NAME' => '$this' === $input ? $member->getName() : 'get' . $member->getName() . '()',
            'SHAPE_CODE' => $this->dumpXmlShape($member, $member->getShape(), $output, $inputElement),
        ]);
    }

    private function dumpXmlMemberList(ListMember $member, string $output, string $input): string
    {
        return $this->dumpXmlShape($member, $member->getShape(), $output, $input);
    }

    private function dumpXmlShape(Member $member, Shape $shape, string $output, string $input): string
    {
        switch (true) {
            case $shape instanceof StructureShape:
                return $this->dumpXmlShapeStructure($member, $shape, $output, $input);
            case $shape instanceof ListShape:
                return $this->dumpXmlShapeList($member, $shape, $output, $input);
        }

        switch ($shape->getType()) {
            case 'blob':
            case 'string':
                return $this->dumpXmlShapeString($member, $shape, $output, $input);
            case 'boolean':
                return $this->dumpXmlShapeBoolean($member, $shape, $output, $input);
        }

        throw new \RuntimeException(sprintf('Type %s is not yet implemented', $shape->getType()));
    }

    private function dumpXmlShapeStructure(Member $member, StructureShape $shape, string $output, string $input): string
    {
        $outputElement = $output . '_' . ($member instanceof StructureMember ? $member->getName() : 'Item');

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
            OUTPUT->appendChild(OUTPUT_E = $document->createElement(OUTPUT_NAME));
            SET_XMLNS_CODE
            MEMBERS_CODE
        ', [
            'OUTPUT' => $output,
            'OUTPUT_E' => $outputElement,
            'OUTPUT_NAME' => \var_export($member->getLocationName() ?? ($member instanceof StructureMember ? $member->getName() : 'member'), true),
            'SET_XMLNS_CODE' => $xmlnsValue ? strtr('OUTPUT_E->setAttribute(NS_ATTRIBUTE, NS_VALUE);', ['OUTPUT_E' => $outputElement, 'NS_ATTRIBUTE' => \var_export($xmlnsAttribute, true), 'NS_VALUE' => \var_export($xmlnsValue, true)]) : '',
            'MEMBERS_CODE' => implode("\n", \array_map(function (StructureMember $member) use ($outputElement, $input) {
                return $this->dumpXmlMember($member, $outputElement, $input);
            }, $shape->getMembers())),
        ]);
    }

    private function dumpXmlShapeList(Member $member, ListShape $shape, string $output, string $input): string
    {
        if ($shape->isFlattened()) {
            return strtr('
                foreach (INPUT as INPUT_E) {
                    MEMBER_CODE
                }
            ', [
                'INPUT' => $input,
                'INPUT_E' => $inputElement = ('$this' === $input ? '$item' : $input . 'Item'),
                'MEMBER_CODE' => $this->dumpXmlShape($member, $shape->getMember()->getShape(), $output, $inputElement),
            ]);
        }

        return strtr('
                OUTPUT->appendChild(OUTPUT_E = $document->createElement(OUTPUT_NAME));
                foreach (INPUT as INPUT_E) {
                    MEMBER_CODE
                }
            ', [
            'OUTPUT' => $output,
            'OUTPUT_E' => $outputElement = $output . '_' . ($member instanceof StructureMember ? $member->getName() : 'member'),
            'OUTPUT_NAME' => \var_export($member->getLocationName() ?? ($member instanceof StructureMember ? $member->getName() : 'member'), true),
            'INPUT' => $input,
            'INPUT_E' => $inputElement = ('$this' === $input ? '$item' : $input . 'Item'),
            'MEMBER_CODE' => $this->dumpXmlMember($shape->getMember(), $outputElement, $inputElement),
        ]);
    }

    private function dumpXmlShapeString(Member $member, Shape $shape, string $output, string $input): string
    {
        if ($member instanceof StructureMember && $member->isXmlAttribute()) {
            $body = 'OUTPUT->setAttribute(OUTPUT_NAME, INPUT);';
        } else {
            $body = 'OUTPUT->appendChild($document->createElement(OUTPUT_NAME, INPUT));';
        }

        return \strtr($body, [
            'INPUT' => $input,
            'OUTPUT' => $output,
            'OUTPUT_NAME' => \var_export($member->getLocationName() ?? ($member instanceof StructureMember ? $member->getName() : 'member'), true),
        ]);
    }

    private function dumpXmlShapeBoolean(Member $member, Shape $shape, string $output, string $input): string
    {
        if ($member instanceof StructureMember && $member->isXmlAttribute()) {
            throw new \InvalidArgumentException('Boolean Shape with xmlAttribute is not yet implemented.');
        }

        $body = 'OUTPUT->appendChild($document->createElement(OUTPUT_NAME, INPUT ? \'true\' : \'false\'));';

        return \strtr($body, [
            'INPUT' => $input,
            'OUTPUT' => $output,
            'OUTPUT_NAME' => \var_export($member->getLocationName() ?? ($member instanceof StructureMember ? $member->getName() : 'member'), true),
        ]);
    }
}
