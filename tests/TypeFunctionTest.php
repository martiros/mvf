<?php

use \UIS\Mvf\ValidationManager;

class TypeFunctionTest extends PHPUnit_Framework_TestCase
{
    public function testValidData()
    {
        $validationRules = [
            'zip' => [
                'type' => 'function',
                'params' => [
                    'function' => function ($zipCode) {
                        return $zipCode === '19881';
                    },
                ],
            ],
        ];
        $validData = [
            'zip' => '19881',
        ];
        $validator = new ValidationManager($validData, $validationRules);
        $this->assertTrue($validator->validate()->isValid());
    }

    public function testInvalidData()
    {
        $validationRules = [
            'zip' => [
                'type' => 'function',
                'params' => [
                    'function' => function ($zipCode) {
                            return $zipCode !== '19881';
                        },
                ],
            ],
        ];
        $validData = [
            'zip' => '19881',
        ];
        $validator = new ValidationManager($validData, $validationRules);
        $this->assertFalse($validator->validate()->isValid());
    }
}
