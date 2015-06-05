<?php

use \UIS\Mvf\ValidationManager;

class TypeDigitTest extends PHPUnit_Framework_TestCase
{
    public function testValidData()
    {
        $validationRules = [
            'zip' => [
                'type' => 'digit',
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
                'type' => 'digit',
            ],
        ];
        $validData = [
            'zip' => 'fvfdv',
        ];
        $validator = new ValidationManager($validData, $validationRules);
        $this->assertFalse($validator->validate()->isValid());
    }

    public function testValidMaxLength()
    {
        $validationRules = [
            'zip' => [
                'type' => 'digit',
                'params' => [
                    'max_length' => 6,
                ],
            ],
        ];
        $validData = [
            'zip' => '015518',
        ];
        $validator = new ValidationManager($validData, $validationRules);
        $this->assertTrue($validator->validate()->isValid());
    }

    public function testInvalidMaxLength()
    {
        $validationRules = [
            'zip' => [
                'type' => 'digit',
                'params' => [
                    'max_length' => 4,
                ],
            ],
        ];
        $validData = [
            'zip' => '015518',
        ];
        $validator = new ValidationManager($validData, $validationRules);
        $this->assertFalse($validator->validate()->isValid());
    }

    public function testValidMinLength()
    {
        $validationRules = [
            'zip' => [
                'type' => 'digit',
                'params' => [
                    'min_length' => 4,
                ],
            ],
        ];
        $validData = [
            'zip' => '015518',
        ];
        $validator = new ValidationManager($validData, $validationRules);
        $this->assertTrue($validator->validate()->isValid());
    }

    public function testInvalidMinLength()
    {
        $validationRules = [
            'zip' => [
                'type' => 'digit',
                'params' => [
                    'min_length' => 4,
                ],
            ],
        ];
        $validData = [
            'zip' => '018',
        ];
        $validator = new ValidationManager($validData, $validationRules);
        $this->assertFalse($validator->validate()->isValid());
    }
}
