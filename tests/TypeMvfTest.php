<?php

use \UIS\Mvf\ValidationManager;

class TypeMvfTest extends TestCase
{
    public function testMvf()
    {
        $validationRules = array(
            'name' => array(
                'type' => 'string',
            ),
            'address' => array(
                'type' => 'mvf',
                'params' => array(
                    'mapping' => array(
                        'street' => array(
                            'type' => 'string',
                            'filters' => array(
                                'string.trim' => true
                            )
                        ),
                        'country_id' => array(
                            'type' => 'int'
                        ),
                    ),
                )
            )
        );
        $data = array(
            'name' => 'Test',
            'address' => array(
                'country_id' => 'not integer',
                'street' => 'test '
            )
        );

        $validator = new ValidationManager($data, $validationRules);
        $validationErrors = $validator->validate()->errors();
        $this->assertTrue(isset($validationErrors['address']['country_id'])); //check is error exists
        $this->assertTrue($data['address']['street'] === 'test'); // check is sub validator changed its value
    }
}