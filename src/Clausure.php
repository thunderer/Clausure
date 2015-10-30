<?php
namespace Thunder\ClosureFactory;

class Clausure
{
    public static function getProperty($property)
    {
        return function($object) use($property) {
            $ref = new \ReflectionObject($object);
            if(!$ref->hasProperty($property)) {
                throw new \InvalidArgumentException();
            }
            $prop = $ref->getProperty($property);
            $prop->setAccessible(true);

            return $prop->getValue($object);
        };
    }

    public static function callMethod($method)
    {
        return function($object) use($method) {
            if(!method_exists($object, $method)) {
                throw new \BadMethodCallException();
            }

            return call_user_func_array([$object, $method], []);
        };
    }

    public static function testProperty($property, $value)
    {
        return function($object) use($property, $value) {
            $ref = new \ReflectionObject($object);
            if(!$ref->hasProperty($property)) {
                throw new \InvalidArgumentException();
            }
            $prop = $ref->getProperty($property);
            $prop->setAccessible(true);

            return $value === $prop->getValue($object);
        };
    }

    public static function testMethod($method, $value)
    {
        return function($object) use($method, $value) {
            if(!method_exists($object, $method)) {
                throw new \BadMethodCallException();
            }

            return $value === call_user_func_array([$object, $method], []);
        };
    }

    public static function filterProperty(array $items, $property, $value)
    {
        return array_filter($items, static::testProperty($property, $value));
    }

    public static function filterMethodCall(array $items, $method, $value)
    {
        $test = static::callMethod($method);

        return array_filter($items, function($item) use($test, $value) {
            return $value === $test($item);
        });
    }

    public static function mapProperty(array $items, $property)
    {
        return array_map(static::getProperty($property), $items);
    }

    public static function mapMethodCall(array $items, $method)
    {
        return array_map(static::callMethod($method), $items);
    }
}
