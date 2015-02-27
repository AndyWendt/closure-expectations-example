<?php
namespace Example;

class B {

    /**
     * @var A
     */
    private $a;

    public function __construct(A $a)
    {
        $this->a = $a;
    }

    /**
     * @param int $number
     */
    public function bar($number)
    {
        return $this->a->foo(
            function () use ($number) {
                return $number * 5;
            }
        );
    }

}
