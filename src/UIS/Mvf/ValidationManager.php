<?php

namespace UIS\Mvf;

use UIS\Mvf\Exceptions\TerminateFiltersChain;
use UIS\Mvf\ValidatorTypes\BaseValidator;
use UIS\Mvf\FilterTypes\BaseFilter;

class ValidationManager
{
    /**
     * @var null
     */
    protected $data = null;

    /**
     * @var array
     */
    protected $rules = [];

    /**
     * @var ValidationResult
     */
    protected $validationResult = null;

    /**
     * @var string
     */
    protected $modelClass = null;

    /**
     * @var Object
     */
    protected $model = null;

    /**
     * @var bool
     */
    protected $filter = true;

    /**
     *  Array of registered validators, where
     *  array key is validator name, array value
     *  validator class name.
     * @var  array
     */
    protected static $registeredValidators = [
        'int' => '\UIS\Mvf\ValidatorTypes\Int',
        'digit' => '\UIS\Mvf\ValidatorTypes\Digit',
        'float' => '\UIS\Mvf\ValidatorTypes\Float',
        'bool' => '\UIS\Mvf\ValidatorTypes\Bool',
        'string' => '\UIS\Mvf\ValidatorTypes\String',
        'email' => '\UIS\Mvf\ValidatorTypes\Email',
        'password' => '\UIS\Mvf\ValidatorTypes\Password',
        'date' => '\UIS\Mvf\ValidatorTypes\Date',
        'url' => '\UIS\Mvf\ValidatorTypes\Url',
        'mvf' => '\UIS\Mvf\ValidatorTypes\Mvf',
        'array' => '\UIS\Mvf\ValidatorTypes\ArrayValidator',
        'enum' => '\UIS\Mvf\ValidatorTypes\Enum',
        'phone' => '\UIS\Mvf\ValidatorTypes\Phone',
        'function' => '\UIS\Mvf\ValidatorTypes\FunctionValidator',
        'username' => '\UIS\Mvf\ValidatorTypes\Username',
    ];

    protected static $registeredFilters = [
        'string' => '\UIS\Mvf\FilterTypes\String',
        'if' => '\UIS\Mvf\FilterTypes\IfFilter',
        'convert' => '\UIS\Mvf\FilterTypes\Convert',
        'terminate' => '\UIS\Mvf\FilterTypes\Terminate',
    ];

    /**
     * @return ValidationResult
     */
    public function validate()
    {
        $this->filter();

        $this->validationResult = $validationResult = new ValidationResult();
        foreach ($this->rules as $key => $rule) {
            $validateDataItem = array_key_exists($key, $this->data) ? $this->data[$key] : '';
            $validationError = $this->validateItem($validateDataItem, $rule);
            if (!$validationError->isValid()) {
                $validationResult->addError($key, $validationError);
            }
            $this->data[$key] = $validateDataItem;
        }

        // unset all not defined keys from data
        foreach ($this->data as $key => $data) {
            if (!isset($this->rules[$key])) {
                unset($this->data[$key]);
            }
        }
        $model = $this->getModel();
        if ($model) {
            $model->fill($this->data);
            $this->data = $model;
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
            try {
                foreach ($filtersList as $filterName => $filterParams) {
                    $validateDataItem = $this->filterItem($validateDataItem, $filterName, $filterParams);
                }
            } catch (TerminateFiltersChain $e) {
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
        } elseif ($validationError->isValid() && $validationRule->isSetDefaultValue()) {
            $validateVar = $validationRule->getDefaultValue();
        }

        if ($validationError->isValid()) {
            $onSuccess = $validationRule->getOnSuccessCallback();
            if (is_callable($onSuccess)) {
                if (call_user_func($onSuccess, $validateVar, $validationError, $this) === false) {
                    $validationError->setError($validatorObject->getValidationRule()->getError());
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
            throw new \UnexpectedValueException(" Filter method ( {$methodName} ) not exists,  $filterName ");
        }
        $filterObj->setVarValue($validateDataItem);

        return call_user_func(
            [
                $filterObj,
                $methodName,
            ],
            $filterParams
        );
    }

    //////////////////////////////////////////////////////////////////////////////////////////////////////
    //////////////////////////////////////////////////////////////////////////////////////////////////////
    //////////////////////////////////////////////////////////////////////////////////////////////////////
    //////////////////////////////////////////////////////////////////////////////////////////////////////
    //////////////////////////////////////////////////////////////////////////////////////////////////////
    //////////////////////////////////////////////////////////////////////////////////////////////////////

    public function __construct(&$data, $rules = [], $filter = true, $model = null)
    {
        $this->data = &$data;
        $this->data = is_array($this->data) ? $this->data : [];
        $this->filter = $filter;

        if (is_string($model)) {
            $this->modelClass = $model;
        } elseif (is_object($model)) {
            $this->modelClass = get_class($model);
            $this->model = $model;
        }

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

        return;
    }

    /**
     * @return ValidationResult
     */
    public function getValidationResult()
    {
        return $this->validationResult;
    }

    public function setData(&$data)
    {
        $this->data = &$data;
    }

    public function getModel()
    {
        if ($this->model === null) {
            if ($this->modelClass === null) {
                return;
            }
            $modelClassName = $this->modelClass;
            $this->model = new $modelClassName();
        }

        return $this->model;
    }

    /**
     * Register validator class.
     * @param   string $validatorName
     * @param   string $validatorClassNamgit s
     * @return  void
     */
    public static function registerValidator($validatorName, $validatorClassName)
    {
        self::$registeredValidators[$validatorName] = $validatorClassName;
    }

    /**
     * Register filter class.
     * @param   string $filterName
     * @param   string $filterClassName
     * @return  void
     */
    public static function registerFilter($filterName, $filterClassName)
    {
        self::$registeredFilters[$filterName] = $filterClassName;
    }

    /******************************************************************************************************************/
    /******************************************************************************************************************/
    /******************************************************************************************************************/
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
    private $params = [];

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

        return;
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
     *  Translate function name.
     * @var  string
     */
    protected static $transFunction = null;

    /**
     *  Set translate function name.
     * @return void
     */
    public static function setTransFunction($transFunction)
    {
        self::$transFunction = $transFunction;
    }

    public static function trans($transKey)
    {
        if (self::$transFunction === null) {
            return $transKey;
        }

        return call_user_func_array(self::$transFunction, func_get_args());
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
            throw new \UnexpectedValueException('Validator type not set in config rule-'.$rule->getKey());
        }

        $validatorObj = null;
        if (isset(self::$registeredValidators[$type])) {
            $validatorClass = self::$registeredValidators[$type];
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

        if (isset(self::$registeredFilters[$filterName])) {
            $filterClassName = self::$registeredFilters[$filterName];
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
        if (isset($names[1])) {
            return $names[1].'Filter';
        } else {
            return 'filter';
        }
    }

    protected function processValidationRules(array $rules)
    {
        $this->rules = [];
        foreach ($rules as $ruleKey => $ruleOptions) {
            $this->rules[$ruleKey] = new ConfigItem($ruleKey, $ruleOptions, $this);
        }
    }
}
