<?php

use \UIS\Mvf\ValidationManager;

class TypeDigitTest extends PHPUnit_Framework_TestCase
{
    public function testValidData()
    {
        $validationRules = array(
            'zip' => array(
                'type' => 'digit',
            )
        );
        $validData = array(
            'zip' => '19881'
        );
        $validator = new ValidationManager($validData, $validationRules);
        $this->assertTrue($validator->validate()->isValid());
    }

    public function testInvalidData()
    {
        $validationRules = array(
            'zip' => array(
                'type' => 'digit',
            )
        );
        $validData = array(
            'zip' => 'fvfdv'
        );
        $validator = new ValidationManager($validData, $validationRules);
        $this->assertFalse($validator->validate()->isValid());
    }

    public function testValidMaxLength()
    {
        $validationRules = array(
            'zip' => array(
                'type' => 'digit',
                'params' => array(
                    'max_length' => 6
                )
            )
        );
        $validData = array(
            'zip' => '015518'
        );
        $validator = new ValidationManager($validData, $validationRules);
        $this->assertTrue($validator->validate()->isValid());
    }

    public function testInvalidMaxLength()
    {
        $validationRules = array(
            'zip' => array(
                'type' => 'digit',
                'params' => array(
                    'max_length' => 4
                )
            )
        );
        $validData = array(
            'zip' => '015518'
        );
        $validator = new ValidationManager($validData, $validationRules);
        $this->assertFalse($validator->validate()->isValid());
    }

    public function testValidMinLength()
    {
        $validationRules = array(
            'zip' => array(
                'type' => 'digit',
                'params' => array(
                    'min_length' => 4
                )
            )
        );
        $validData = array(
            'zip' => '015518'
        );
        $validator = new ValidationManager($validData, $validationRules);
        $this->assertTrue($validator->validate()->isValid());
    }

    public function testInvalidMinLength()
    {
        $validationRules = array(
            'zip' => array(
                'type' => 'digit',
                'params' => array(
                    'min_length' => 4
                )
            )
        );
        $validData = array(
            'zip' => '018'
        );
        $validator = new ValidationManager($validData, $validationRules);
        $this->assertFalse($validator->validate()->isValid());
    }
}