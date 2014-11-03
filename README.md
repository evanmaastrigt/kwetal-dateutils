Kwetal-DateUtils
================

A number of utility methods for handling date related operations, wrapped in a object-oriented interface

Installation
------------

The recommended way to install DateUtils is through composer.

Just create a `composer.json` file for your project:

``` json
{
    "require": {
        "kwetal/dateutils": "dev-master"
    }
}
```

And run these two commands to install it:

``` bash
$ curl -sS https://getcomposer.org/installer | php
$ composer install
```

Now you can add the autoloader, and you will have access to the library:

``` php
<?php

require 'vendor/autoload.php';
```

Unit tests
----------

After installing, from the root project dir run
```bash
$ vendor/phpunit/phpunit/phpunit
```
