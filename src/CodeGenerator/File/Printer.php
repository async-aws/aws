<?php

declare(strict_types=1);

namespace AsyncAws\CodeGenerator\File;

use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\Closure;
use Nette\PhpGenerator\Dumper;
use Nette\PhpGenerator\GlobalFunction;
use Nette\PhpGenerator\Helpers;
use Nette\PhpGenerator\Method;
use Nette\PhpGenerator\PhpNamespace;
use Nette\PhpGenerator\Printer as BasePrinter;
use Nette\Utils\Strings;

/**
 * Convert a class definition to a file string.
 *
 * @internal
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
class Printer extends BasePrinter
{
    /**
     * @var string
     */
    protected $indentation = '    ';

    /**
     * @var int
     */
    protected $linesBetweenMethods = 1;

    /**
     * @var bool
     */
    private $resolveTypes = true;

    public function printClass(ClassType $class, PhpNamespace $namespace = null): string
    {
        $class->validate();
        $resolver = $this->resolveTypes && $namespace ? [$namespace, 'unresolveName'] : function ($s) { return $s; };

        $traits = [];
        foreach ($class->getTraitResolutions() as $trait => $resolutions) {
            $traits[] = 'use ' . $resolver($trait)
                . ($resolutions ? " {\n" . $this->indentation . implode(";\n" . $this->indentation, $resolutions) . ";\n}\n" : ";\n");
        }

        $consts = [];
        foreach ($class->getConstants() as $const) {
            $def = ($const->getVisibility() ? $const->getVisibility() . ' ' : '') . 'const ' . $const->getName() . ' = ';
            $consts[] = Helpers::formatDocComment((string) $const->getComment())
                . $def
                . $this->dump($const->getValue(), \strlen($def)) . ";\n";
        }

        $properties = [];
        foreach ($class->getProperties() as $property) {
            $type = $property->getType();
            $def = (($property->getVisibility() ?: 'public') . ($property->isStatic() ? ' static' : '') . ' '
                . ($type ? ($property->isNullable() ? '?' : '') . ($this->resolveTypes && $namespace ? $namespace->unresolveName($type) : $type) . ' ' : '')
                . '$' . $property->getName());

            $properties[] = Helpers::formatDocComment((string) $property->getComment())
                . $def
                . (null === $property->getValue() && !$property->isInitialized() ? '' : ' = ' . $this->dump($property->getValue(), \strlen($def) + 3)) // 3 = ' = '
                . ';';
        }

        $methods = [];
        $methodDefinitions = $class->getMethods();
        ksort($methodDefinitions);
        foreach ($methodDefinitions as $method) {
            $methods[] = $this->printMethod($method, $namespace);
        }

        $members = array_filter([
            implode('', $traits),
            implode('', $consts),
            implode("\n", $properties),
            ($methods && $properties ? str_repeat("\n", $this->linesBetweenMethods - 1) : '')
            . implode(str_repeat("\n", $this->linesBetweenMethods), $methods),
        ]);

        return Strings::normalize(
                Helpers::formatDocComment($class->getComment() . "\n")
                . ($class->isAbstract() ? 'abstract ' : '')
                . ($class->isFinal() ? 'final ' : '')
                . ($class->getName() ? $class->getType() . ' ' . $class->getName() . ' ' : '')
                . ($class->getExtends() ? 'extends ' . implode(', ', array_map($resolver, (array) $class->getExtends())) . ' ' : '')
                . ($class->getImplements() ? 'implements ' . implode(', ', array_map($resolver, $class->getImplements())) . ' ' : '')
                . ($class->getName() ? "\n" : '') . "{\n"
                . ($members ? $this->indent(implode("\n", $members)) : '')
                . '}'
            ) . ($class->getName() ? "\n" : '');
    }

    /**
     * @param Closure|GlobalFunction|Method $function
     */
    protected function printParameters($function, ?PhpNamespace $namespace): string
    {
        $params = [];
        $list = $function->getParameters();
        foreach ($list as $param) {
            $variadic = $function->isVariadic() && $param === end($list);
            $type = $param->getType();
            $params[] = ($type ? ($param->isNullable() ? '?' : '') . ($this->resolveTypes && $namespace ? $namespace->unresolveName($type) : $type) . ' ' : '')
                . ($param->isReference() ? '&' : '')
                . ($variadic ? '...' : '')
                . '$' . $param->getName()
                . ($param->hasDefaultValue() && !$variadic ? ' = ' . $this->dump($param->getDefaultValue()) : '');
        }

        return \strlen($tmp = implode(', ', $params)) > (new Dumper())->wrapLength && \count($params) > 1
            ? "(\n" . $this->indentation . implode(",\n" . $this->indentation, $params) . "\n)"
            : "($tmp)";
    }

    /**
     * @param Closure|GlobalFunction|Method $function
     */
    protected function printReturnType($function, ?PhpNamespace $namespace): string
    {
        return $function->getReturnType()
            ? ': ' . ($function->isReturnNullable() ? '?' : '') . ($this->resolveTypes && $namespace ? $namespace->unresolveName($function->getReturnType()) : $function->getReturnType())
            : '';
    }
}
