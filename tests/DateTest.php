<?php

use \UIS\Mvf\ValidationManager;

class DateTest extends TestCase
{
    public function testDate()
    {
        $validData = array(
            'birthday' => '2014-12-20'
        );
        $invalidData = array(
            'birthday' => 'Test'
        );
        $validationRules = array(
            'birthday' => array(
                'type' => 'date'
            ),
        );
        $this->assertTrue((new ValidationManager($validData, $validationRules))->validate()->isValid());
        $this->assertFalse((new ValidationManager($invalidData, $validationRules))->validate()->isValid());
    }

    public function testMaxDate()
    {
        $validData = array(
            'birthday' => '2014-12-20'
        );
        $invalidData = array(
            'birthday' => '2019-10-12'
        );
        $validationRules = array(
            'birthday' => array(
                'type' => 'date',
                'params' => array(
                    'max_date' => '2015-01-02'
                )
            ),
        );
        $this->assertTrue((new ValidationManager($validData, $validationRules))->validate()->isValid());
        $this->assertFalse((new ValidationManager($invalidData, $validationRules))->validate()->isValid());
    }

    public function testMinDate()
    {
        $validData = array(
            'birthday' => '2019-10-12'
        );
        $invalidData = array(
            'birthday' => '2014-12-20'
        );
        $validationRules = array(
            'birthday' => array(
                'type' => 'date',
                'params' => array(
                    'min_date' => '2015-01-02'
                )
            ),
        );
        $this->assertTrue((new ValidationManager($validData, $validationRules))->validate()->isValid());
        $this->assertFalse((new ValidationManager($invalidData, $validationRules))->validate()->isValid());
    }
}
