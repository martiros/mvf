<?php

namespace UIS\Mvf\ValidatorTypes;

use \UIS\Mvf\Util;

class Int extends BaseValidator
{
    protected $params = array(
        'max_value' => null,
        'min_value' => null,
    );

    public function validate()
    {
        $valueToValidate = $this->getVarValue();
        if (Util::isInt($valueToValidate)) {
            return $this->makeError('invalidData');
        }

        if ($this->params['min_value'] !== null && $this->params['min_value'] > $valueToValidate) {
            return $this->makeError('min_value');
        }

        if ($this->params['max_value'] !== null && $this->params['max_value'] > $valueToValidate) {
            return $this->makeError('max_value');
        }
        return $this->makeValid();
    }
}
