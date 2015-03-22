<?php
namespace UIS\Mvf\FilterTypes;

class Convert extends BaseFilter
{
    public function intFilter($params)
    {
        $var = $this->getVarValue();
        if (is_object($var) || is_array($var)) {
            return 0;
        }
        return intval($var);
    }

    public function arrayFilter($params)
    {
        $var = $this->getVarValue();
        if (is_array($var)) {
            return $var;
        }

        if (is_object($var)) {
            return json_decode(json_encode($var), true);
        }
        return [];
    }
}
