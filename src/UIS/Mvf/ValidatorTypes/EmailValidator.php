<?php

namespace UIS\Mvf\ValidatorTypes;

use UIS\Mvf\Util;

class EmailValidator extends BaseValidator
{
    protected $name = 'email';

    protected $defaultError = '{validation.error.email.invalid}';

    public function validate()
    {
        $valueToValidate = $this->getVarValue();
        if (Util::isEmail($valueToValidate) === false) {
            return $this->makeError();
        }

        return $this->makeValid();
    }
}
