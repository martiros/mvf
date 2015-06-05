<?php

namespace UIS\Mvf\ValidatorTypes;

use UIS\Mvf\Util;

class Email extends BaseValidator
{
    public function validate()
    {
        $valueToValidate = $this->getVarValue();
        if (Util::isEmail($valueToValidate) === false) {
            return $this->makeError();
        }

        return $this->makeValid();
    }
}
