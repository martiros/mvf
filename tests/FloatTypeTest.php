<?php

use \UIS\Mvf\ValidationManager;

class FloatTypeTest extends PHPUnit_Framework_TestCase
{
    public function testBase()
    {
        $validationRules = ['price' => ['type' => 'float']];

        $validData = ['price' => '19881.15'];
        $validator = new ValidationManager($validData, $validationRules);
        $this->assertTrue($validator->validate()->isValid());

        $invalidData = ['price' => '19881c.15'];
        $validator = new ValidationManager($invalidData, $validationRules);
        $this->assertFalse($validator->validate()->isValid());
    }

    public function testMaxValue()
    {
        $validationRules = [
            'price' => [
                'type' => 'float',
                'params' => [
                    'max_value' => 157.15
                ]
            ]
        ];

        $validData = ['price' => '157.10'];
        $validator = new ValidationManager($validData, $validationRules);
        $this->assertTrue($validator->validate()->isValid());

        $validData = ['price' => '157.15'];
        $validator = new ValidationManager($validData, $validationRules);
        $this->assertTrue($validator->validate()->isValid());

        $invalidData = ['price' => '158.10'];
        $validator = new ValidationManager($invalidData, $validationRules);
        $this->assertFalse($validator->validate()->isValid());
    }

    public function testMinValue()
    {
        $validationRules = [
            'price' => [
                'type' => 'float',
                'params' => [
                    'min_value' => 157.15
                ]
            ]
        ];

        $validData = ['price' => '159.10'];
        $validator = new ValidationManager($validData, $validationRules);
        $this->assertTrue($validator->validate()->isValid());

        $validData = ['price' => '157.15'];
        $validator = new ValidationManager($validData, $validationRules);
        $this->assertTrue($validator->validate()->isValid());

        $invalidData = ['price' => '154.10'];
        $validator = new ValidationManager($invalidData, $validationRules);
        $this->assertFalse($validator->validate()->isValid());
    }

    public function testMaxDecimals()
    {
        $validationRules = [
            'price' => [
                'type' => 'float',
                'params' => [
                    'max_decimals' => 2
                ]
            ]
        ];

        $validData = ['price' => '159'];
        $validator = new ValidationManager($validData, $validationRules);
        $this->assertTrue($validator->validate()->isValid());

        $validData = ['price' => '159.1'];
        $validator = new ValidationManager($validData, $validationRules);
        $this->assertTrue($validator->validate()->isValid());

        $validData = ['price' => '159.11'];
        $validator = new ValidationManager($validData, $validationRules);
        $this->assertTrue($validator->validate()->isValid());

        $invalidData = ['price' => '154.010'];
        $validator = new ValidationManager($invalidData, $validationRules);
        $this->assertTrue($validator->validate()->isValid());

        $invalidData = ['price' => '154.011'];
        $validator = new ValidationManager($invalidData, $validationRules);
        $this->assertFalse($validator->validate()->isValid());
    }
}