<?php

use UIS\Mvf\ValidationManager;
use UIS\Mvf\ValidationError;

class TypeArrayTest extends PHPUnit_Framework_TestCase
{
    public function testBase()
    {
        $validationRules = [
            'tags' => [
                'type' => 'array',
            ],
        ];
        $validData = [
            'tags' => ['PHP', 'MySql', 'JavaScript'],
        ];

        $invalidData = [
            'tags' => 'just string',
        ];

        $validator = new ValidationManager($validData, $validationRules);
        $this->assertTrue($validator->validate()->isValid());

        $validator = new ValidationManager($invalidData, $validationRules);
        $this->assertFalse($validator->validate()->isValid());
    }

    public function testMinLength()
    {
        $validationRules = [
            'tags' => [
                'type' => 'array',
                'params' => [
                    'min_length' => 2,
                ],
            ],
        ];
        $validData = [
            'tags' => ['PHP', 'MySql', 'JavaScript'],
        ];

        $invalidData = [
            'tags' => ['PHP'],
        ];

        $validator = new ValidationManager($validData, $validationRules);
        $this->assertTrue($validator->validate()->isValid());

        $validator = new ValidationManager($invalidData, $validationRules);
        $this->assertFalse($validator->validate()->isValid());
    }

    public function testMaxLength()
    {
        $validationRules = [
            'tags' => [
                'type' => 'array',
                'params' => [
                    'max_length' => 2,
                ],
            ],
        ];
        $validData = [
            'tags' => ['PHP'],
        ];

        $invalidData = [
            'tags' => ['PHP', 'MySql', 'JavaScript'],
        ];

        $validator = new ValidationManager($validData, $validationRules);
        $this->assertTrue($validator->validate()->isValid());

        $validator = new ValidationManager($invalidData, $validationRules);
        $this->assertFalse($validator->validate()->isValid());
    }

    public function testAllowedValues()
    {
        $validationRules = [
            'tags' => [
                'type' => 'array',
                'params' => [
                    'allowed_values' => [
                        'PHP', 'MySql', 'JavaScript',
                    ],
                ],
            ],
        ];
        $validData = [
            'tags' => ['PHP', 'JavaScript'],
        ];

        $invalidData = [
            'tags' => ['PHP', 'MySql', 'Java'],
        ];

        $validator = new ValidationManager($validData, $validationRules);
        $this->assertTrue($validator->validate()->isValid());

        $validator = new ValidationManager($invalidData, $validationRules);
        $this->assertFalse($validator->validate()->isValid());
    }

    public function testArrayUnique()
    {
        $validationRules = [
            'tags' => [
                'type' => 'array',
                'params' => [
                    'array_unique' => true,
                ],
            ],
        ];
        $validData = [
            'tags' => ['PHP', 'JavaScript'],
        ];
        $validator = new ValidationManager($validData, $validationRules);
        $this->assertTrue($validator->validate()->isValid());

        $invalidData = [
            'tags' => ['PHP', 'MySql', 'PHP'],
        ];
        $validator = new ValidationManager($invalidData, $validationRules);
        $this->assertFalse($validator->validate()->isValid());

        $validData = [
            'tags' => [
                [
                    'key1_a' => 'value1_a',
                    'key1_b' => 'value1_b',
                ],
                [
                    'key2_a' => 'value2_a',
                    'key2_b' => 'value2_b',
                ],
            ],
        ];
        $validator = new ValidationManager($validData, $validationRules);
        $this->assertTrue($validator->validate()->isValid());

        $invalidData = [
            'tags' => [
                [
                    'key1_a' => 'value1_a',
                    'key1_b' => 'value1_b',
                ],
                [
                    'key1_b' => 'value1_b',
                    'key1_a' => 'value1_a',
                ],
            ],
        ];
        $validator = new ValidationManager($invalidData, $validationRules);
        $this->assertFalse($validator->validate()->isValid());
    }

    public function testItemsValidator()
    {
        $callsCount = 0;
        $testCase = $this;
        $validationRules = [
            'name' => [
                'type' => 'string',
            ],
            'tags' => [
                'type' => 'array',
                'params' => [
                    'items_validator' => [
                        'mapping' => [
                            'name' => [
                                'type' => 'string',
                                'filters' => [
                                    'string.trim' => true,
                                ],
                            ],
                            'url' => [
                                'type' => 'url',
                            ],
                        ],
                    ],
                ],
                'success' => function ($tags, ValidationError $error) use ($testCase, &$callsCount) {
                    $callsCount++;
                    $testCase->assertTrue(count($tags) === 2);
                    $testCase->assertTrue($error->isValid());
                    $testCase->assertEquals(1, $callsCount);
                },
            ],
        ];
        $validData = [
            'tags' => [
                [
                    'name' => 'PHP',
                    'url' => 'http://en.wikipedia.org/wiki/PHP',
                ],
                [
                    'name' => ' JavaScript',
                    'url' => 'http://en.wikipedia.org/wiki/JavaScript',
                ],
            ],
        ];

        $invalidData = [
            'name' => 'test',
            'tags' => [
                'Java',
                [
                    'name' => 'PHP',
                    'url' => 'http://en.wikipedia.org/wiki/PHP',
                ],
                [
                    'name' => 'JavaScript',
                    'url' => 'http://en.wikipedia.org/wiki/JavaScript',
                ],
            ],
        ];

        $validator = new ValidationManager($validData, $validationRules);
        $this->assertTrue($validator->validate()->isValid());
        $this->assertEquals(1, $callsCount);

        $this->assertTrue($validData['tags'][1]['name'] === 'JavaScript');

        $validator = new ValidationManager($invalidData, $validationRules);
        $this->assertFalse($validator->validate()->isValid());
    }
}
