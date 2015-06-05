<?php
use UIS\Mvf\ValidationManager;

class TypeUsernameTest extends PHPUnit_Framework_TestCase
{
    /** @test */
    public function it_pass_validation_for_valid_username()
    {
        $validationRules = ['username' => ['type' => 'username']];
        $data = ['username' => 'martiros.aghajanyan'];
        $validator = new ValidationManager($data, $validationRules);
        $this->assertTrue($validator->validate()->isValid());
    }

    /** @test */
    public function it_fail_validation_for_not_allowed_chars()
    {
        $validationRules = ['username' => ['type' => 'username']];
        $data = ['username' => 'martiros. aghajanyan'];
        $validator = new ValidationManager($data, $validationRules);

        $validationResult = $validator->validate();
        $this->assertFalse($validationResult->isValid());
        $this->assertEquals('validation.error.username.invalid', $validationResult->error('username'));
    }

    /** @test */
    public function it_fail_validation_for_small_username()
    {
        $validationRules = ['username' => ['type' => 'username']];
        $data = ['username' => 'm'];
        $validator = new ValidationManager($data, $validationRules);

        $validationResult = $validator->validate();
        $this->assertFalse($validationResult->isValid());
        $this->assertEquals('validation.error.username.min_length', $validationResult->error('username'));
    }

    /** @test */
    public function it_fail_validation_for_big_username()
    {
        $validationRules = ['username' => ['type' => 'username']];
        $data = ['username' => 'very.long.username.very.long.username.very.long.username.very.long.username.very.long.username.very.long.username'];
        $validator = new ValidationManager($data, $validationRules);

        $validationResult = $validator->validate();
        $this->assertFalse($validationResult->isValid());
        $this->assertEquals('validation.error.username.max_length', $validationResult->error('username'));
    }

    /** @test */
    public function it_fail_validation_for_not_valid_first_char()
    {
        $validationRules = ['username' => ['type' => 'username']];
        $data = ['username' => '.martiros.aghajanyan'];
        $validator = new ValidationManager($data, $validationRules);

        $validationResult = $validator->validate();
        $this->assertFalse($validationResult->isValid());
        $this->assertEquals('validation.error.username.first_char', $validationResult->error('username'));
    }

    /** @test */
    public function it_fail_validation_for_not_valid_end_char()
    {
        $validationRules = ['username' => ['type' => 'username']];
        $data = ['username' => 'martiros.aghajanyan.'];
        $validator = new ValidationManager($data, $validationRules);

        $validationResult = $validator->validate();
        $this->assertFalse($validationResult->isValid());
        $this->assertEquals('validation.error.username.end_char', $validationResult->error('username'));
    }

    /** @test */
    public function it_fail_validation_for_periods_punctuation()
    {
        $validationRules = ['username' => ['type' => 'username']];
        $data = ['username' => 'martiros..aghajanyan'];
        $validator = new ValidationManager($data, $validationRules);

        $validationResult = $validator->validate();
        $this->assertFalse($validationResult->isValid());
        $this->assertEquals('validation.error.username.punctuation', $validationResult->error('username'));
    }
}
