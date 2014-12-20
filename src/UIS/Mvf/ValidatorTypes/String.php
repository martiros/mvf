<?php

namespace UIS\Mvf\ValidatorTypes;

use \UIS\Mvf\Util;

class String extends BaseValidator
{
    protected $params = array(
        'regexp' => null,
        'max_length' => null,
        'min_length' => null,
        'encoding' => 'UTF-8' // Validator will replace all non UTF-8 characters, set null to disable this feature
    );

    public function validate()
    {
        $valueToValidate = $this->getVarValue();
        if (is_string($valueToValidate) === false) {
            return $this->makeError();
        }
        $this->correctEncoding();
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

    protected function correctEncoding()
    {
        if ($this->params['encoding']==='UTF-8') {
            $string = $this->getVarValue();
            $string = preg_replace('/[\xF0-\xF7].../s', '', $string); // remove all not UTF-8 characters and ( hard code for utf_general_ci, FIXME )
            $string = preg_replace('/[^(\x20-\x7F)]*/','', $string); // strip all 4byte strings (emoji) FIXME
            $this->setVarValue($string);
        }
    }

    public function allowChangeData()
    {
        return true;
    }
}
