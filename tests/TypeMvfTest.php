<?php

use UIS\Mvf\ValidationManager;

class TypeMvfTest extends PHPUnit_Framework_TestCase
{
    public function testMvf()
    {
        $validationRules = [
            'name' => [
                'type' => 'string',
            ],
            'address' => [
                'type' => 'mvf',
                'params' => [
                    'mapping' => [
                        'street' => [
                            'type' => 'string',
                            'filters' => [
                                'string.trim' => true,
                            ],
                        ],
                        'country_id' => [
                            'type' => 'int',
                        ],
                    ],
                ],
            ],
        ];
        $data = [
            'name' => 'Test',
            'address' => [
                'country_id' => 'not integer',
                'street' => 'test ',
            ],
        ];

        $validator = new ValidationManager($data, $validationRules);
        $validationErrors = $validator->validate()->errors();
        $this->assertTrue(isset($validationErrors['address']['country_id'])); //check is error exists
        $this->assertTrue($data['address']['street'] === 'test'); // check is sub validator changed its value
    }
}
