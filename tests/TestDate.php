<?php

use UIS\Mvf\ValidationManager;

class TestDate extends PHPUnit_Framework_TestCase
{
    public function testDate()
    {
        $validData = [
            'birthday' => '2014-12-20',
        ];
        $invalidData = [
            'birthday' => 'Test',
        ];
        $validationRules = [
            'birthday' => [
                'type' => 'date',
            ],
        ];
        $this->assertTrue((new ValidationManager($validData, $validationRules))->validate()->isValid());
        $this->assertFalse((new ValidationManager($invalidData, $validationRules))->validate()->isValid());
    }

    public function testMaxDate()
    {
        $validData = [
            'birthday' => '2014-12-20',
        ];
        $invalidData = [
            'birthday' => '2019-10-12',
        ];
        $validationRules = [
            'birthday' => [
                'type' => 'date',
                'params' => [
                    'max_date' => '2015-01-02',
                ],
            ],
        ];
        $this->assertTrue((new ValidationManager($validData, $validationRules))->validate()->isValid());
        $this->assertFalse((new ValidationManager($invalidData, $validationRules))->validate()->isValid());
    }

    public function testMinDate()
    {
        $validData = [
            'birthday' => '2019-10-12',
        ];
        $invalidData = [
            'birthday' => '2014-12-20',
        ];
        $validationRules = [
            'birthday' => [
                'type' => 'date',
                'params' => [
                    'min_date' => '2015-01-02',
                ],
            ],
        ];
        $this->assertTrue((new ValidationManager($validData, $validationRules))->validate()->isValid());
        $this->assertFalse((new ValidationManager($invalidData, $validationRules))->validate()->isValid());
    }
}
