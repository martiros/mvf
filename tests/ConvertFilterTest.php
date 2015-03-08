<?php

use UIS\Mvf\ValidationManager;
use UIS\Mvf\FilterTypes\Convert;

class ConvertFilterTest extends PHPUnit_Framework_TestCase
{
    public function testConvertInt()
    {
        $convert = new Convert();

        $convert->setVarValue('125');
        $this->assertSame(125, $convert->intFilter([]));

        $convert->setVarValue('125d');
        $this->assertSame(125, $convert->intFilter([]));

        $convert->setVarValue('d125d');
        $this->assertSame(0, $convert->intFilter([]));

        $convert->setVarValue('dddd');
        $this->assertSame(0, $convert->intFilter([]));

        $convert->setVarValue('');
        $this->assertSame(0, $convert->intFilter([]));
    }

    public function testConvertIntWithMvf()
    {
        $validationRules = [
            'price' => [
                'type' => 'int',
                'filters' => [
                    'convert.int' => true,
                ]
            ]
        ];
        $validData = ['price' => '19881dd'];
        $validator = new ValidationManager($validData, $validationRules);
        $this->assertTrue($validator->validate()->isValid());
    }
}