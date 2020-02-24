<?php

namespace AsyncAws\Symfony\Bundle\VarDumper;

use AsyncAws\Core\Result;
use AsyncAws\Core\Waiter;
use Symfony\Component\VarDumper\Caster\Caster;
use Symfony\Component\VarDumper\Cloner\Stub;

class ResultCaster
{
    public static function castResult(Result $result, array $a, Stub $stub, bool $isNested)
    {
        foreach (["\0AsyncAws\\Core\\Result\0httpClient", "\0AsyncAws\\Core\\Result\0response", "\0AsyncAws\\Core\\Result\0prefetchResults", Caster::PREFIX_PROTECTED . 'awsClient', Caster::PREFIX_PROTECTED . 'input'] as $k) {
            if (\array_key_exists($k, $a)) {
                unset($a[$k]);
                ++$stub->cut;
            }
        }

        return $a;
    }

    public static function castWaiter(Waiter $waiter, array $a, Stub $stub, bool $isNested)
    {
        foreach (["\0AsyncAws\\Core\\Waiter\0httpClient", "\0AsyncAws\\Core\\Waiter\0response", Caster::PREFIX_PROTECTED . 'awsClient', Caster::PREFIX_PROTECTED . 'input'] as $k) {
            if (\array_key_exists($k, $a)) {
                unset($a[$k]);
                ++$stub->cut;
            }
        }

        return $a;
    }
}
