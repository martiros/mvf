<?php

use \UIS\Mvf\Util;

class TestUtil extends PHPUnit_Framework_TestCase
{
    public function testInt()
    {
        $this->assertTrue(Util::isInt('0'));
        $this->assertTrue(Util::isInt(0));
        $this->assertTrue(Util::isInt(5));
        $this->assertTrue(Util::isInt('5'));
        $this->assertTrue(Util::isInt(1.0));
        $this->assertFalse(Util::isInt('05'));
        $this->assertFalse(Util::isInt('1.0'));
        $this->assertFalse(Util::isInt(5.5));
        $this->assertFalse(Util::isInt('5.5'));
    }

    public function testDigit()
    {
        $this->assertTrue(Util::isDigit('55'));
        $this->assertTrue(Util::isDigit('01555'));
        $this->assertFalse(Util::isDigit('x01555'));
        $this->assertFalse(Util::isDigit('5.5'));
    }

    public function testEmail()
    {
        $this->assertTrue(Util::isEmail('martiros.aghajanyan@gmail.com'));
        $this->assertFalse(Util::isEmail('martirosaghajanyangmail.com'));
        $this->assertFalse(Util::isEmail('martirosaghajanyan@gmailcom'));
    }

    public function testDate()
    {
        $this->assertTrue(Util::isDate('2015-02-28')); // Valid date
        $this->assertFalse(Util::isDate('22014-02-28')); // Invalid date
        $this->assertFalse(Util::isDate('2015-02-280')); // Invalid date
        $this->assertFalse(Util::isDate('2015-02-29')); // There is no such date
        $this->assertFalse(Util::isDate('0000-00-00')); // There is no such date
    }

    public function testUrl()
    {
        $this->assertTrue(Util::isUrl('http://localhost/index.php'));
        $this->assertTrue(Util::isUrl('https://www.google.com/'));
        $this->assertTrue(Util::isUrl('https://www.google.com/fffff'));
        $this->assertFalse(Util::isUrl('www.google.com'));
    }
}