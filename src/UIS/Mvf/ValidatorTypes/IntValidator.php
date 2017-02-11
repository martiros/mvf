<?php

namespace UIS\Mvf\ValidatorTypes;

use UIS\Mvf\Util;

class IntValidator extends BaseValidator
{
    protected $name = 'int';

    protected $params = [
        'max_value' => null,
        'min_value' => null,
    ];

    protected $defaultError = '{validation.error.int.invalid}';

    protected $defaultCustomErrors = [
        'min_value' => [
            'message' => '{validation.error.int.min_value}',
            'overwrite' => false,
        ],
        'max_value' => [
            'message' => '{validation.error.int.max_value}',
            'overwrite' => false,
        ],
    ];

    public function validate()
    {
        $valueToValidate = $this->getVarValue();
        if (!Util::isInt($valueToValidate)) {
            return $this->makeError('invalid_data');
        }

        if ($this->params['min_value'] !== null && $this->params['min_value'] > $valueToValidate) {
            return $this->makeError('min_value');
        }

        if ($this->params['max_value'] !== null && $this->params['max_value'] < $valueToValidate) {
            return $this->makeError('max_value');
        }

        return $this->makeValid();
    }
}
