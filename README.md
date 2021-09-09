[![Build Status](https://github.com/retailcrm/url-validator/workflows/CI/badge.svg)](https://github.com/retailcrm/url-validator/actions)
[![Coverage](https://img.shields.io/codecov/c/gh/retailcrm/url-validator/master.svg?logo=codecov&logoColor=white)](https://codecov.io/gh/retailcrm/url-validator)
[![Latest stable](https://img.shields.io/packagist/v/retailcrm/url-validator.svg)](https://packagist.org/packages/retailcrm/url-validator)
[![PHP from Packagist](https://img.shields.io/packagist/php-v/retailcrm/url-validator.svg?logo=php&logoColor=white)](https://packagist.org/packages/retailcrm/url-validator)


# RetailCRM URL Validator

This validator will help you validate system URLs in your project using [`symfony/validator`](https://packagist.org/packages/symfony/validator).

# Table of contents

* [Requirements](#requirements)
* [Installation](#installation)
* [Usage](#usage)

## Requirements

* PHP 7.3 and above
* PHP's JSON support
* `symfony/validator`

## Installation

Follow those steps to install the library:

1. Download and install [Composer](https://getcomposer.org/download/) package manager.
2. Install the library from the Packagist by executing this command:
```bash
composer require retailcrm/url-validator:"^1"
```
3. Include the autoloader if it's not included, or you didn't use Composer before.
```php
require 'path/to/vendor/autoload.php';
```

Replace `path/to/vendor/autoload.php` with the correct path to Composer's `autoload.php`.

## Usage

You have to use Symfony Validator to work with this library.
Please refer to the [official documentation for the `symfony/validator`](https://symfony.com/doc/current/components/validator.html) to learn how to use it.
If you want to use `symfony/validator` with Symfony framework - you should use [this documentation](https://symfony.com/doc/current/validation.html).

After ensuring that you're using `symfony/validator` you can just append the `@CrmUrl()` annotation to the DTO entity field that contains system URL.

After that validator's `validate` call on this DTO will generate the proper violation messages for incorrect URLs.

Here's an example of the DTO (*note:* `@Assert\Url()` is optional):

```php
<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;
use RetailCrm\Validator\CrmUrl;

class Connection
{
    /**
     * @var string
     * 
     * @Assert\NotBlank()
     * @Assert\Url()
     * @CrmUrl()
     */
    public $apiUrl;
}
```

And below you can find a complete example of usage (*note:* it requires `doctrine/annotations` and `symfony/cache` to work properly).

**Connection.php**
```php
<?php

namespace App;

use Symfony\Component\Validator\Constraints as Assert;
use RetailCrm\Validator\CrmUrl;

class Connection
{
    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @Assert\Url()
     * @CrmUrl()
     */
    public $apiUrl;

    public function __construct(string $apiUrl)
    {
        $this->apiUrl = $apiUrl;
    }
}
```

**app.php**
```php
namespace App;

// We assume that `app.php` is stored within a directory that is being autoloaded by Composer.
require __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\Validator\Validation;

$validator = Validation::createValidatorBuilder()
    ->enableAnnotationMapping(true)
    ->addDefaultDoctrineAnnotationReader()
    ->getValidator();
$violations = $validator->validate(new Connection('https://test.retailcrm.pro'));

if (0 !== count($violations)) {
    foreach ($violations as $violation) {
        echo $violation->getMessage();
    }
}
```
