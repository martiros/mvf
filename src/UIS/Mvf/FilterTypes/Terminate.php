<?php

namespace UIS\Mvf\FilterTypes;

use UIS\Mvf\Exceptions\TerminateFiltersChain;

class Terminate extends BaseFilter
{
    public function ifEmptyFilter($params)
    {
        $var = $this->getVarValue();
        if ($var === '') {
            throw new TerminateFiltersChain();
        }

        return $var;
    }
}
