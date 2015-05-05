<?php

namespace UIS\Mvf\ValidatorTypes;

use \UIS\Mvf\Util;

class String extends BaseValidator
{
    protected $params = array(
        'regexp' => null,
        'max_length' => null,
        'min_length' => null,
    );

    public function validate()
    {
        $valueToValidate = $this->getVarValue();
        if (is_string($valueToValidate) === false) {
            return $this->makeError();
        }

        if ($this->rule->isRequired()) {
            $validationResult = $this->validateRequired();
            if (!$validationResult->isValid()) {
                return $validationResult;
            }
        }
        $strLength = Util::strLen($valueToValidate);

        if ($this->params['min_length'] !== null && $strLength < $this->params['min_length']) {
            return $this->makeError('min_length');
        }

        if ($this->params['max_length'] !== null && $strLength > $this->params['max_length']) {
            return $this->makeError('max_length');
        }

        if ($this->params['regexp'] !== null) {
            if (!preg_match($this->params['regexp'], $valueToValidate)) {
                return $this->makeError('regexp');
            }
        }
        return $this->makeValid();
    }
}
