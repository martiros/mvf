<?php

namespace UIS\Mvf\FilterTypes;

class IfFilter extends BaseFilter
{
    public function emptyFilter($params)
    {
        $var = $this->getVarValue();
        if (empty($var)) {
            return isset($params['true']) ? $params['true'] : true;
        }
        return isset($params['false']) ? $params['false'] : false;
    }

    public function notEmptyFilter($params)
    {
        $var = $this->getVarValue();
        if (empty($var)) {
            return isset($params['false']) ? $params['false'] : false;
        }
        return isset($params['true']) ? $params['true'] : true;
    }
}
