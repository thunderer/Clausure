<?php
namespace Thunder\Clausure;

/**
 * Think of each method as "returning closure that should (method name)".
 *
 * @author Tomasz Kowalczyk <tomasz@kowalczyk.cc>
 */
final class Clausure
{
    public static function property($property)
    {
        return property($property);
    }

    public static function method($method)
    {
        return method($method);
    }

    public static function propertyEquals($property, $value)
    {
        return propertyEquals($property, $value);
    }

    public static function methodEquals($method, $value)
    {
        return methodEquals($method, $value);
    }

    public static function filterProperty(array $items, $property, $value)
    {
        return filterProperty($items, $property, $value);
    }

    public static function filterMethod(array $items, $method, $value)
    {
        return filterMethod($items, $method, $value);
    }

    public static function mapProperty(array $items, $property)
    {
        return mapProperty($items, $property);
    }

    public static function mapMethod(array $items, $method)
    {
        return mapMethod($items, $method);
    }
}
