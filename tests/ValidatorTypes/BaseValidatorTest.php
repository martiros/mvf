<?php

class BaseValidatorTest extends PHPUnit_Framework_TestCase
{
    /** @test */
    public function it_return_get_default_error_message_when_error_message_not_set()
    {
        $validationManager = Mockery::mock('\UIS\Mvf\ValidationManager');
        $rule = Mockery::mock('\UIS\Mvf\ConfigItem');
        $rule->shouldReceive('getError')->once()->andReturn(true);
        $baseValidator = $this->getMockForAbstractClass('\UIS\Mvf\ValidatorTypes\BaseValidator', [$validationManager, $rule]);

        $this->assertEquals('Invalid data', $baseValidator->getErrorMessage());
    }

    /** @test */
    public function it_return_get_error_message_if_it_set_in_rule()
    {
        $validationManager = Mockery::mock('\UIS\Mvf\ValidationManager');
        $rule = Mockery::mock('\UIS\Mvf\ConfigItem');
        $rule->shouldReceive('getError')->once()->andReturn('error message');
        $baseValidator = $this->getMockForAbstractClass('\UIS\Mvf\ValidatorTypes\BaseValidator', [$validationManager, $rule]);

        $this->assertEquals('error message', $baseValidator->getErrorMessage());
    }
    
    /** @test */
    public function it_return_custom_message_if_it_set()
    {
        $validationManager = Mockery::mock('\UIS\Mvf\ValidationManager');
        $rule = Mockery::mock('\UIS\Mvf\ConfigItem');
        $rule->shouldReceive('isSetCustomError')->once()->andReturn(true);
        $rule->shouldReceive('getCustomError')->once()->with('custom_case_key')->andReturn('custom error');
        $baseValidator = $this->getMockForAbstractClass('\UIS\Mvf\ValidatorTypes\BaseValidator', [$validationManager, $rule]);

        $this->assertEquals('custom error', $baseValidator->getCustomErrorMessage('custom_case_key'));
    }

    /** @test */
    public function it_return_default_custom_message_if_custom_not_set()
    {
        $validationManager = Mockery::mock('\UIS\Mvf\ValidationManager');
        $rule = Mockery::mock('\UIS\Mvf\ConfigItem');
        $rule->shouldReceive('isSetCustomError')->once()->andReturn(false);
        $rule->shouldReceive('getError')->once()->withNoArgs()->andReturn(true);
        $baseValidator = $this->getMockForAbstractClass('\UIS\Mvf\ValidatorTypes\BaseValidator', [$validationManager, $rule]);
        $baseValidator->extendDefaultCustomErrors([
            'custom_case_key' => [
                'message' => 'custom case error',
                'overwrite' => false
            ]
        ]);
        $this->assertEquals('custom case error', $baseValidator->getCustomErrorMessage('custom_case_key'));
    }

    /** @test */
    public function it_return_error_message_if_overwrite_option_of_default_custom_error_set_false()
    {
        $validationManager = Mockery::mock('\UIS\Mvf\ValidationManager');
        $rule = Mockery::mock('\UIS\Mvf\ConfigItem');
        $rule->shouldReceive('isSetCustomError')->once()->andReturn(false);
        $rule->shouldReceive('getError')->once()->withNoArgs()->andReturn('general error');
        $baseValidator = $this->getMockForAbstractClass('\UIS\Mvf\ValidatorTypes\BaseValidator', [$validationManager, $rule]);
        $baseValidator->extendDefaultCustomErrors([
            'custom_case_key' => [
                'message' => 'custom case error',
                'overwrite' => false
            ]
        ]);
        $this->assertEquals('general error', $baseValidator->getCustomErrorMessage('custom_case_key'));
    }

    /** @test */
    public function it_return_default_custom_error_message_if_overwrite_option_of_default_custom_error_set_true()
    {
        $validationManager = Mockery::mock('\UIS\Mvf\ValidationManager');
        $rule = Mockery::mock('\UIS\Mvf\ConfigItem');
        $rule->shouldReceive('isSetCustomError')->once()->andReturn(false);
        $rule->shouldReceive('getError')->once()->withNoArgs()->andReturn('general error');
        $baseValidator = $this->getMockForAbstractClass('\UIS\Mvf\ValidatorTypes\BaseValidator', [$validationManager, $rule]);
        $baseValidator->extendDefaultCustomErrors([
            'custom_case_key' => [
                'message' => 'custom case error',
                'overwrite' => true
            ]
        ]);
        $this->assertEquals('custom case error', $baseValidator->getCustomErrorMessage('custom_case_key'));
    }
}
