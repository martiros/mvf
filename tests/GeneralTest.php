<?php

use \UIS\Mvf\ValidationManager;

class GeneralTest extends PHPUnit_Framework_TestCase
{
    public function testRequiredWithEmptyData()
    {
        $validationRules = [
            'my_date' => [
                'type' => 'date',
                'required' => 'Error message, my_date is required',
            ],
        ];

        $data = [
            'my_date' => '',
        ];
        $validator = new ValidationManager($data, $validationRules);
        $this->assertTrue(!$validator->validate()->isValid());
    }

    public function testRequiredWithNotEmptyData()
    {
        $validationRules = [
            'my_date' => [
                'type' => 'date',
                'required' => 'Error message, my_date is required',
            ],
        ];

        $data = [
            'my_date' => '2014-10-10',
        ];
        $validator = new ValidationManager($data, $validationRules);
        $this->assertTrue($validator->validate()->isValid());
    }

    public function testDefaultData()
    {
        $validationRules = [
            'my_date' => [
                'type' => 'date',
                'default' => null,
            ],
        ];

        $data = [
            'my_date' => '',
        ];
        $validator = new ValidationManager($data, $validationRules);
        $validationResult = $validator->validate();

        $this->assertTrue($validationResult->isValid());
        $this->assertTrue($data['my_date'] === null);
    }

    public function testDefaultDataWithRequiredError()
    {
        $validationRules = [
            'my_date' => [
                'type' => 'date',
                'default' => null,
                'required' => 'Error message, my_date is required',
            ],
        ];

        $data = [
            'my_date' => '',
        ];
        $validator = new ValidationManager($data, $validationRules);
        $validationResult = $validator->validate();

        $this->assertTrue(!$validationResult->isValid());
        $this->assertTrue($data['my_date'] !== null);
        $this->assertTrue($data['my_date'] === '');
    }

    /**
     * Test is unset not defined key from validating data, if key not defined in rules.
     * @return void
     */
    public function testIsUnsetingNotDefinedKey()
    {
        $dataToValidate = [
            'first_name' => 'Test',
            'last_name' => 'Testyan',
            'password' => '123',
        ];
        $validationRules = [
            'first_name' => [
                'type' => 'string',
            ],
            'last_name' => [
                'type' => 'string',
            ],
        ];
        $validator = new ValidationManager($dataToValidate, $validationRules);
        $validator->validate();
        $this->assertTrue(!isset($dataToValidate['password']), 'Fail: Test is unset not defined key from validating data, if key not defined in rules');
    }
}
