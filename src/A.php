<?php
namespace Example;

use Closure;

class A {

    public function foo(Closure $closure)
    {
        return $closure();
    }
}
