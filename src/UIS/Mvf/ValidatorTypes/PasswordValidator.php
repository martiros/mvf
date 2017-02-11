<?php

namespace UIS\Mvf\ValidatorTypes;

use UIS\Mvf\Util;
use InvalidArgumentException;

class PasswordValidator extends BaseValidator
{
    protected $name = 'password';

    protected $params = [
        'min_length' => 6,
        'max_length' => 100,
        'security_level' => 'low',
    ];

    protected $securityLevels = [
        'low',
        'medium',
        'height',
    ];

    protected $defaultError = '{validation.error.password.invalid}';

    protected $defaultCustomErrors = [
        'min_length' => [
            'message' => '{validation.error.password.min_length}',
            'overwrite' => false,
        ],
        'max_length' => [
            'message' => '{validation.error.password.max_length}',
            'overwrite' => false,
        ],
        'security_level_not_medium' => [
            'message' => '{validation.error.password.security_level_not_medium}',
            'overwrite' => false,
        ],
        'security_level_not_height' => [
            'message' => '{validation.error.password.security_level_not_height}',
            'overwrite' => false,
        ],
    ];

    public function validate()
    {
        if ($this->isValidLowPassword() === false) {
            return $this->makeError();
        }

        if ($error = $this->validatePasswordLength()) {
            return $error;
        }

        if ($error = $this->validatePasswordSecurityLevel()) {
            return $error;
        }

        return $this->makeValid();
    }

    /**
     * @return \UIS\Mvf\ValidationError
     */
    protected function validatePasswordLength()
    {
        $password = $this->getVarValue();
        $strLength = Util::strLen($password);

        // Validate max length of the password.
        if ($this->params['min_length'] !== null && $this->params['min_length'] > $strLength) {
            return $this->makeError('min_length');
        }

        // Validate max length of the password.
        if ($this->params['max_length'] !== null && $this->params['max_length'] < $strLength) {
            return $this->makeError('max_length');
        }

        return;
    }

    protected function validatePasswordSecurityLevel()
    {
        if (!in_array($this->params['security_level'], $this->securityLevels)) {
            throw new InvalidArgumentException('Invalid parameter security_level-'.$this->params['security_level'].'. Class - '.__CLASS__);
        }

        if ($this->params['security_level'] === 'medium' && $this->isValidMediumPassword() === false) {
            return $this->makeError('security_level_not_medium');
        }

        if ($this->params['security_level'] === 'height' && $this->isValidHeightPassword() === false) {
            return $this->makeError('security_level_not_height');
        }

        return;
    }

    /**
     * Check is password valid'.
     * @return bool
     */
    protected function isValidLowPassword()
    {
        $value = $this->getVarValue();
        if (Util::isString($value) === false) {
            return false;
        }

        return true;
    }

    /**
     * Check is password level medium.
     * @return bool
     */
    protected function isValidMediumPassword()
    {
        if ($this->isContentNumbers() === true && $this->isContentLetters() === true) {
            return true;
        }

        return false;
    }

    /**
     * Check is password level height.
     * @return bool
     */
    protected function isValidHeightPassword()
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
     * Check is password content numbers.
     * @return bool
     */
    protected function isContentNumbers()
    {
        $password = $this->getVarValue();
        if (preg_match('/[0-9]{1,}/i', $password)) {
            return true;
        }

        return false;
    }

    /**
     * Check is password content letters.
     * @return bool
     */
    protected function isContentLetters()
    {
        $password = $this->getVarValue();
        if (preg_match('/[A-Z]{1,}/i', $password)) {
            return true;
        }

        return false;
    }

    /**
     * Check is password content special chart.
     * @return bool
     */
    protected function isContentSpecialChars()
    {
        $password = $this->getVarValue();
        if (preg_match('/[^a-z0-9]{1,}/i', $password)) {
            return true;
        }

        return false;
    }
}
