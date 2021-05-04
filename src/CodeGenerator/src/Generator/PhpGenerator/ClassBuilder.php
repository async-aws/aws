<?php

declare(strict_types=1);

namespace AsyncAws\CodeGenerator\Generator\PhpGenerator;

use AsyncAws\CodeGenerator\Generator\Naming\ClassName;
use Nette\PhpGenerator\Constant;
use Nette\PhpGenerator\Method;
use Nette\PhpGenerator\PhpNamespace;
use Nette\PhpGenerator\Property;

/**
 * Wrapper for Nette PhpNamespace and ClassType.
 *
 * @author Jérémy Derussé <jeremy@derusse.com>
 */
class ClassBuilder
{
    private $namespace;

    private $class;

    private $className;

    public function __construct(PhpNamespace $namespace)
    {
        $this->namespace = $namespace;
        $classes = $namespace->getClasses();
        $this->class = $classes[array_key_first($classes)];
        $this->className = ClassName::create($this->namespace->getName(), $this->class->getName());
    }

    public function getClassName(): ClassName
    {
        return $this->className;
    }

    public function addUse(string $name): self
    {
        $this->namespace->addUse($name);

        return $this;
    }

    public function setExtends($names): self
    {
        $this->class->setExtends($names);

        return $this;
    }

    public function addExtend(string $name): self
    {
        $this->class->addExtend($name);

        return $this;
    }

    public function setImplement($names): self
    {
        $this->class->setImplement($names);

        return $this;
    }

    public function addImplement(string $name): self
    {
        $this->class->addImplement($name);

        return $this;
    }

    public function setFinal(bool $state = true): self
    {
        $this->class->setFinal($state);

        return $this;
    }

    public function addConstant(string $name, $value): Constant
    {
        return $this->class->addConstant($name, $value);
    }

    public function addMethod(string $name): Method
    {
        return $this->class->addMethod($name);
    }

    public function getMethod(string $name): Method
    {
        return $this->class->getMethod($name);
    }

    public function hasMethod(string $name): bool
    {
        return $this->class->hasMethod($name);
    }

    public function setMethods(array $methods): self
    {
        $this->class->setMethods($methods);

        return $this;
    }

    public function addProperty(string $name): Property
    {
        return $this->class->addProperty($name);
    }

    public function addComment(string $val): self
    {
        $this->class->addComment($val);

        return $this;
    }

    public function hasProperty(string $name): bool
    {
        return $this->class->hasProperty($name);
    }

    public function build(): PhpNamespace
    {
        return $this->namespace;
    }
}
