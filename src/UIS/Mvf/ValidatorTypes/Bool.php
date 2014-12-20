<?php

namespace UIS\Mvf\ValidatorTypes;

class Bool extends BaseValidator
{
    public function validate()
    {
        $valueToValidate = $this->getVarValue();
        if (!is_bool($valueToValidate)) {
            return $this->makeError();
        }
        return $this->makeValid();
    }
}
