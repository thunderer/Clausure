<?php
namespace Thunder\Clausure;

function property($property)
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

function method($method)
{
    return function($object) use($method) {
        if(!method_exists($object, $method)) {
            throw new \BadMethodCallException();
        }

        return call_user_func_array([$object, $method], []);
    };
}

function propertyEquals($property, $value)
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

function methodEquals($method, $value)
{
    return function($object) use($method, $value) {
        if(!method_exists($object, $method)) {
            throw new \BadMethodCallException();
        }

        return $value === call_user_func_array([$object, $method], []);
    };
}

function filterProperty(array $items, $property, $value)
{
    return array_filter($items, propertyEquals($property, $value));
}

function filterMethod(array $items, $method, $value)
{
    $test = method($method);

    return array_filter($items, function($item) use($test, $value) {
        return $value === $test($item);
    });
}

function mapProperty(array $items, $property)
{
    return array_map(property($property), $items);
}

function mapMethod(array $items, $method)
{
    return array_map(method($method), $items);
}
