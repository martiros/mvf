<?php

use UIS\Mvf\ValidationManager;

class TerminateFilterTest extends PHPUnit_Framework_TestCase
{
    /** @test */
    public function it_terminate_filters_chain_for_empty_value()
    {
        $validationRules = [
            'price' => [
                'type' => 'int',
                'filters' => [
                    'terminate.ifEmpty' => true,
                    'convert.int' => true,
                ],
            ],
        ];
        $validData = ['price' => ''];
        $validator = new ValidationManager($validData, $validationRules);
        $validator->validate();
        $this->assertSame('', $validData['price']);
    }

    /** @test */
    public function it_not_terminate_filters_chain_for_not_empty_value()
    {
        $validationRules = [
            'price' => [
                'type' => 'int',
                'filters' => [
                    'terminate.ifEmpty' => true,
                    'convert.int' => true,
                ],
            ],
        ];
        $validData = ['price' => '0'];
        $validator = new ValidationManager($validData, $validationRules);
        $validator->validate();
        $this->assertSame(0, $validData['price']);
    }
}
