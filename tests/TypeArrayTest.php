<?php

use UIS\Mvf\ValidationManager;
use UIS\Mvf\ValidationError;
use Mockery as m;

class TypeArrayTest extends PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        m::close();
    }

    public function testBase()
    {
        $validationRules = array(
            'tags' => array(
                'type' => 'array'
            )
        );
        $validData = array(
            'tags' => array( 'PHP', 'MySql', 'JavaScript')
        );

        $invalidData = array(
            'tags' => 'just string'
        );

        $validator = new ValidationManager($validData, $validationRules);
        $this->assertTrue($validator->validate()->isValid());

        $validator = new ValidationManager($invalidData, $validationRules);
        $this->assertFalse($validator->validate()->isValid());
    }

    public function testMinLength()
    {
        $validationRules = array(
            'tags' => array(
                'type' => 'array',
                'params' => array(
                    'min_length' => 2
                )
            )
        );
        $validData = array(
            'tags' => array( 'PHP', 'MySql', 'JavaScript')
        );

        $invalidData = array(
            'tags' => array( 'PHP')
        );

        $validator = new ValidationManager($validData, $validationRules);
        $this->assertTrue($validator->validate()->isValid());

        $validator = new ValidationManager($invalidData, $validationRules);
        $this->assertFalse($validator->validate()->isValid());
    }

    public function testMaxLength()
    {
        $validationRules = array(
            'tags' => array(
                'type' => 'array',
                'params' => array(
                    'max_length' => 2
                )
            )
        );
        $validData = array(
            'tags' => array( 'PHP')
        );

        $invalidData = array(
            'tags' => array( 'PHP', 'MySql', 'JavaScript')
        );

        $validator = new ValidationManager($validData, $validationRules);
        $this->assertTrue($validator->validate()->isValid());

        $validator = new ValidationManager($invalidData, $validationRules);
        $this->assertFalse($validator->validate()->isValid());
    }

    public function testAllowedValues()
    {
        $validationRules = array(
            'tags' => array(
                'type' => 'array',
                'params' => array(
                    'allowed_values' => array(
                        'PHP', 'MySql', 'JavaScript'
                    )
                )
            )
        );
        $validData = array(
            'tags' => array( 'PHP', 'JavaScript')
        );

        $invalidData = array(
            'tags' => array( 'PHP', 'MySql', 'Java')
        );

        $validator = new ValidationManager($validData, $validationRules);
        $this->assertTrue($validator->validate()->isValid());

        $validator = new ValidationManager($invalidData, $validationRules);
        $this->assertFalse($validator->validate()->isValid());
    }

    public function testArrayUnique()
    {
        $validationRules = array(
            'tags' => array(
                'type' => 'array',
                'params' => array(
                    'array_unique' => true
                )
            )
        );
        $validData = array(
            'tags' => array( 'PHP', 'JavaScript')
        );

        $invalidData = array(
            'tags' => array( 'PHP', 'MySql', 'PHP')
        );

        $validator = new ValidationManager($validData, $validationRules);
        $this->assertTrue($validator->validate()->isValid());

        $validator = new ValidationManager($invalidData, $validationRules);
        $this->assertFalse($validator->validate()->isValid());
    }

    public function testItemsValidator()
    {
        $callsCount = 0;
        $testCase = $this;
        $validationRules = array(
            'name' => array(
                'type' => 'string'
            ),
            'tags' => array(
                'type' => 'array',
                'params' => array(
                    'items_validator' => array(
                        'mapping' => array(
                            'name' => array(
                                'type' => 'string',
                                'filters' => array(
                                    'string.trim' => true
                                )
                            ),
                            'url' => array(
                                'type' => 'url'
                            )
                        )
                    )
                ),
                'success' => function($tags, ValidationError $error) use ($testCase, &$callsCount){
                    $callsCount++;
                    $testCase->assertTrue(count($tags) === 2);
                    $testCase->assertTrue($error->isValid());
                    $testCase->assertEquals(1, $callsCount);
                }
            )
        );
        $validData = array(
            'tags' => array(
                array(
                    'name' => 'PHP',
                    'url' => 'http://en.wikipedia.org/wiki/PHP'
                ),
                array(
                    'name' => ' JavaScript',
                    'url' => 'http://en.wikipedia.org/wiki/JavaScript',
                )
            )
        );

        $invalidData = array(
            'name' => 'test',
            'tags' => array(
                'Java',
                array(
                    'name' => 'PHP',
                    'url' => 'http://en.wikipedia.org/wiki/PHP'
                ),
                array(
                    'name' => 'JavaScript',
                    'url' => 'http://en.wikipedia.org/wiki/JavaScript',
                )
            )
        );

        $validator = new ValidationManager($validData, $validationRules);
        $this->assertTrue($validator->validate()->isValid());
        $this->assertEquals(1, $callsCount);

        $this->assertTrue($validData['tags'][1]['name'] === 'JavaScript');

        $validator = new ValidationManager($invalidData, $validationRules);
        $this->assertFalse($validator->validate()->isValid());
    }
}
