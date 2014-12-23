<?php

namespace UIS\Mvf\ValidatorTypes;

use InvalidArgumentException;

class FunctionValidator extends BaseValidator
{
    protected $params = array(
        'function' => null
    );

    public function validate()
    {
        $this->validateParams();
        $valueToValidate = $this->getVarValue();
        if (!call_user_func($this->params['function'], $valueToValidate)) {
            return $this->makeError();
        }
        return $this->makeValid();
    }

    protected function validateParams()
    {
        if (!isset($this->params['function'])) {
            throw new InvalidArgumentException("you must specify 'function' parameter in params list");
        }

        if (!is_callable($this->params['function'])) {
            throw new InvalidArgumentException(" 'function' parameter is not callable");
        }
    }
}
