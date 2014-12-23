<?php

namespace UIS\Mvf;

use \UIS\Mvf\ValidatorTypes\BaseValidator;
use \UIS\Mvf\FilterTypes\BaseFilter;

class ValidationManager
{
    /**
     * @var null
     */
    protected $data = null;

    /**
     * @var array
     */
    protected $rules = array();

    /**
     * @var bool
     */
    protected $filter = true;

    protected $registeredValidators = array(
        'int' => '\UIS\Mvf\ValidatorTypes\Int',
        'digit' => '\UIS\Mvf\ValidatorTypes\Digit',
        'bool' => '\UIS\Mvf\ValidatorTypes\Bool',
        'string' => '\UIS\Mvf\ValidatorTypes\String',
        'email' => '\UIS\Mvf\ValidatorTypes\Email',
        'password' => '\UIS\Mvf\ValidatorTypes\Password',
        'date' => '\UIS\Mvf\ValidatorTypes\Date',
        'url' => '\UIS\Mvf\ValidatorTypes\Url',
        'mvf' => '\UIS\Mvf\ValidatorTypes\Mvf',
        'array' => '\UIS\Mvf\ValidatorTypes\ArrayValidator',
        'phone' => '\UIS\Mvf\ValidatorTypes\Phone'
    );

    protected $registeredFilters = array(
        'string' => '\UIS\Mvf\FilterTypes\String',
        'if' => '\UIS\Mvf\FilterTypes\IfFilter',
    );

    /**
     * @return ValidationResult
     */
    public function validate()
    {
        $this->filter();

        $validationResult = new ValidationResult();
        foreach ($this->rules as $key => $rule) {
            $validateDataItem = array_key_exists($key, $this->data) ? $this->data[$key] : '';
            $validationError = $this->validateItem($validateDataItem, $rule);
            if (!$validationError->isValid()) {
                $validationResult->addError($key, $validationError);
            }
            $this->data[$key] = $validateDataItem;
        }

        // unset all not defined keys from data
        foreach ($this->data AS $key => $data) {
            if (!isset($this->rules[$key])) {
                unset($this->data[$key]);
            }
        }
        return $validationResult;
    }

    public function filter()
    {
        if (!$this->filter) {
            return;
        }
        foreach ($this->rules as $key => $rule) {
            if (!$rule->isSetFilters()) {
                continue;
            }
            $validateDataItem = array_key_exists($key, $this->data) ? $this->data[$key] : '';
            $filtersList = $rule->getFilters();
            foreach ($filtersList as $filterName => $filterParams) {
                $validateDataItem = $this->filterItem($validateDataItem, $filterName, $filterParams);
            }
            $this->data[$key] = $validateDataItem;
        }
    }

    /**
     * @param mixed $validateVar
     * @param \UIS\Mvf\ConfigItem $validationRule
     * @return \UIS\Mvf\ValidationError
     */
    protected function validateItem(&$validateVar, ConfigItem $validationRule)
    {
        $validatorObject = $this->getValidatorObj($validationRule);
        $validatorObject->setVarValue($validateVar);
        $validationError = $validatorObject->validateRequired();
        if ($validationError->isValid() && !$validatorObject->isEmpty()) {
            $validationError = $validatorObject->validate();
            if ($validatorObject->allowChangeData()) {
                $validateVar = $validatorObject->getVarValue();
            }
        } else if($validationError->isValid() && $validationRule->isSetDefaultValue()) {
            $validateVar = $validationRule->getDefaultValue();
        }

        if ($validationError->isValid()) {
            $onSuccess = $validationRule->getOnSuccessCallback();
            if (!empty($onSuccess)) {
                if (is_object($onSuccess) && ($onSuccess instanceof Closure)) {
                    $onSuccess = $onSuccess->bindTo($validationRule->getValidator());
                    $onSuccess($validateVar, $validationError);
                }
            }
        }
        return $validationError;
    }

    /**
     * @param $validateDataItem
     * @param $filterName
     * @param $filterParams
     * @return mixed
     * @throws \UnexpectedValueException
     */
    protected function filterItem($validateDataItem, $filterName, $filterParams)
    {
        $filterObj = $this->getFilterObj($filterName);
        $methodName = $this->getFilterClassMethod($filterName);

        if (!method_exists($filterObj, $methodName)) {
            throw new \UnexpectedValueException (" Filter method ( {$methodName} ) not exists,  $filterName ");
        }
        $filterObj->setVarValue($validateDataItem);
        return call_user_func(
            array(
                $filterObj,
                $methodName
            ),
            $filterParams
        );
    }

    //////////////////////////////////////////////////////////////////////////////////////////////////////
    //////////////////////////////////////////////////////////////////////////////////////////////////////
    //////////////////////////////////////////////////////////////////////////////////////////////////////
    //////////////////////////////////////////////////////////////////////////////////////////////////////
    //////////////////////////////////////////////////////////////////////////////////////////////////////
    //////////////////////////////////////////////////////////////////////////////////////////////////////

    public function __construct(&$data, $rules = array(), $filter = true)
    {
        $this->data = & $data;
        $this->data = is_array($this->data) ? $this->data : array();
        $this->filter = $filter;
        $this->processValidationRules($rules);
    }

    public function getData($key = null)
    {
        if ($key === null) {
            return $this->data;
        }

        if (isset($this->data[$key])) {
            return $this->data[$key];
        }
        return null;
    }

    public function setData(&$data)
    {
        $this->data = & $data;
    }


    /**
     * Register filter class
     * @param   string $filterName
     * @param   string $filterClassName
     * @return  void
     */
    public function registerFilter($filterName, $filterClassName)
    {
        $this->registeredFilters[$filterName] = $filterClassName;
    }

    /******************************************************************************************************************/
    /******************************************************************************************************************/
    /******************************************************************************************************************/

    /**
     *  Array of registered validators, where
     *  array key is falidator name, array value
     *  validator class name
     * @var  array
     */
    // private $registeredValidators = array();

    /**
     * Register validator class
     * @param   string $validatorName
     * @param   string $validatorClassName
     * @return  void
     */
    /*
    public function registerFilter( $validatorName , $validatorClassName )  {
        $this->registeredValidators[ $validatorName ] = $validatorClassName;
    }
    */


    //----------------------------------------------------------------------------------


    /******************************************************************************************************************/
    /******************************************************************************************************************/
    /******************************************************************************************************************/
    /******************************************************************************************************************/
    /******************************************************************************************************************/
    /******************************************************************************************************************/
    /******************************************************************************************************************/


    /**
     * @var UIS_Mvf_Config
     */
    private $config = null;

    /**
     * @var array
     */
    private $params = array();

    /**
     * @var UIS_Mvf
     */
    private $parent = null;

    /**
     * @param string|null $key
     * @return array|null
     */
    public function getParam($key = null)
    {
        if ($key === null) {
            return $this->params;
        }

        if (isset($this->params[$key])) {
            return $this->params[$key];
        }
        return null;
    }

    /**
     * @param string $key
     * @param mixed $value
     */
    public function setParam($key, $value)
    {
        $this->params[$key] = $value;
    }


    /**
     * @return UIS_Mvf_Config
     */
    public function getConfig()
    {
        return $this->config;
    }


    public function getParent()
    {
        return $this->parent;
    }

    public function setParent($parent)
    {
        $this->parent = $parent;
    }

    /**
     *  Translate function name
     * @var  string
     */
    private $transFunction = 'trans';


    /**
     *  Set translate function name
     * @return void
     */
    public function setTransFunction($transFunction)
    {
        $this->transFunction = $transFunction;
    }

    /******************************************************************************************************************/
    /******************************************************************************************************************/
    /******************************************************************************************************************/
    /******************************************************************************************************************/
    /******************************************************************************************************************/
    /******************************************************************************************************************/
    /******************************************************************************************************************/
    /******************************************************************************************************************/

    /**
     * @param \UIS\Mvf\ConfigItem $rule
     * @return \UIS\Mvf\ValidatorTypes\BaseValidator
     * @throws \UnexpectedValueException
     */
    protected function getValidatorObj(ConfigItem $rule)
    {
        $type = $rule->getType();
        if ($type === null) {
            throw new \UnexpectedValueException('Validator type not set in config rule-' . $rule->getKey());
        }

        $validatorObj = null;
        if (isset($this->registeredValidators[$type])) {
            $validatorClass = $this->registeredValidators[$type];
        } else {
            $validatorClass = $type;
        }

        if (!class_exists($validatorClass)) {
            throw new \UnexpectedValueException("Validator($validatorClass) class not found.");
        }

        $validatorObj = new $validatorClass($this, $rule);
        if (!$validatorObj instanceof BaseValidator) {
            throw new \UnexpectedValueException("Validator ( $validatorClass ) Not Valid : must extends from \\UIS\\Mvf\\ValidatorTypes\\BaseValidator");
        }
        $validatorObj->setParams($rule->getParams());
        return $validatorObj;
    }

    private function getFilterObj($filterName)
    {
        $names = explode('.', $filterName);
        $filterName = $names[0];

        if (isset($this->registeredFilters[$filterName])) {
            $filterClassName = $this->registeredFilters[$filterName];
        } else {
            $filterClassName = $filterName;
        }

        if (!class_exists($filterClassName)) {
            throw new \UnexpectedValueException("Filter ($filterClassName) class not found.");
        }

        $filterObj = new $filterClassName();
        if (!($filterObj instanceof BaseFilter)) {
            throw new \UnexpectedValueException("Filter ( $filterClassName ) Not Valid : must extends from \\UIS\\Mvf\\FilterTypes\\BaseFilter");
        }
        return $filterObj;
    }

    private function getFilterClassMethod($name)
    {
        $names = explode('.', $name);
        if (isset ($names[1])) {
            return $names[1] . 'Filter';
        } else {
            return 'filter';
        }
    }

    protected function processValidationRules(array $rules)
    {
        $this->rules = array();
        foreach ($rules as $ruleKey => $ruleOptions) {
            $this->rules[$ruleKey] = new ConfigItem($ruleKey, $ruleOptions, $this);
        }
    }
}
