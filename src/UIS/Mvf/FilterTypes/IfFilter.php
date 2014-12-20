<?php

namespace UIS\Mvf\FilterTypes;

class IfFilter extends BaseFilter
{
    public function emptyFilter($params)
    {
        $var = $this->getVarValue();
        if (empty($var)) {
            return isset($params['true']) ? true : false;
        }
        return isset($params['false']) ? false : true;
    }

    public function notEmptyFilter($params)
    {
        $var = $this->getVarValue();
        if (empty($var)) {
            return isset($params['false']) ? false : true;
        }
        return isset($params['true']) ? true : false;
    }
}
