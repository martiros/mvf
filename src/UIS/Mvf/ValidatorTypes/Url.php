<?php

namespace UIS\Mvf\ValidatorTypes;

use UIS\Mvf\Util;

class Url extends BaseValidator
{
    public function validate()
    {
        $valueToValidate = $this->getVarValue();
        if (!Util::isUrl($valueToValidate)) {
            return $this->makeError();
        }
        return $this->makeValid();
    }
}
