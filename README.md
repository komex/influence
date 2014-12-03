[![Build Status](https://travis-ci.org/komex/influence.svg?branch=master)](https://travis-ci.org/komex/influence)
[![Code Coverage](https://scrutinizer-ci.com/g/komex/influence/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/komex/influence/?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/komex/influence/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/komex/influence/?branch=master)

About project
========

**Influence** is a testing instrument for programmers and quality engineers that helps them to test PHP projects
by giving ability to mock and stub any objects, classes and new instances in realtime.

## Requirements

* PHP 5.4 or higher
* SPL extension
* Tokenizer extension
* It works only with projects which uses [Composer](http://getcomposer.org/).

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

Just add one line of code before all tests to get ability to do anything with all not build-in objects and static classes:

```php
Influence\Influence::affect();
```

**Influence** must be injected as early as possible. You may control objects, classes and new instances loaded only after call ```affect()``` method. If you are using unit test framework like [unteist](https://github.com/komex/unteist) or [PHPUnit](https://phpunit.de/) the best way to do this is include influence in bootstrap file.

Since this moment you are able to mock any objects and static classes. By default, all objects behave as usual. You need to configure behavior of each object or class you need to control with ```Influence\RemoteControlUtils```.

## Authors

This project was founded by [Andrey Kolchenko](https://github.com/komex) in October of 2014.

## Support or Contact

Having trouble with Influence? Contact andrey@kolchenko.me and we’ll help you in the short time.

## License

<p><span xmlns:dct="http://purl.org/dc/terms/" property="dct:title">Influence</span> by <a xmlns:cc="http://creativecommons.org/ns#" href="https://github.com/komex" property="cc:attributionName" rel="cc:attributionURL">Andrey Kolchenko</a> is licensed under a <a rel="license" href="http://creativecommons.org/licenses/by-sa/4.0/">Creative Commons Attribution-ShareAlike 4.0 International License</a>.</p>
<p><a rel="license" href="http://creativecommons.org/licenses/by-sa/4.0/"><img alt="Creative Commons License" style="border-width:0" src="https://i.creativecommons.org/l/by-sa/4.0/88x31.png" /></a></p>
