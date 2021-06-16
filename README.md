# Heimdall
## _A simple class validator for fields._

[![Codacy Badge](https://app.codacy.com/project/badge/Grade/a2e193903dbe4690bbff4e1b02ae8329)](https://www.codacy.com/gh/pablosanches/heimdall/dashboard?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=pablosanches/heimdall&amp;utm_campaign=Badge_Grade)
[![Build Status](https://travis-ci.org/pablosanches/heimdall.svg?branch=master)](https://travis-ci.org/pablosanches/heimdall)

## Features

- Validate a class using annotations

## Installation

Heimdall requires the composer installed.

Install the dependencies and devDependencies and start the server.

```json
"require": {
    "pablosanches/heimdall": "dev-master"
},
```

Then on you project directory...

```sh
composer install
composer update
```

## Usage

Heimdall is very simple to use.
You just need to define the validation rules in your class as annotations
```php
class SomeClass
{
    /**
     * @type string
     * @maxlength 10
     * @minlength 10
     * @required
     * @message Default failure message.
     */
    public $name;

    /**
     * @type email
     * @required
     * @message Email is required.
     */
    public $email;

    /**
     * @type chosen[M,F]
     * @required
     */
    public $gender;

    /**
     * @type phone
     * @required
     */
    public $phone;

    /**
     * @type date
     */
    public $birthday;

    /**
     * @type number
     */
    public $age;
}
```

So when you need to validate that this class you just call Heimdall to make sure everything is in compliance!

```php
    $someClass = new \SomeClass();

    $someClass->name = 'Pablo Sanches';
    $someClass->email = 'sanches.webmaster@gmail.com';
    $someClass->gender = 'M';
    $someClass->phone = '31971111540';
    $someClass->birthday = '23/12/1990';
    $someClass->age = 30;
    
    $result = Heimdall::validate($someClass);
    
    /* $results can come as an array with all the fields that failed or as true if everything is right. */
```

### Enjoy it! ;)