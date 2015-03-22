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

    public function testConvertArray()
    {
        $convert = new Convert();

        $convert->setVarValue('125');
        $this->assertSame([], $convert->arrayFilter([]));

        $convert->setVarValue(['a']);
        $this->assertSame(['a'], $convert->arrayFilter([]));

        $object = new stdClass();
        $object->a = new stdClass();
        $object->a->b = 2;
        $object->a->c = 3;
        $object->c = ['a' => 1, 'c' => 3];
        $convert->setVarValue($object);
        $this->assertSame(
            ['a' => ['b' => 2, 'c' => 3], 'c' => ['a' => 1, 'c' => 3]],
            $convert->arrayFilter([])
        );
    }

    public function testConvertArrayWithMvf()
    {
        $validationRules = [
            'tags' => [
                'type' => 'array',
                'filters' => [
                    'convert.array' => true,
                ]
            ]
        ];
        $validData = ['tags' => 'test'];
        $validator = new ValidationManager($validData, $validationRules);
        $this->assertTrue($validator->validate()->isValid());
        $this->assertEquals(['tags' => []], $validData);
    }
}