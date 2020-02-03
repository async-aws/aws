<?php

declare(strict_types=1);

namespace AsyncAws\CodeGenerator\Generator;

use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\Closure;
use Nette\PhpGenerator\GlobalFunction;
use Nette\PhpGenerator\Helpers;
use Nette\PhpGenerator\Literal;
use Nette\PhpGenerator\Method;
use Nette\PhpGenerator\Parameter;
use Nette\PhpGenerator\PhpNamespace;
use Nette\PhpGenerator\Property;
use Nette\SmartObject;

/**
 * Generate Nette PhpNamespace from existing source class.
 *
 * This is a slightly modified version of \Nette\PhpGenerator\Factory
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
class ClassFactory
{
    use SmartObject;

    private static $cache = [];

    public static function fromExistingClass(string $class): PhpNamespace
    {
        if (isset(self::$cache[$class])) {
            return self::$cache[$class];
        }
        $factory = new self();

        return self::$cache[$class] = $factory->fromClassReflection(new \ReflectionClass($class));
    }

    public function fromClassReflection(\ReflectionClass $from): PhpNamespace
    {
        $namespace = new PhpNamespace($from->getNamespaceName());
        $filename = $from->getFileName();
        $rows = \file($filename);

        // Find Use statements
        foreach ($rows as $row) {
            if (false !== \strstr($row, 'class ' . $from->getName())) {
                // No use statements after this point
                break;
            }

            if (\preg_match('#use ([^;]+)( as [^;]+)?;#i', $row, $match)) {
                $namespace->addUse($match[1], $match[2] ?? null);
            }
        }

        $class = $from->isAnonymous()
            ? new ClassType()
            : new ClassType($from->getShortName(), $namespace);

        $namespace->add($class);
        $class->setType($from->isInterface() ? $class::TYPE_INTERFACE : ($from->isTrait() ? $class::TYPE_TRAIT : $class::TYPE_CLASS));
        $class->setFinal($from->isFinal() && $class->isClass());
        $class->setAbstract($from->isAbstract() && $class->isClass());

        $ifaces = $from->getInterfaceNames();
        foreach ($ifaces as $iface) {
            $ifaces = \array_filter($ifaces, function (string $item) use ($iface): bool {
                return !\is_subclass_of($iface, $item);
            });
        }
        $class->setImplements($ifaces);

        $class->setComment(Helpers::unformatDocComment((string) $from->getDocComment()));
        if ($from->getParentClass()) {
            $class->setExtends($from->getParentClass()->getName());
            $class->setImplements(\array_diff($class->getImplements(), $from->getParentClass()->getInterfaceNames()));
        }
        $props = $methods = [];
        foreach ($from->getProperties() as $prop) {
            if ($prop->isDefault() && $prop->getDeclaringClass()->getName() === $from->getName()) {
                $props[] = $this->fromPropertyReflection($prop);
            }
        }
        $class->setProperties($props);
        foreach ($from->getMethods() as $method) {
            if ($method->getDeclaringClass()->getName() === $from->getName()) {
                $methods[] = $this->fromMethodReflection($method);
            }
        }
        $class->setMethods($methods);
        $class->setConstants($from->getConstants());

        return $namespace;
    }

    public function fromMethodReflection(\ReflectionMethod $from): Method
    {
        $method = new Method($from->getName());
        $method->setParameters(\array_map([$this, 'fromParameterReflection'], $from->getParameters()));
        $method->setStatic($from->isStatic());
        $isInterface = $from->getDeclaringClass()->isInterface();
        $method->setVisibility(
            $from->isPrivate()
            ? ClassType::VISIBILITY_PRIVATE
            : ($from->isProtected() ? ClassType::VISIBILITY_PROTECTED : ($isInterface ? null : ClassType::VISIBILITY_PUBLIC))
        );
        $method->setFinal($from->isFinal());
        $method->setAbstract($from->isAbstract() && !$isInterface);

        $filename = $from->getFileName();
        $rows = \file($filename);
        $body = \implode('', \array_map(function ($r) {
            return trim($r, ' ');
        }, \array_slice($rows, $from->getStartLine() + 1, $from->getEndLine() - $from->getStartLine() - 2)));

        $method->setBody($from->isAbstract() ? null : \print_r($body, true));
        $method->setReturnReference($from->returnsReference());
        $method->setVariadic($from->isVariadic());
        $method->setComment(Helpers::unformatDocComment((string) $from->getDocComment()));
        if ($from->hasReturnType()) {
            $method->setReturnType($from->getReturnType()->getName());
            $method->setReturnNullable($from->getReturnType()->allowsNull());
        }

        return $method;
    }

    /**
     * @return GlobalFunction|Closure
     */
    public function fromFunctionReflection(\ReflectionFunction $from)
    {
        $function = $from->isClosure() ? new Closure() : new GlobalFunction($from->getName());
        $function->setParameters(\array_map([$this, 'fromParameterReflection'], $from->getParameters()));
        $function->setReturnReference($from->returnsReference());
        $function->setVariadic($from->isVariadic());
        if (!$from->isClosure()) {
            $function->setComment(Helpers::unformatDocComment((string) $from->getDocComment()));
        }
        if ($from->hasReturnType()) {
            $function->setReturnType($from->getReturnType()->getName());
            $function->setReturnNullable($from->getReturnType()->allowsNull());
        }

        return $function;
    }

    public function fromParameterReflection(\ReflectionParameter $from): Parameter
    {
        $param = new Parameter($from->getName());
        $param->setReference($from->isPassedByReference());
        $param->setType($from->hasType() ? $from->getType()->getName() : null);
        $param->setNullable($from->hasType() && $from->getType()->allowsNull());
        if ($from->isDefaultValueAvailable()) {
            $param->setDefaultValue($from->isDefaultValueConstant()
                ? new Literal($from->getDefaultValueConstantName())
                : $from->getDefaultValue());
            $param->setNullable($param->isNullable() && null !== $param->getDefaultValue());
        }

        return $param;
    }

    public function fromPropertyReflection(\ReflectionProperty $from): Property
    {
        $defaults = $from->getDeclaringClass()->getDefaultProperties();
        $prop = new Property($from->getName());
        $prop->setValue($defaults[$prop->getName()] ?? null);
        $prop->setStatic($from->isStatic());
        $prop->setVisibility(
            $from->isPrivate()
            ? ClassType::VISIBILITY_PRIVATE
            : ($from->isProtected() ? ClassType::VISIBILITY_PROTECTED : ClassType::VISIBILITY_PUBLIC)
        );
        if (\PHP_VERSION_ID >= 70400 && ($type = $from->getType())) {
            $prop->setType($type->getName());
            $prop->setNullable($type->allowsNull());
            $prop->setInitialized(\array_key_exists($prop->getName(), $defaults));
        }
        $prop->setComment(Helpers::unformatDocComment((string) $from->getDocComment()));

        return $prop;
    }
}
