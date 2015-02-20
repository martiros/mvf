<?php

namespace UIS\Mvf\ValidatorTypes;

use UIS\Mvf\ValidationManager;
use UIS\Mvf\ValidationResult;

class ArrayValidator extends BaseValidator
{
    protected $params = array(
        'min_length' => 0,
        'max_length' => null,
        'allowed_values' => null,
        'array_unique' => false,
        'items_validator' => null,
    );

    public function validate()
    {
        $arrayToValidate = $this->getVarValue();
        if (!is_array($arrayToValidate)) {
            return $this->makeError();
        }

        if ($this->params['allowed_values'] !== null) {
            foreach ($arrayToValidate as $arrayItem) {
                if (!in_array($arrayItem, $this->params['allowed_values'])) {
                    return $this->makeError('not_allowed_value');
                }
            }
        }

        if ($this->params['array_unique']) {
            if (count(array_unique($arrayToValidate, SORT_REGULAR)) !== count($arrayToValidate)) {
                return $this->makeError('not_unique');
            }
        }

        if ($this->params['min_length']) {
            if (count($arrayToValidate) < $this->params['min_length']) {
                return $this->makeError('invalid_min_length');
            }
        }

        if ($this->params['max_length']) {
            if (count($arrayToValidate) > $this->params['max_length']) {
                return $this->makeError('invalid_max_length');
            }
        }

        if ($this->params['items_validator'] !== null) {
            $validationResult = new ValidationResult();
            $model = null;
            if (is_array($this->params['items_validator'])) {
                if (isset($this->params['items_validator']['mapping'])) {
                    $validatorRules = $this->params['items_validator']['mapping'];
                    if (isset($this->params['items_validator']['model'])) {
                        $model = $this->params['items_validator']['model'];
                    }
                } else {
                    $validatorRules = $this->params['items_validator'];
                }
            } else {
                $validatorRules = $this->params['items_validator'];
            }
            foreach ($arrayToValidate as $key => &$subArray) {
                if (!is_array($subArray)) {
                    // @TODO: Move to default translates
                    $validationResult->addError($key, 'data is not array');
                    continue;
                }
                $validator = new ValidationManager($subArray, $validatorRules, true, $model);
                $validator->setParent($this->getValidationManager());
                $subArrayValidationResult = $validator->validate();
                if (!$subArrayValidationResult->isValid()) {
                    $validationResult->addError($key, $subArrayValidationResult);
                }
            }

//            if ($validationResult->isValid()) {
//                $validationError = $this->makeValid();
//                $onSuccess = $this->getValidationRule()->getOnSuccessCallback();
//                $onSuccess($arrayToValidate, $validationError, $this->getValidationManager());
//                if (!$validationError->isValid()) {
//                    $this->setVarValue($arrayToValidate);
//                    return $validationError;
//                }
//            }
            $this->setVarValue($arrayToValidate);
            if (!$validationResult->isValid()) {
                return $validationResult;
            }
        }
        return $this->makeValid();
    }

    public function allowChangeData()
    {
        return true;
    }
}
