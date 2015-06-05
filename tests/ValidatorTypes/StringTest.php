<?php
use UIS\Mvf\ValidationManager;

class StringTest extends PHPUnit_Framework_TestCase
{
    /** @test */
    public function it_pass_validation_for_string()
    {
        $validationRules = ['name' => ['type' => 'string']];
        $data = ['name' => 'Martiros'];

        $validator = new ValidationManager($data, $validationRules);
        $this->assertTrue($validator->validate()->isValid());
    }

    /** @test */
    public function it_fail_validation_for_non_string()
    {
        $validationRules = ['name' => ['type' => 'string']];
        $data = ['name' => ['first_name' => 'Martiros']];

        $validator = new ValidationManager($data, $validationRules);

        $validationResult = $validator->validate();
        $this->assertFalse($validationResult->isValid());
        $this->assertEquals('validation.error.string.invalid', $validationResult->error('name'));
    }

    /** @test */
    public function it_pass_validation_if_string_length_is_small_than_max_length_option()
    {
        $validationRules = ['name' => ['type' => 'string', 'params' => ['max_length' => 9]]];
        $data = ['name' => 'Martiros'];

        $validator = new ValidationManager($data, $validationRules);
        $this->assertTrue($validator->validate()->isValid());
    }

    /** @test */
    public function it_pass_validation_if_string_length_is_equal_to_max_length_option()
    {
        $validationRules = ['name' => ['type' => 'string', 'params' => ['max_length' => 8]]];
        $data = ['name' => 'Martiros'];

        $validator = new ValidationManager($data, $validationRules);
        $this->assertTrue($validator->validate()->isValid());
    }

    /** @test */
    public function it_fail_validation_if_string_length_is_big_than_max_length_option()
    {
        $validationRules = ['name' => ['type' => 'string', 'params' => ['max_length' => 7]]];
        $data = ['name' => 'Martiros'];

        $validator = new ValidationManager($data, $validationRules);

        $validationResult = $validator->validate();
        $this->assertFalse($validationResult->isValid());
        $this->assertEquals('validation.error.string.max_length', $validationResult->error('name'));
    }

    /** @test */
    public function it_fail_validation_if_string_length_is_small_than_min_length_option()
    {
        $validationRules = ['name' => ['type' => 'string', 'params' => ['min_length' => 100]]];
        $data = ['name' => 'Martiros'];

        $validator = new ValidationManager($data, $validationRules);

        $validationResult = $validator->validate();
        $this->assertFalse($validationResult->isValid());
        $this->assertEquals('validation.error.string.min_length', $validationResult->error('name'));
    }

    /** @test */
    public function it_pass_validation_if_matching_regex()
    {
        $validationRules = ['phone' => ['type' => 'string', 'params' => ['regexp' => '/^[0-9]{6,20}$/']]];
        $data = ['phone' => '374441589'];

        $validator = new ValidationManager($data, $validationRules);
        $this->assertTrue($validator->validate()->isValid());
    }

    /** @test */
    public function it_fail_validation_if_matching_regex()
    {
        $validationRules = ['phone' => ['type' => 'string', 'params' => ['regexp' => '/^[0-9]{6,20}$/']]];
        $data = ['phone' => 'Martiros'];

        $validator = new ValidationManager($data, $validationRules);
        $validationResult = $validator->validate();
        $this->assertFalse($validationResult->isValid());
        $this->assertEquals('validation.error.string.regexp', $validationResult->error('phone'));
    }
}
