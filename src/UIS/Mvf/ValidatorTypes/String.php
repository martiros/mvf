<?php

namespace UIS\Mvf\ValidatorTypes;

use UIS\Mvf\Util;

class String extends BaseValidator
{
    protected $name = 'string';

    protected $params = [
        'regexp' => null,
        'max_length' => null,
        'min_length' => null,
    ];

    protected $defaultError = '{validation.error.string.invalid}';

    protected $defaultCustomErrors = [
        'min_length' => [
            'message' => '{validation.error.string.min_length}',
            'overwrite' => false,
        ],
        'max_length' => [
            'message' => '{validation.error.string.max_length}',
            'overwrite' => false,
        ],
        'regexp' => [
            'message' => '{validation.error.string.regexp}',
            'overwrite' => false,
        ],
    ];

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
