<?php

use UIS\Mvf\ValidationManager;

class TerminateFilterTest extends PHPUnit_Framework_TestCase
{
    public function testIfEmpty()
    {
        $validationRules = [
            'price' => [
                'type' => 'int',
                'filters' => [
                    'terminate.ifEmpty' => true,
                    'convert.int' => true,
                ]
            ]
        ];
        $validData = ['price' => ''];
        $validator = new ValidationManager($validData, $validationRules);
        $validator->validate();
        $this->assertEquals('', $validData['price']);
    }
}