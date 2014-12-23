<?php

use \UIS\Mvf\ValidationManager;

class TypeFunctionTest extends TestCase
{
    public function testValidData()
    {
        $validationRules = array(
            'zip' => array(
                'type' => 'function',
                'params' => array(
                    'function' => function($zipCode) {
                        return $zipCode === '19881';
                    }
                )
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
                'type' => 'function',
                'params' => array(
                    'function' => function($zipCode) {
                            return $zipCode !== '19881';
                        }
                )
            )
        );
        $validData = array(
            'zip' => '19881'
        );
        $validator = new ValidationManager($validData, $validationRules);
        $this->assertFalse($validator->validate()->isValid());
    }
}