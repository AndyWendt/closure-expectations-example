<?php
namespace Example;

use Mockery as m;

class ClosureExampleTest extends \PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        m::close();
    }

    /**
     * @test
     */
    public function you_cannot_set_an_expectation_for_a_closure_by_passing_one()
    {
        $this->setExpectedException('Mockery\Exception\NoMatchingExpectationException');

        $a = m::mock('Example\A');
        $number = 5;
        // This is the method that is expecting a \Closure object to be passed.
        // It will raise an exception because, even though it is pretty much identical to the one that we use,
        // it is still a distinct \Closure instance.
        $a->shouldReceive('foo')->with(
            function () use ($number) {
                return $number * 5;
            }
        )->andReturn(25);

        $b = new B($a);
        // ::bar() is calling foo()
        $this->assertSame(25, $b->bar($number));
    }

    /**
     * http://php.net/manual/en/class.closure.php
     * http://php.net/manual/en/functions.anonymous.php
     * @test
     */
    public function anonymous_functions_are_closure_objects()
    {
        $anonymousFunction = function () {
            echo 'I am a \\Closure';
        };
        $this->assertSame("Closure", get_class($anonymousFunction));
    }

    /**
     * @test
     */
    public function closures_created_identically_will_never_have_the_same_object_hash()
    {
        $y = function ($number) {
            return 5 * $number;
        };

        $z = function ($number) {
            return 5 * $number;
        };

        // the only way to get these to match (that I know of) is to use a singleton
        $this->assertNotSame(
            spl_object_hash($y),
            spl_object_hash($z)
        );
    }

    /**
     * @test
     */
    public function it_can_expect_a_bool_from_a_closure_and_we_can_assert_the_desired_return_value()
    {
        $a = m::mock('Example\A');

        // this is the method that is expecting a \Closure class to be passed
        $a->shouldReceive('foo')->with(
            m::on(
                function ($closure) {

                    // this is so that we can know if the returned value from the \Closure is what we want.
                    $this->assertSame(25, $closure());

                    // you must return a bool here so that Mockery knows that the expectation passed
                    return is_callable($closure);
                }
            )
        )->andReturn(25);

        $b = new B($a);

        // have bar() call foo()
        $b->bar(5);
    }
}
