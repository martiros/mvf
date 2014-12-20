<?php

namespace UIS\Mvf\FilterTypes;

abstract class BaseFilter
{
    protected $varValue = null;

    public function getVarValue()
    {
        return $this->varValue;
    }

    public function setVarValue($varValue)
    {
        $this->varValue = $varValue;
    }
}
