# Clausure

[![License](https://poser.pugx.org/thunderer/shortcode/license.svg)](https://packagist.org/packages/thunderer/clausure)

Clausure is an utility library for generating common closures useful in `array_map()`, `array_filter()` and alike.

## Requirements

No required dependencies, only PHP >=5.3

## Installation

This library is available in Composer as `thunderer/clausure`, to install it execute:

```
composer require thunderer/clausure
```

or manually update your `composer.json` with:

```
(...)
"require": {
    "thunderer/clausure": "dev-master"
}
(...)
```

and run `composer install` or `composer update` afterwards.

## Usage

The whole library consists of one class, `Clausure`, which has several static methods. It can be used in two ways:

* create necessary closure and pass it into desired function,
* execute desired operation with closure being created inside.

Assume that there is a class `Data`:

```php
class Data
{
    private $id;
    private $name;

    public function __construct($id, $name)
    {
        $this->id = $id;
        $this->name = $name;
    }

    public function getId() { return $this->id; }
    public function getName() { return $this->name; }
}
```

It can be then processed as shown below:

```php
use Thunder\Clausure\Clausure;

$data0 = new Data('aaa', 123);
$data1 = new Data('xxx', 123);
$data2 = new Data('aaa', 456);

$items = array($data0, $data1, $data2);

// just get closures and pass them to functions:
$onlyAaa = array_filter($items, Clausure::testProperty('name', 'aaa'));
$only123 = array_filter($items, Clausure::testMethod($items, 'getValue'));
$names = array_map(Clausure::getProperty('name'), $items);
$values = array_map(Clausure::callMethod('getValue'), $items);

// execute desired operation with closure created inside:
$onlyAaa = Clausure::filterProperty($items, 'name', 'aaa');
$only123 = Clausure::filterMethodCall($items, 'getValue', 123);
$names = Clausure::mapProperty($items, 'name');
$values = Clausure::mapMethodCall($items, 'getValue');
```

## License

See LICENSE file in the main directory of this library.
