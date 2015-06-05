<?php

namespace UIS\Mvf\ValidatorTypes;

use UIS\Mvf\Util;

class Url extends BaseValidator
{
    protected $defaultError = '{validation.error.url.invalid}';

    public function validate()
    {
        $valueToValidate = $this->getVarValue();
        if (!Util::isUrl($valueToValidate)) {
            return $this->makeError();
        }
        return $this->makeValid();
    }
}
