<?php

namespace UIS\Mvf\ValidatorTypes;

use UIS\Mvf\Util;

class Float extends BaseValidator
{
    protected $params = [
        'max_value' => null,
        'min_value' => null,
        'max_decimals' => null,
    ];

    public function validate()
    {
        $valueToValidate = $this->getVarValue();
        if (!Util::isFloat($valueToValidate)) {
            return $this->makeError('invalid_data');
        }

        if ($this->params['min_value'] !== null && $this->params['min_value'] > $valueToValidate) {
            return $this->makeError('min_value');
        }

        if ($this->params['max_value'] !== null && $this->params['max_value'] < $valueToValidate) {
            return $this->makeError('max_value');
        }

        if ($this->params['max_decimals'] !== null && $this->params['max_decimals'] < $this->getDecimalsCount()) {
            return $this->makeError('max_decimals');
        }

        return $this->makeValid();
    }

    private function getDecimalsCount()
    {
        $value = $this->getVarValue();
        $value = floatval($value);
        $value = explode('.', $value.'');
        $value = isset($value[1]) ? $value[1] : '0';

        return strlen($value);
    }
}
