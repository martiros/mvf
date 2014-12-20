<?php

namespace UIS\Mvf\ValidatorTypes;

use \UIS\Mvf\Util;

class Password extends BaseValidator
{
    protected $params = array(
        'min_length' => 6,
        'max_length' => null,
        'scLevel' => 'low',
    );

    protected $scLevelsList = array(
        'low',
        'medium',
        'height'
    );

    public function validate()
    {
        $valueToValidate = $this->getVarValue();
        if ($this->isValidLowPassword() === false) {
            return $this->makeError();
        }

        if ($this->params['scLevel'] === 'medium' && $this->isValidMediumPassword() === false) {
            return $this->makeError('notStrongMediumPassword');
        }

        if ($this->params['scLevel'] === 'height' && $this->isValidHeightPassword() === false) {
            return $this->makeError('notStrongHeightPassword');
        }

        $strLength = Util::strLen($valueToValidate);
        if ($this->params['min_length'] !== null && $this->params['min_length'] > $strLength) {
            return $this->makeError('min_length');
        }

        if ($this->params['max_length'] !== null && $this->params['max_length'] < $strLength) {
            return $this->makeError('max_length');
        }
        return $this->makeValid();
    }

    /**
     * Check is password valid'
     * @return bool
     */
    private function isValidLowPassword()
    {
        $value = $this->getVarValue();
        if (Util::isString($value) === false) {
            return false;
        }
        return true;
    }

    /**
     * Check is password level medium
     * @return bool
     */
    public function isValidMediumPassword()
    {
        if ($this->isContentNumbers() === true && $this->isContentLetters() === true) {
            return true;
        }
        return false;
    }

    /**
     * Check is password level height
     * @return bool
     */
    public function isValidHeightPassword()
    {
        if ($this->isContentNumbers() === true
            && $this->isContentLetters() === true
            && $this->isContentSpecialChars() === true
        ) {
            return true;
        }
        return false;
    }

    /**
     * Check is password content numbers
     * @return bool
     */
    private function isContentNumbers()
    {
        $valueToValidate = $this->getVarValue();
        if (preg_match('/[0-9]{1,}/i', $valueToValidate)) {
            return true;
        }
        return false;
    }

    /**
     * Check is password content letters
     * @return bool
     */
    private function isContentLetters()
    {
        $valueToValidate = $this->getVarValue();
        if (preg_match('/[A-Z]{1,}/i', $valueToValidate)) {
            return true;
        }
        return false;
    }

    /**
     * Check is password content special chart
     * @return bool
     */
    private function isContentSpecialChars()
    {
        $valueToValidate = $this->getVarValue();
        if (preg_match('/[^a-z0-9]{1,}/i', $valueToValidate)) {
            return true;
        }
        return false;
    }
}
