# Heimdall
## _A simple class validator for fields._

[![Build Status](https://travis-ci.org/joemccann/dillinger.svg?branch=master)](https://travis-ci.org/joemccann/dillinger)

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