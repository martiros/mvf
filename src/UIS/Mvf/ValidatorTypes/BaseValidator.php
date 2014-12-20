<?php

namespace UIS\Mvf\ValidatorTypes;

use UIS\Mvf\ValidationError;
use UIS\Mvf\ConfigItem;
use UIS\Mvf\ValidationManager;

abstract class BaseValidator
{
    /**
     * @var array
     */
    protected $params = array();

    /**
     * @var \UIS\Mvf\ValidationError
     */
    protected $error = null;

    /**
     * @var \UIS\Mvf\ConfigItem
     */
    protected $rule = null;

    /**
     * @var \UIS\Mvf\ValidationManager
     */
    protected $validationManager = null;

    public function __construct(ValidationManager $validationManager, ConfigItem $rule)
    {
        $this->resetError();
        $this->rule = $rule;
        $this->validationManager = $validationManager;
    }

    protected function resetError()
    {
        $this->error = new ValidationError();
        $this->error->setParams($this->getParams());
    }

    /**
     * @param array $params
     * @throws \UnexpectedValueException
     */
    public function setParams(array $params)
    {
        foreach ($params as $key => $param) {
            if (!array_key_exists($key, $this->params)) {
                throw new \UnexpectedValueException("Invalid validator parameter-{$key}" );
            }
            $this->params[$key] = $param;
        }
    }

    /**
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * @return bool
     */
    public function isEmpty()
    {
        $varValue = $this->getVarValue();
        if( $varValue === '' || $varValue===null ){
            return true;
        }
        return false;
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
            $this->error->setError($this->rule->getRequiredError());
        }
        return $this->error;
    }

    public function makeError()
    {
        $this->error->setError($this->rule->getError());
        return $this->error;
    }

    public function makeValid()
    {
        $this->resetError();
        return $this->error;
    }

    public function allowChangeData()
    {
        return false;
    }

    /******************************************************************************************************************/
    /******************************************************************************************************************/
    /******************************************************************************************************************/

    private $mvf = null;

    /**
     *  @var  UIS_Mvf_Config_Item
     */
    protected $mvfConfigItem = null;

    /**
     *  @return UIS_Mvf_Config_Item
     */
    public function getConfig() {
        return 	$this->mvfConfigItem;
    }

    /**
     *  Set validator config
     *  @param     UIS_Mvf_Config_Item   $config
     *  @return    void
     */
    public function setConfig ( $config ){
        $this->mvfConfigItem = $config;
    }


    protected $varValue;

    public function getVarValue() {
        return $this->varValue;
    }

    public function setVarValue($newValue) {
        $this->varValue = $newValue;
    }



    /**
     * Is email required
     * @var bool
     */
    protected $required = false;

    public function setRequired($newVal)
    {
        $this->required = $newVal;
    }



    public function issetVar()
    {
        $result = ( isset(  $this->varValue  ) ) ?  true  : false;
        return $result;
    }

    public function setMvf($mvf)
    {
        $this->mvf = $mvf;
    }

    public function getMvf()
    {
        return $this->mvf;
    }

    public function passEmptyData()
    {
        return false;
    }


}
