<?php

namespace UIS\Mvf;

/**
 * Filtering and validating configuration
 * single item for variable.
 */
class ConfigItem
{
    /**
     *  Validator config key.
     * @var string
     */
    protected $key = null;

    /**
     *  Validator type.
     * @var     string
     */
    protected $type = null;

    /**
     * @var mixed
     */
    protected $defaultValue = null;

    /**
     * @var bool
     */
    protected $defaultValueExists = false;

    /**
     * Error if required.
     * @var  string|bool
     */
    protected $required = null;

    /**
     * Error message.
     * @var  string|bool
     */
    protected $error = null;

    /**
     * Custom errors list.
     * @var   array
     */
    protected $customErrors = [];

    /**
     * Validator params.
     * @var    array
     */
    protected $params = [];

    /**
     * Filters list.
     * @var array
     */
    protected $filters = [];

    /**
     * Validation success callback.
     * @var \Closure
     */
    protected $success = null;

    /**
     * Skip validation and filtering if property not set on data.
     *
     * @var bool
     */
    protected $skipIfNotExists = false;

    /**
     * @var \UIS\Mvf\ValidationManager
     */
    protected $validationManager = null;

    /**
     * @param $key
     * @param array $options
     * @param \UIS\Mvf\ValidationManager $validationManager
     */
    public function __construct($key, array $options, ValidationManager $validationManager)
    {
        array_walk_recursive(
            $options,
            function (&$item, $key) use ($validationManager) {
                if ($key !== 'function' && $key !== 'success' && is_object($item) && ($item instanceof Closure)) {
                    // $item = $item->bindTo($validationManager);
                    $item = call_user_func($item, $validationManager);
                }
            }
        );

        if (isset($options['params'])) {
            $this->params = $options['params'];
        }

        if (isset($options['type'])) {
            $this->type = $options['type'];
        }

        if (array_key_exists('default', $options)) {
            $this->defaultValue = $options['default'];
            $this->defaultValueExists = true;
        }

        $this->error = isset($options['error']) ? $options['error'] : true;

        if (isset($options['required']) && $options['required'] == true) {
            $this->required = $options['required'];
        }

        if (isset($options['filters'])) {
            $this->filters = $options['filters'];
        }

        if (isset($options['customErrors'])) {
            $this->customErrors = $options['customErrors'];
        }

        if (isset($options['success'])) {
            $this->success = $options['success'];
        }

        if (isset($options['skip_if_not_exists'])) {
            $this->skipIfNotExists = $options['skip_if_not_exists'];
        }

        $this->key = $key;
        $this->validationManager = $validationManager;
    }

    /**
     * Get item key.
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * Get validator type.
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return bool
     */
    public function isSetDefaultValue()
    {
        return $this->defaultValueExists;
    }

    /**
     * @return mixed
     */
    public function getDefaultValue()
    {
        return $this->defaultValue;
    }

    /**
     * Check is variable required.
     * @return bool True if required, else return false
     */
    public function isRequired()
    {
        if ($this->required === null) {
            return false;
        }

        return true;
    }

    /**
     * Get required error.
     * @return string
     */
    public function getRequiredError()
    {
        return $this->required;
    }

    /**
     * Get validator main error.
     * @return string
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * Check if custom error exists.
     * @param string $errorKey
     * @return bool True if exists, else return false
     */
    public function isSetCustomError($errorKey)
    {
        if (isset($this->customErrors[$errorKey])) {
            return true;
        }

        return false;
    }

    /**
     * Get custom error message by key.
     * @param string $errorKey
     * @return string|null
     */
    public function getCustomError($errorKey)
    {
        if (isset($this->customErrors[$errorKey])) {
            return $this->customErrors[$errorKey];
        }

        return;
    }

    public function getCustomErrors()
    {
        return $this->customErrors;
    }

    /**
     * Get validator parameters.
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * Check is set filters.
     * @return bool
     */
    public function isSetFilters()
    {
        if (empty($this->filters)) {
            return false;
        }

        return true;
    }

    /**
     * Get skipIfNotExists option.
     *
     * @return boolean
     */
    public function skipIfNotExists()
    {
       return $this->skipIfNotExists;
    }

    /**
     * Return array of filters, where
     * key is filter name, value is
     * filter parameters.
     * @return array
     */
    public function getFilters()
    {
        return $this->filters;
    }

    public function getOnSuccessCallback()
    {
        return $this->success;
    }

    /**
     * @return \UIS\Mvf\ValidationManager
     */
    public function getValidationManager()
    {
        return $this->validationManager;
    }
}
