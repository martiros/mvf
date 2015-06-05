<?php

use \UIS\Mvf\ValidationManager;

class TypeEnumTest extends PHPUnit_Framework_TestCase
{
    public function testValidData()
    {
        $validationRules = [
            'gender' => [
                'type' => 'enum',
                'params' => [
                    'values' => [
                        'female',
                        'male',
                    ],
                ],
            ],
        ];
        $validData = [
            'gender' => 'female',
        ];
        $validator = new ValidationManager($validData, $validationRules);
        $this->assertTrue($validator->validate()->isValid());
    }

    public function testInvalidData()
    {
        $validationRules = [
            'gender' => [
                'type' => 'enum',
                'params' => [
                    'values' => [
                        'female', 'male',
                    ],
                ],
            ],
        ];
        $validData = [
            'gender' => 'data',
        ];
        $validator = new ValidationManager($validData, $validationRules);
        $this->assertFalse($validator->validate()->isValid());
    }
}
