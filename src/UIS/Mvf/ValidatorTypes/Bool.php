<?php

namespace UIS\Mvf\ValidatorTypes;

class Bool extends BaseValidator
{
    protected $name = 'boolean';

    public function validate()
    {
        $valueToValidate = $this->getVarValue();
        if (!is_bool($valueToValidate)) {
            return $this->makeError();
        }

        return $this->makeValid();
    }
}
