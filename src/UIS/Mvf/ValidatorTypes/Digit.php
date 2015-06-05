<?php

namespace UIS\Mvf\ValidatorTypes;

use UIS\Mvf\Util;

class Digit extends BaseValidator
{
    protected $params = [
        'max_length' => null,
        'min_length' => null,
    ];

    public function validate()
    {
        $valueToValidate = $this->getVarValue();
        if (!is_string($valueToValidate) && !is_int($valueToValidate)) {
            return $this->makeError();
        }
        $valueToValidate .= '';
        if (!Util::isDigit($valueToValidate)) {
            return $this->makeError();
        }

        $strLength = Util::strLen($valueToValidate);
        if ($this->params['min_length'] !== null && $strLength < $this->params['min_length']) {
            return $this->makeError('min_length');
        }

        if ($this->params['max_length'] !== null && $strLength > $this->params['max_length']) {
            return $this->makeError('max_length');
        }

        return $this->makeValid();
    }
}
