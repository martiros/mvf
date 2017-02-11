<?php

namespace UIS\Mvf\ValidatorTypes;

class BoolValidator extends BaseValidator
{
    protected $name = 'boolean';

    protected $defaultError = '{validation.error.bool.invalid}';

    public function validate()
    {
        $valueToValidate = $this->getVarValue();
        if (!is_bool($valueToValidate)) {
            return $this->makeError();
        }

        return $this->makeValid();
    }
}
