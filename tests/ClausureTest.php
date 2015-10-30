<?php
namespace Thunder\ClosureFactory\Tests;

use Thunder\ClosureFactory\Clausure;
use Thunder\ClosureFactory\Tests\Fake\FakeClass;

class ClausureTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider provideClosures
     */
    public function testClosure($callable, $object, $expected)
    {
        $this->assertSame($expected, $callable($object));
    }

    public function provideClosures()
    {
        return array(
            array(Clausure::getProperty('id'), new FakeClass(2, ''), 2),
            array(Clausure::getProperty('name'), new FakeClass(1, 'xxx'), 'xxx'),
            array(Clausure::callMethod('getName'), new FakeClass(1, 'xxx'), 'xxx'),
            array(Clausure::testProperty('id', 1), new FakeClass(1, 'xxx'), true),
            array(Clausure::testProperty('name', 'aaa'), new FakeClass(1, 'xxx'), false),
            array(Clausure::testMethod('getName', 'aaa'), new FakeClass(1, 'xxx'), false),
        );
    }

    public function testOperations()
    {
        $fake0 = new FakeClass(1, 'a');
        $fake1 = new FakeClass(2, 'a');
        $fake2 = new FakeClass(1, 'b');
        $fakes = array($fake0, $fake1, $fake2);

        $this->assertSame([0 => $fake0, 2 => $fake2], Clausure::filterProperty($fakes, 'id', 1));
        $this->assertSame([0 => $fake0, 1 => $fake1], Clausure::filterProperty($fakes, 'name', 'a'));
        $this->assertSame([0 => $fake0, 1 => $fake1], Clausure::filterMethodCall($fakes, 'getName', 'a'));
        $this->assertSame([1, 2, 1], Clausure::mapProperty($fakes, 'id'));
        $this->assertSame(['a', 'a', 'b'], Clausure::mapMethodCall($fakes, 'getName'));
    }

    /**
     * @dataProvider provideExceptions
     */
    public function testExceptions($callable, $object, $exception)
    {
        $this->setExpectedException($exception);
        $callable($object);
    }

    public function provideExceptions()
    {
        return array(
            array(Clausure::getProperty('invalid'), new FakeClass(2, ''), 'InvalidArgumentException'),
            array(Clausure::callMethod('getId'), new FakeClass(1, 'xxx'), 'BadMethodCallException'),
            array(Clausure::testProperty('invalid', null), new FakeClass(1, 'xxx'), 'InvalidArgumentException'),
            array(Clausure::testMethod('invalid', null), new FakeClass(1, 'xxx'), 'BadMethodCallException'),
        );
    }

    public function testTestPropertyException()
    {
        $this->setExpectedException('BadMethodCallException');
        Clausure::filterMethodCall(array(new FakeClass(1, 'x')), 'getId', 1);
    }
}
