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
}
