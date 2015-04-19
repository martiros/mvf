<?php
namespace UIS\Mvf\ValidatorTypes;

use UIS\Mvf\Util;

class Username extends BaseValidator
{
    protected $params = [
        'min_length' => 6,
        'max_length' => 40
    ];

    public function validate()
    {
        $username = $this->getVarValue();
        if (!$this->checkIsContainValidChars($username)) {
            return $this->makeError();
        }

        $length = Util::strLen($username);

        if ($this->params['min_length'] !== null && $length < $this->params['min_length']) {
            return $this->makeError('min_length');
        }

        if ($this->params['max_length'] !== null && $length > $this->params['max_length']) {
            return $this->makeError('max_length');
        }

        if (!$this->isValidFirstChar()) {
            return $this->makeError('first_char');
        }

        if (!$this->isValidEndChar()) {
            return $this->makeError('end_char');
        }

        if ($this->isContainPeriodsPunctuation()) {
            return $this->makeError('punctuation');
        }
        return $this->makeValid();
    }

    /**
     * @return bool
     */
    protected function checkIsContainValidChars()
    {
        $username = $this->getVarValue();
        if (!Util::isString($username)) {
            return false;
        }

        preg_match( '/[^A-Z0-9\.]{1,}/i', $username, $matches);
        if (!empty($matches)) {
            return false;
        }
        return true;
    }

    /**
     * @return bool
     */
    protected function isValidFirstChar()
    {
        $username = $this->getVarValue();
        if (strpos($username, '.') === 0) {
            return false;
        }
        return true;
    }

    /**
     * @return bool
     */
    protected function isValidEndChar()
    {
        $username = $this->getVarValue();
        if ('.' === substr($username, -1)) {
            return false;
        }
        return true;
    }

    /**
     * @return bool
     */
    protected function isContainPeriodsPunctuation()
    {
        $username = $this->getVarValue();
        return strpos($username, '..') !== false;
    }
}
