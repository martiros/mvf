<?php
use UIS\Mvf\ValidationManager;

class PhoneTest extends PHPUnit_Framework_TestCase
{
    /** @test */
    public function it_pass_validation_for_valid_phone()
    {
        $validationRules = ['url' => ['type' => 'url']];
        $data = ['url' => 'https://github.com/martiros'];
        $validator = new ValidationManager($data, $validationRules);

        $validationResult = $validator->validate();
        $this->assertTrue($validationResult->isValid());
    }

    /** @test */
    public function it_fail_validation_for_invalid_phone()
    {
        $validationRules = ['phone' => ['type' => 'phone']];
        $data = ['phone' => '015'];
        $validator = new ValidationManager($data, $validationRules);

        $validationResult = $validator->validate();
        $this->assertFalse($validationResult->isValid());
        $this->assertEquals('validation.error.phone.invalid', $validationResult->error('phone'));
    }
}
