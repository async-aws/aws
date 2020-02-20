<?php

declare(strict_types=1);

namespace AsyncAws\CodeGenerator\Generator;

use AsyncAws\CodeGenerator\Definition\ListShape;
use AsyncAws\CodeGenerator\Definition\MapShape;
use AsyncAws\CodeGenerator\Definition\Member;
use AsyncAws\CodeGenerator\Definition\Shape;
use AsyncAws\CodeGenerator\Definition\StructureMember;
use AsyncAws\CodeGenerator\Definition\StructureShape;

/**
 * @author Jérémy Derussé <jeremy@derusse.com>
 *
 * @internal
 */
class ArrayDumper
{
    public function dumpArrayRoot(StructureShape $shape): string
    {
        $body = implode("\n", array_map(function (StructureMember $member) {
            if (null !== $member->getLocation() ?? null) {
                return '';
            }
            $shape = $member->getShape();
            if ($member->isRequired() || $shape instanceof ListShape || $shape instanceof MapShape) {
                $body = 'MEMBER_CODE;';
                $inputElement = '$this->' . $member->getName();
            } else {
                $body = '
                    if (null !== $v = INPUT_NAME) {
                        MEMBER_CODE;
                    }
                ';
                $inputElement = '$v';
            }

            return strtr($body, [
                'INPUT_NAME' => '$this->' . $member->getName(),
                'MEMBER_CODE' => $this->dumpArray($this->getName($member), $inputElement, $shape),
            ]);
        }, $shape->getMembers()));

        return false !== \strpos($body, '$indices') ? '$indices = new \stdClass();' . $body : $body;
    }

    private function getName(Member $member)
    {
        if (($shape = $member->getShape()) instanceof ListShape && $shape->isFlattened()) {
            return $member->getLocationName() ?? $shape->getMember()->getLocationName() ?? ($member instanceof StructureMember ? $member->getName() : 'member');
        }

        return $member->getLocationName() ?? ($member instanceof StructureMember ? $member->getName() : 'member');
    }

    private function dumpArray(string $output, string $input, Shape $shape)
    {
        switch (true) {
            case $shape instanceof StructureShape:
                return $this->dumpArrayStructure($output, $input, $shape);
            case $shape instanceof ListShape:
                return $this->dumpArrayList($output, $input, $shape);
            case $shape instanceof MapShape:
                return $this->dumpArrayMap($output, $input, $shape);
        }

        switch ($shape->getType()) {
            case 'string':
            case 'integer':
                return $this->dumpArrayScalar($output, $input, $shape);
            case 'boolean':
                return $this->dumpArrayBoolean($output, $input, $shape);
            case 'timestamp':
                return $this->dumpArrayTimestamp($output, $input, $shape);
            case 'blob':
                return $this->dumpArrayBlob($output, $input, $shape);
        }

        throw new \RuntimeException(sprintf('Type %s is not yet implemented', $shape->getType()));
    }

    private function dumpArrayStructure(string $output, string $input, StructureShape $shape): string
    {
        return strtr('(static function($input) use (USE) {
                MEMBERS_CODE
            })(INPUT);',
        [
            'INPUT' => $input,
            'MEMBERS_CODE' => $memberCode = implode("\n", array_map(function (StructureMember $member) use ($output) {
                $shape = $member->getShape();
                if ($member->isRequired() || $shape instanceof ListShape || $shape instanceof MapShape) {
                    $body = 'MEMBER_CODE;';
                    $inputElement = '$input->get' . $member->getName() . '()';
                } else {
                    $body = 'if (null !== $v = INPUT_NAME) {
                        MEMBER_CODE;
                    }';
                    $inputElement = '$v';
                }

                return strtr($body, [
                    'INPUT_NAME' => '$input->get' . $member->getName() . '()',
                    'MEMBER_CODE' => $this->dumpArray(sprintf('%s.%s', $output, $this->getName($member)), $inputElement, $shape),
                ]);
            }, $shape->getMembers())),
            'USE' => \strpos($memberCode, '$indices') ? '&$payload, $indices' : '&$payload',
        ]);
    }

    private function dumpArrayMap(string $output, string $input, MapShape $shape): string
    {
        return strtr('(static function($input) use (USE) {
                $indices->INDEX_KEY = 0;
                foreach ($input as $key => $value) {
                    $indices->INDEX_KEY++;
                    $payload["OUTPUT_KEY"] = $key;
                    MEMBER_CODE
                }
            })(INPUT);',
        [
            'INPUT' => $input,
            'INDEX_KEY' => $indexKey = 'k' . \substr(sha1($output), 0, 7),
            'OUTPUT_KEY' => sprintf('%s.{$indices->%s}.%s', $output, $indexKey, $shape->getKey()->getLocationName() ?? 'key'),
            'MEMBER_CODE' => $memberCode = $this->dumpArray(sprintf('%s.{$indices->%s}.%s', $output, $indexKey, $shape->getValue()->getLocationName() ?? 'value'), '$value', $shape->getValue()->getShape()),
            'USE' => \strpos($memberCode, '$indices') ? '&$payload, $indices' : '&$payload',
        ]);
    }

    private function dumpArrayList(string $output, string $input, ListShape $shape): string
    {
        return strtr('(static function($input) use (USE) {
                $indices->INDEX_KEY = 0;
                foreach ($input as $value) {
                    $indices->INDEX_KEY++;
                    MEMBER_CODE
                }
            })(INPUT);',
            [
                'INPUT' => $input,
                'INDEX_KEY' => $indexKey = 'k' . \substr(sha1($output), 0, 7),
                'MEMBER_CODE' => $memberCode = $this->dumpArray(sprintf('%s.{$indices->%s}', $output, $indexKey), '$value', $shape->getMember()->getShape()),
                'USE' => \strpos($memberCode, '$indices') ? '&$payload, $indices' : '&$payload',
            ]);
    }

    private function dumpArrayScalar(string $output, string $input, Shape $shape): string
    {
        return strtr('$payload["OUTPUT"] = INPUT;', [
            'OUTPUT' => $output,
            'INPUT' => $input,
        ]);
    }

    private function dumpArrayBoolean(string $output, string $input, Shape $shape): string
    {
        return strtr('$payload["OUTPUT"] = INPUT ? "true" : "false";', [
            'OUTPUT' => $output,
            'INPUT' => $input,
        ]);
    }

    private function dumpArrayTimestamp(string $output, string $input, Shape $shape): string
    {
        return strtr('$payload["OUTPUT"] = INPUT->format(\DateTimeInterface::ATOM);', [
            'OUTPUT' => $output,
            'INPUT' => $input,
        ]);
    }

    private function dumpArrayBlob(string $output, string $input, Shape $shape): string
    {
        return strtr('$payload["OUTPUT"] = base64_encode(INPUT);', [
            'OUTPUT' => $output,
            'INPUT' => $input,
        ]);
    }
}
