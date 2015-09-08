<?php

namespace UIS\Mvf;

class ValidationError
{
    protected $mainError = null;

    protected $requiredError = null;

    protected $errorsArray = [];

    protected $params = [];

    protected $validator;

    protected $errorCode = null;

    public function __construct($validator = null)
    {
        $this->validator = $validator;
    }

    public function errorMessage()
    {
        if ($this->requiredError  !== null) {
            return $this->requiredError;
        }

        if (!empty($this->errorsArray)) {
            return implode($this->errorsArray, ', ');
        }

        return $this->mainError;
    }

    public function setError($mainError)
    {
        $this->mainError = $mainError;

        return $this;
    }

    public function addCustomError($key, $value)
    {
        if ($value != null) {
            $this->errorsArray[ $key ] = $value;
        }
    }

    public function getErrorSource()
    {
        return $this->getCustomErrorKey();
    }

    public function getCustomErrorKey()
    {
        reset($this->errorsArray);
        return key($this->errorsArray);
    }

    public function getErrorCode()
    {
        if ($this->errorCode) {
            return $this->errorCode;
        }

        $errorCode = null;
        if ($this->validator === null) {
            return null;
        }

        $errorCode = 'invalid.'.$this->validator->getName();
        $customErrorKey = $this->getCustomErrorKey();
        return $customErrorKey === null ? $errorCode : $errorCode.'.'.$customErrorKey;
    }

    public function setErrorCode($errorCode)
    {
        $this->errorCode = $errorCode;

        return $this;
    }

    /**
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * @param array $params
     */
    public function setParams($params)
    {
        $this->params = $params;
    }

    /**
     *  @return bool
     */
    public function isValid()
    {
        if ($this->mainError == null && $this->requiredError == null  && empty($this->errorsArray)) {
            return true;
        }

        return false;
    }

    public function jsonSerialize()
    {
        $errors = $this->errors();
        if (empty($errors)) {
            return;
        }

        return  ($errors);
    }
}
