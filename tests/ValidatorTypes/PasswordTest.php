<?php
use UIS\Mvf\ValidationManager;

class PasswordTest extends PHPUnit_Framework_TestCase
{
    /** @test */
    public function it_pass_validation_for_password()
    {
        $validationRules = ['password' => ['type' => 'password']];
        $data = ['name' => 'test123'];

        $validator = new ValidationManager($data, $validationRules);
        $this->assertTrue($validator->validate()->isValid());
    }

    /** @test */
    public function it_fail_validation_for_non_string()
    {
        $validationRules = ['password' => ['type' => 'password']];
        $data = ['password' => ['test123']];

        $validator = new ValidationManager($data, $validationRules);
        $validationResult = $validator->validate();
        $this->assertFalse($validationResult->isValid());
        $this->assertEquals('validation.error.password.invalid', $validationResult->error('password'));
    }

    /** @test */
    public function it_fail_validation_if_string_length_is_small_than_min_length_option()
    {
        $validationRules = ['password' => ['type' => 'password', 'params' => ['min_length' => 100]]];
        $data = ['password' => 'testTest123'];

        $validator = new ValidationManager($data, $validationRules);
        $validationResult = $validator->validate();
        $this->assertFalse($validationResult->isValid());
        $this->assertEquals('validation.error.password.min_length', $validationResult->error('password'));
    }

    /** @test */
    public function it_pass_validation_if_string_length_is_small_than_max_length_option()
    {
        $validationRules = ['password' => ['type' => 'password', 'params' => ['max_length' => 9]]];
        $data = ['password' => 'test123'];

        $validator = new ValidationManager($data, $validationRules);
        $this->assertTrue($validator->validate()->isValid());
    }

    /** @test */
    public function it_fail_validation_if_string_is_large_than_max_length_option()
    {
        $validationRules = ['password' => ['type' => 'password', 'params' => ['max_length' => 9]]];
        $data = ['password' => 'testTest123'];

        $validator = new ValidationManager($data, $validationRules);
        $validationResult = $validator->validate();
        $this->assertFalse($validationResult->isValid());
        $this->assertEquals('validation.error.password.max_length', $validationResult->error('password'));
    }

    /** @test */
    public function it_throws_exception_for_invalid_security_level_type()
    {
        $this->setExpectedException('\InvalidArgumentException');

        $validationRules = ['password' => ['type' => 'password', 'params' => ['security_level' => 'invalid_type']]];
        $data = ['password' => 'testTest1'];

        $validator = new ValidationManager($data, $validationRules);
        $validator->validate();
    }

    /** @test */
    public function it_pass_validation_for_medium_password()
    {
        $validationRules = ['password' => ['type' => 'password', 'params' => ['security_level' => 'medium']]];
        $data = ['password' => 'testTest1'];

        $validator = new ValidationManager($data, $validationRules);
        $validationResult = $validator->validate();
        $this->assertTrue($validationResult->isValid());
    }

    /** @test */
    public function it_fail_validation_for_not_medium_password()
    {
        $validationRules = ['password' => ['type' => 'password', 'params' => ['security_level' => 'medium']]];
        $data = ['password' => 'testTest'];

        $validator = new ValidationManager($data, $validationRules);
        $validationResult = $validator->validate();
        $this->assertFalse($validationResult->isValid());
        $this->assertEquals('validation.error.password.security_level_not_medium', $validationResult->error('password'));
    }

    /** @test */
    public function it_pass_validation_for_height_password()
    {
        $validationRules = ['password' => ['type' => 'password', 'params' => ['security_level' => 'height']]];
        $data = ['password' => 'testTes$t1'];

        $validator = new ValidationManager($data, $validationRules);
        $validationResult = $validator->validate();
        $this->assertTrue($validationResult->isValid());
    }

    /** @test */
    public function it_fail_validation_for_not_height_password()
    {
        $validationRules = ['password' => ['type' => 'password', 'params' => ['security_level' => 'height']]];
        $data = ['password' => 'testTest'];

        $validator = new ValidationManager($data, $validationRules);
        $validationResult = $validator->validate();
        $this->assertFalse($validationResult->isValid());
        $this->assertEquals('validation.error.password.security_level_not_height', $validationResult->error('password'));
    }
}
