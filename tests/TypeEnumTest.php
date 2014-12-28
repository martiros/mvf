<?php

use \UIS\Mvf\ValidationManager;

class TypeEnumTest extends TestCase
{
    public function testValidData()
    {
        $validationRules = array(
            'gender' => array(
                'type' => 'enum',
                'params' => array(
                    'values' => array(
                        'female',
                        'male'
                    )
                )
            )
        );
        $validData = array(
            'gender' => 'female'
        );
        $validator = new ValidationManager($validData, $validationRules);
        $this->assertTrue($validator->validate()->isValid());
    }

    public function testInvalidData()
    {
        $validationRules = array(
            'gender' => array(
                'type' => 'enum',
                'params' => array(
                    'values' => array(
                        'female', 'male'
                    )
                )
            )
        );
        $validData = array(
            'gender' => 'data'
        );
        $validator = new ValidationManager($validData, $validationRules);
        $this->assertFalse($validator->validate()->isValid());
    }
}