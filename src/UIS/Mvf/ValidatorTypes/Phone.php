<?php

namespace UIS\Mvf\ValidatorTypes;

class Phone extends BaseValidator
{
    protected $defaultError = '{validation.error.phone.invalid}';

    public function validate()
    {
        $valueToValidate = $this->getVarValue();
        if (is_string($valueToValidate) === false) {
            return $this->makeError();
        }

        if (!preg_match('#^[0-9]{6,20}$#', $valueToValidate)) {
            return $this->makeError();
        }

        return $this->makeValid();
    }
}
