<?php

namespace UIS\Mvf;

class ValidationError
{
    private $mainError = null;
    private $requiredError = null;
    private $errorsArray = array();
    private $params	= array();

    public function __construct(){

    }

    public function errorMessage ( ){

        if ( $this->requiredError  !== null ){
            return $this->requiredError;
        }

        if ( !empty( $this->errorsArray ) ) {
            return implode( $this->errorsArray , ', '  );
        }
        return $this->mainError;

    }

    public function setError( $mainError ){
        $this->mainError = $mainError;
        return $this;
    }

    public function addCustomeError( $key , $value ){
        if( $value != null ){
            $this->errorsArray[ $key ] = $value;
        }
    }

    public function setRequierdError( $requiredError ) {
        $this->requiredError = $requiredError;
        return $this;
    }

    /**
     * @return array
     */
    public function getParams(){
        return $this->params;
    }

    /**
     * @param array $params
     */
    public function setParams($params){
        $this->params = $params;
    }

    /**
     *  @return boolean
     */
    public function isValid(){
        if( $this->mainError == null && $this->requiredError == null  && empty( $this->errorsArray ) ){
            return true;
        }
        return false;
    }
}
