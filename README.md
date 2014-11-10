[![Build Status](https://travis-ci.org/komex/influence.svg?branch=master)](https://travis-ci.org/komex/influence)
[![Code Coverage](https://scrutinizer-ci.com/g/komex/influence/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/komex/influence/?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/komex/influence/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/komex/influence/?branch=master)
[![Latest Stable Version](https://poser.pugx.org/komex/influence/v/stable.svg)](https://packagist.org/packages/komex/influence)
[![License](https://poser.pugx.org/komex/influence/license.svg)](https://packagist.org/packages/komex/influence)
[![Gitter](https://badges.gitter.im/Join Chat.svg)](https://gitter.im/komex/influence)

# Influence

**Influence** is the PHP library gives you ability to mock any objects and static classes. Works only if you use [Composer autoloader](htttp://getcomposer.org/). This library very useful for testing your code.

## Requirements

* PHP 5.4 or higher
* SPL extension
* Tokenizer extension
* Composer loader

## Installation

To add Influence as a local, per-project dependency to your project, simply add a dependency on `komex/influence` to your project's `composer.json` file.
Here is a minimal example of a `composer.json` file that just defines a develop dependency on Influence:

```json
{
    "require-dev": {
        "komex/influence": "1.1.*"
    }
}
```

## Usage

**Influence** must be injected as early as possible. If you are using unit test framework like [unteist](https://github.com/komex/unteist) or [PHPUnit](https://phpunit.de/) the best way to do this is include autoload and influence in bootstrap file.

```php
require 'vendor/autoload.php';
Influence\Influence::affect();
```

Since this moment you are able to mock any objects and static classes. By default, all objects behave as usual. You need to configure behavior of each object or class you need to control.

### Manage objects

Let imagine we have a simple class A:

```php
class A
{
    public function sum($a)
    {
        return $a + $this->rand(0, $a);
    }
    private fuction rand($min, $max)
    {
        return rand($min, $max);
    }
}
```

#### Custom method behavior.

So, if we create an object of class ```A``` we can invoke only ```sum()``` method and control only ```$a``` and never know result of our operation.

```php
$a = new A();
echo $a->sum(1); // ??
echo $a->sum(7); // ??
````

But with **Influence** you can simply test this code. Just specify the behavior of ```sum()``` like this:

```php
$a = new A();
$method = Influence\RemoteControl::controlObject($a)->get('rand');
$method->setValue(new ReturnValue(1));
echo $a->sum(1); // 2
echo $a->sum(7); // 8
$method->setValue();
echo $a->sum(1); // ??
echo $a->sum(7); // ??
```

#### Log method calls

If you don't need to set custom method behavior, but want to know how many times method was called and with what arguments.

```php
$a = new A();
$method = Influence\RemoteControl::controlObject($a)->get('rand');
$method->setLog(true);
echo $a->sum(1); // ??
echo $a->sum(7); // ??
var_dump($method->getLogs()); // [ [0, 1], [0, 7] ]
```

## License

<p><span xmlns:dct="http://purl.org/dc/terms/" property="dct:title">Influence</span> by <a xmlns:cc="http://creativecommons.org/ns#" href="https://github.com/komex" property="cc:attributionName" rel="cc:attributionURL">Andrey Kolchenko</a> is licensed under a <a rel="license" href="http://creativecommons.org/licenses/by-sa/4.0/">Creative Commons Attribution-ShareAlike 4.0 International License</a>.</p>
<p><a rel="license" href="http://creativecommons.org/licenses/by-sa/4.0/"><img alt="Creative Commons License" style="border-width:0" src="https://i.creativecommons.org/l/by-sa/4.0/88x31.png" /></a></p>