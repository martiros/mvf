<?php

use UIS\Mvf\ValidationManager;
use UIS\Mvf\ValidationError;

class TypeUsernameTest extends PHPUnit_Framework_TestCase
{
    public function testValidationSuccess()
    {
        $validationRules = ['username' => ['type' => 'username']];
        $data = ['username' => 'martiros.aghajanyan'];
        $validator = new ValidationManager($data, $validationRules);
        $this->assertTrue($validator->validate()->isValid());
    }

    public function testIsValidationFailForNotAllowedChars()
    {
        $validationRules = ['username' => ['type' => 'username']];
        $data = ['username' => 'martiros. aghajanyan'];
        $validator = new ValidationManager($data, $validationRules);
        $this->assertFalse($validator->validate()->isValid());
    }

    public function testIsValidationFailForNotValidFirstChar()
    {
        $validationRules = ['username' => ['type' => 'username']];
        $data = ['username' => '.martiros.aghajanyan'];
        $validator = new ValidationManager($data, $validationRules);
        $this->assertFalse($validator->validate()->isValid());
    }

    public function testIsValidationFailForNotValidEndChar()
    {
        $validationRules = ['username' => ['type' => 'username']];
        $data = ['username' => 'martiros.aghajanyan.'];
        $validator = new ValidationManager($data, $validationRules);
        $this->assertFalse($validator->validate()->isValid());
    }

    public function testIsValidationFailForPeriodsPunctuation()
    {
        $validationRules = ['username' => ['type' => 'username']];
        $data = ['username' => 'martiros..aghajanyan'];
        $validator = new ValidationManager($data, $validationRules);
        $this->assertFalse($validator->validate()->isValid());
    }
}
