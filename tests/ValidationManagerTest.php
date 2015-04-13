<?php

use UIS\Mvf\ValidationManager;
use UIS\Mvf\ValidationError;

class ValidationManagerTest extends PHPUnit_Framework_TestCase
{
    public function testSuccessCallback()
    {
        $validationRules = [
            'name' => [
                'type' => 'string',
                'success' => function($validateVar, ValidationError $validationError, ValidationManager $validationManager){

                }
            ],
            'price' => [
                'type' => 'float'
            ],
        ];

        $validData = ['name' => 'PC', 'price' => '19881.15'];
        $validator = new ValidationManager($validData, $validationRules);
        $this->assertTrue($validator->validate()->isValid());
        $this->assertTrue($validator->validate()->isValid());
    }

    public function testValidationFailWhenSuccessCallbackReturnsFalse()
    {
        $validationRules = [
            'name' => [
                'type' => 'string',
                'success' => function($validateVar, $validationError, $this){
                    return false;
                }
            ],
            'price' => [
                'type' => 'float'
            ],
        ];

        $validData = ['name' => 'PC', 'price' => '19881.15'];
        $validator = new ValidationManager($validData, $validationRules);
        $this->assertFalse($validator->validate()->isValid());
    }
}