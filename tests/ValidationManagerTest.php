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
                'success' => function ($validateVar, ValidationError $validationError, ValidationManager $validationManager) {

                },
            ],
            'price' => [
                'type' => 'float',
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
                'success' => function () {
                    return false;
                },
            ],
            'price' => [
                'type' => 'float',
            ],
        ];

        $validData = ['name' => 'PC', 'price' => '19881.15'];
        $validator = new ValidationManager($validData, $validationRules);
        $this->assertFalse($validator->validate()->isValid());
    }

    /** @test */
    public function it_skips_validators_if_data_not_set_for_skip_if_not_exists_option()
    {
        $validationRules = [
            'name' => [
                'type' => 'string',
                'required' => true,
                'skip_if_not_exists' => true,
            ],
            'price' => [
                'type' => 'float',
            ],
        ];
        $data = ['price' => '19881.15'];
        $validator = new ValidationManager($data, $validationRules);
        $validationResult = $validator->validate();
        $this->assertTrue($validationResult->isValid());
        $this->assertArrayNotHasKey('name', $data);
    }

    /** @test */
    public function it_skips_filters_if_data_not_set_for_skip_not_exists_options()
    {
        $validationRules = [
            'name' => [
                'type' => 'string',
                'required' => true,
                'skip_if_not_exists' => true,
                'filters' => [
                    'string.trim' => true,
                ],
            ],
            'price' => [
                'type' => 'float',
            ],
        ];
        $data = ['price' => '19881.15'];
        $validator = new ValidationManager($data, $validationRules);
        $validationResult = $validator->validate();
        $this->assertTrue($validationResult->isValid());
        $this->assertArrayNotHasKey('name', $data);
    }
}
