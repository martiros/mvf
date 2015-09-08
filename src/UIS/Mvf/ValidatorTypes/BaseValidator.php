<?php

namespace UIS\Mvf\ValidatorTypes;

use UIS\Mvf\ValidationError;
use UIS\Mvf\ConfigItem;
use UIS\Mvf\ValidationManager;
use InvalidArgumentException;
use UnexpectedValueException;

abstract class BaseValidator
{
    /**
     * @var string
     */
    protected $name = null;

    /**
     * @var array
     */
    protected $params = [];

    /**
     * @var string
     */
    protected $defaultRequiredError = null;

    /**
     * @var string
     */
    protected $defaultError = 'Invalid data';

    /**
     * @var array
     */
    protected $defaultCustomErrors = [];

    /**
     * @var \UIS\Mvf\ValidationError
     */
    protected $error = null;

    /**
     * Is data required.
     * @var bool
     */
    protected $required = false;

    /**
     * @var \UIS\Mvf\ConfigItem
     */
    protected $rule = null;

    /**
     * @var \UIS\Mvf\ValidationManager
     */
    protected $validationManager = null;

    protected $mvf = null;

    /**
     *  @var  UIS_Mvf_Config_Item
     */
    protected $mvfConfigItem = null;

    public function __construct(ValidationManager $validationManager, ConfigItem $rule)
    {
        $this->rule = $rule;
        $this->validationManager = $validationManager;
        $this->resetError();
    }

    public function getName()
    {
        if ($this->name === null) {
            return strtolower(get_class($this));
        }
        return $this->name;
    }

    /**
     * @param array $params
     * @throws \UnexpectedValueException
     */
    public function setParams(array $params)
    {
        foreach ($params as $key => $param) {
            if (!array_key_exists($key, $this->params)) {
                throw new UnexpectedValueException("Invalid validator parameter-{$key}");
            }
            $this->params[$key] = $param;
        }
        $this->error->setParams($this->params);
    }

    /**
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * @return \UIS\Mvf\ConfigItem
     */
    public function getValidationRule()
    {
        return $this->rule;
    }

    /**
     * @return \UIS\Mvf\ValidationManager
     */
    public function getValidationManager()
    {
        return $this->validationManager;
    }

    /**
     *  @return \UIS\Mvf\ConfigItem
     */
    public function getConfig()
    {
        return    $this->mvfConfigItem;
    }

    /**
     *  Set validator config.
     *  @param     UIS_Mvf_Config_Item   $config
     *  @return    void
     */
    public function setConfig($config)
    {
        $this->mvfConfigItem = $config;
    }

    protected $varValue;

    public function getVarValue()
    {
        return $this->varValue;
    }

    public function setVarValue($newValue)
    {
        $this->varValue = $newValue;
    }

    public function setRequired($newVal)
    {
        $this->required = $newVal;
    }

    /**
     * @return \UIS\Mvf\ValidationError
     */
    abstract public function validate();

    /**
     * @return \UIS\Mvf\ValidationError
     */
    public function validateRequired()
    {
        if ($this->isEmpty()) {
            $this->error->setError($this->rule->getRequiredError())->setErrorCode('required_data');
        }

        return $this->error;
    }

    public function makeValid()
    {
        $this->resetError();

        return $this->error;
    }

    protected function resetError()
    {
        $this->error = new ValidationError($this);
    }

    public function makeError($customError = null)
    {
        $this->error->setError($this->getErrorMessage());
        if ($customErrorMessage = $this->getCustomErrorMessage($customError)) {
            $this->error->addCustomError($customError, $customErrorMessage);
        }

        return $this->error;
    }

    /**
     * @return string
     * @throws InvalidArgumentException
     */
    public function getErrorMessage()
    {
        if ($this->rule->getError() !== true) {
            return $this->rule->getError();
        } elseif ($this->defaultError !== null) {
            return $this->defaultError;
        }
        throw new InvalidArgumentException('MVF - Default error not found for validator - '.get_class($this));
    }

    public function getCustomErrorMessage($customError = null)
    {
        if ($customError === null) {
            return;
        }

        if ($this->rule->isSetCustomError($customError)) {
            return $this->rule->getCustomError($customError);
        } elseif (isset($this->defaultCustomErrors[$customError])) {
            if ($this->defaultCustomErrors[$customError]['overwrite'] === true || $this->rule->getError() === true) {
                return $this->defaultCustomErrors[$customError]['message'];
            }

            return $this->rule->getError();
        }

        return;
    }

    public function extendDefaultCustomErrors(array $defaultCustomErrors)
    {
        $this->defaultCustomErrors = array_merge($this->defaultCustomErrors, $defaultCustomErrors);
    }

    public function allowChangeData()
    {
        return false;
    }

    public function passEmptyData()
    {
        return false;
    }

    /**
     * @return bool
     */
    public function isEmpty()
    {
        $varValue = $this->getVarValue();
        if ($varValue === '' || $varValue === null) {
            return true;
        }

        return false;
    }
}
