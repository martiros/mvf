<?php

namespace UIS\Mvf\ValidatorTypes;

use UIS\Mvf\Util;

class DateValidator extends BaseValidator
{
    protected $name = 'date';

    protected $params = [
        'max_date' => null,
        'min_date' => null,
    ];

    protected $defaultError = '{validation.error.date.invalid}';

    protected $defaultCustomErrors = [
        'min_date' => [
            'message' => '{validation.error.date.min_date}',
            'overwrite' => false,
        ],
        'max_date' => [
            'message' => '{validation.error.date.max_date}',
            'overwrite' => false,
        ],
    ];

    public function validate()
    {
        $valueToValidate = $this->getVarValue();
        if (!Util::isDate($valueToValidate)) {
            return $this->makeError('invalid_date');
        }

        if (isset($this->params['min_date'])) {
            if (strtotime($valueToValidate) < strtotime($this->params['min_date'])) {
                return $this->makeError('min_date');
            }
        }

        if (isset($this->params['max_date'])) {
            if (strtotime($valueToValidate) > strtotime($this->params['max_date'])) {
                return $this->makeError('max_date');
            }
        }

        return $this->makeValid();
    }
}
