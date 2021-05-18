<?php

namespace PabloSanches\Heimdall\Tests;

use PabloSanches\Heimdall;

class HeimdallTest extends \PHPUnit_Framework_TestCase
{
    public function setUp() {}

    public function testInitialize()
    {
        $someClass = new \SomeClass();

        // Age is required (FALSE - dont have a @required option) 
        $someClass->name = 'PabloSanch';
        $someClass->email = 'sanches.webmaster@gmail.com';
        $someClass->gender = 'M';
        $someClass->phone = '(31) 2552-6196';

        $result = Heimdall::validate($someClass);

        $this->assertTrue($result);

        return $someClass;
    }

    /**
     * Test the name property
     * @depends testInitialize
     */
    public function testName($someClass)
    {
        // Name is required
        $someClass->name = '';
        $result = Heimdall::validate($someClass);
        $this->assertTrue(array_key_exists('required', $result['name']));

        // Name has minlength 5
        $someClass->name = 'Pablo';
        $result = Heimdall::validate($someClass);
        $this->assertTrue($result);

        // Name has minlength 5
        $someClass->name = 'Pa';
        $result = Heimdall::validate($someClass);
        $this->assertTrue(array_key_exists('minlength', $result['name']));

                // Name has maxlength 13
        $someClass->name = 'Pablo R Sanches';
        $result = Heimdall::validate($someClass);
        $this->assertTrue(array_key_exists('maxlength', $result['name']));

        // Name has maxlength 13
        $someClass->name = 'Pablo Sanches';
        $result = Heimdall::validate($someClass);
        $this->assertTrue($result);

    }

    /**
     * Test the email property
     * @depends testInitialize
     */
    public function testEmail($someClass)
    {
        // Name is required
        $someClass->email = '';
        $result = Heimdall::validate($someClass);
        $this->assertTrue(array_key_exists('required', $result['email']));

        // Email format
        $someClass->email = 'pablo.com.br';
        $result = Heimdall::validate($someClass);
        $this->assertTrue(array_key_exists('type', $result['email']));

        // Email correct
        $someClass->email = 'sanches.webmaster@gmail.com';
        $result = Heimdall::validate($someClass);
    
        $this->assertTrue($result);
    }

    /**
     * Test the gender property
     * @depends testInitialize
     */
    public function testGender($someClass)
    {
        // Gender is required
        $someClass->gender = '';
        $result = Heimdall::validate($someClass);
        $this->assertTrue(array_key_exists('required', $result['gender']));

        // Gender invalid option
        $someClass->gender = 'P';
        $result = Heimdall::validate($someClass);
        $this->assertTrue(array_key_exists('type', $result['gender']));

        // Gender valid option
        $someClass->gender = 'M';
        $result = Heimdall::validate($someClass);
        $this->assertTrue($result);
    }

    /**
     * Test the phone property
     * @depends testInitialize
     */
    public function testPhone($someClass)
    {
        // Phone is required
        $someClass->phone = '';
        $result = Heimdall::validate($someClass);
        $this->assertTrue(array_key_exists('required', $result['phone']));

        // Phone invalid option
        $someClass->phone = '31585380';
        $result = Heimdall::validate($someClass);
        $this->assertTrue(array_key_exists('type', $result['phone']));

        // Phone valid option
        $someClass->phone = '11111111111';
        $result = Heimdall::validate($someClass);
        $this->assertTrue($result);
    }

    /**
     * Test the birthday property
     * @depends testInitialize
     */
    public function testBirthday($someClass)
    {
        // Date is required (FALSE - dont have a @required option) 
        $someClass->birthday = '';
        $result = Heimdall::validate($someClass);
        $this->assertTrue($result);

        // Date invalid option
        $someClass->birthday = '23-12-1990';
        $result = Heimdall::validate($someClass);
        $this->assertTrue(array_key_exists('type', $result['birthday']));

        // Date valid option
        $someClass->birthday = '23/12/1990';
        $result = Heimdall::validate($someClass);
        $this->assertTrue($result);
    }

    /**
     * Test the aget property
     * @depends testInitialize
     */
    public function testAge($someClass)
    {
        // Age is required (FALSE - dont have a @required option) 
        $someClass->age = '';
        $result = Heimdall::validate($someClass);
        $this->assertTrue($result);

        // Age as a string
        $someClass->age = 'One';
        $result = Heimdall::validate($someClass);
        $this->assertTrue(array_key_exists('type', $result['age']));

        // Age as number
        $someClass->age = 30;
        $result = Heimdall::validate($someClass);
        $this->assertTrue($result);

        // Age as number (string)
        $someClass->age = '30';
        $result = Heimdall::validate($someClass);
        $this->assertTrue($result);
    }
}