<?php

namespace UIS\Mvf\ValidatorTypes;

use UIS\Mvf\Util;

class DigitValidator extends BaseValidator
{
    protected $name = 'digit';

    protected $params = [
        'max_length' => null,
        'min_length' => null,
    ];

    protected $defaultError = '{validation.error.digit.invalid}';

    protected $defaultCustomErrors = [
        'min_length' => [
            'message' => '{validation.error.digit.min_length}',
            'overwrite' => false,
        ],
        'max_length' => [
            'message' => '{validation.error.digit.max_length}',
            'overwrite' => false,
        ],
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
