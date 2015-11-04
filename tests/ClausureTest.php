<?php
namespace Thunder\Clausure\Tests;

use Thunder\Clausure\Clausure;
use Thunder\Clausure\Tests\Fake\FakeClass;
use function Thunder\Clausure\filterProperty;

/**
 * @author Tomasz Kowalczyk <tomasz@kowalczyk.cc>
 */
final class ClausureTest extends \PHPUnit_Framework_TestCase
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
            array(Clausure::property('id'), new FakeClass(2, ''), 2),
            array(Clausure::property('name'), new FakeClass(1, 'xxx'), 'xxx'),
            array(Clausure::method('getName'), new FakeClass(1, 'xxx'), 'xxx'),
            array(Clausure::propertyEquals('id', 1), new FakeClass(1, 'xxx'), true),
            array(Clausure::propertyEquals('name', 'aaa'), new FakeClass(1, 'xxx'), false),
            array(Clausure::methodEquals('getName', 'aaa'), new FakeClass(1, 'xxx'), false),
        );
    }

    public function testOperations()
    {
        $fake0 = new FakeClass(1, 'a');
        $fake1 = new FakeClass(2, 'a');
        $fake2 = new FakeClass(1, 'b');
        $fakes = array($fake0, $fake1, $fake2);

        $this->assertSame([0 => $fake0, 2 => $fake2], Clausure::filterProperty($fakes, 'id', 1));
        $this->assertSame([0 => $fake0, 2 => $fake2], filterProperty($fakes, 'id', 1));
        $this->assertSame([0 => $fake0, 1 => $fake1], Clausure::filterProperty($fakes, 'name', 'a'));
        $this->assertSame([0 => $fake0, 1 => $fake1], Clausure::filterMethod($fakes, 'getName', 'a'));
        $this->assertSame([1, 2, 1], Clausure::mapProperty($fakes, 'id'));
        $this->assertSame(['a', 'a', 'b'], Clausure::mapMethod($fakes, 'getName'));
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
            array(Clausure::property('invalid'), new FakeClass(2, ''), 'InvalidArgumentException'),
            array(Clausure::method('getId'), new FakeClass(1, 'xxx'), 'BadMethodCallException'),
            array(Clausure::propertyEquals('invalid', null), new FakeClass(1, 'xxx'), 'InvalidArgumentException'),
            array(Clausure::methodEquals('invalid', null), new FakeClass(1, 'xxx'), 'BadMethodCallException'),
        );
    }

    public function testTestPropertyException()
    {
        $this->setExpectedException('BadMethodCallException');
        Clausure::filterMethod(array(new FakeClass(1, 'x')), 'getId', 1);
    }
}
