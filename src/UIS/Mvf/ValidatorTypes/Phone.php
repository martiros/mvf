<?php

namespace UIS\Mvf\ValidatorTypes;

class Phone extends BaseValidator
{
    public function validate()
    {
        $valueToValidate = $this->getVarValue();
        if (is_string($valueToValidate) === false) {
            return $this->makeError();
        }

        if (!preg_match("#^[0-9\+x]{11,16}$#", $valueToValidate)) {
            return $this->makeError();
        }
        return $this->makeValid();
    }
}
