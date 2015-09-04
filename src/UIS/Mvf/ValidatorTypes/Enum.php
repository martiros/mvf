<?php

namespace UIS\Mvf\ValidatorTypes;

use InvalidArgumentException;

class Enum extends BaseValidator
{
    protected $name = 'enum';

    protected $params = [
        'values' => null,
    ];

    public function validate()
    {
        if (!is_array($this->params['values'])) {
            throw new InvalidArgumentException('Enum param "values" must be an array of possible values.');
        }
        $valueToValidate = $this->getVarValue();
        if (in_array($valueToValidate, $this->params['values'])) {
            return $this->makeValid();
        }

        return $this->makeError();
    }
}
