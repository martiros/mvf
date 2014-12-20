<?php

namespace UIS\Mvf\FilterTypes;

class String extends BaseFilter
{
    public function trimFilter($params)
    {
        $var = $this->getVarValue();
        if (isset($params['charList'])) {
            $var = trim($var, $params['charList']);
        } else {
            $var = trim($var);
        }
        return $var;
    }

    public function ltrimFilter($params)
    {
        $var = $this->getVarValue();
        if (isset($params['charList'])) {
            $var = rtrim($var, $params['charList']);
        } else {
            $var = ltrim($var);
        }
        return $var;
    }

    public function rtrimFilter($params)
    {
        $var = $this->getVarValue();
        if (isset($params['charList'])) {
            $var = rtrim($var, $params['charList']);
        } else {
            $var = rtrim($var);
        }
        return $var;
    }

    public function stripslashesFilter()
    {
        $var = $this->getVarValue();
        $var = stripslashes($var);
        return $var;
    }


    public function strvalFilter()
    {
        $var = $this->getVarValue();
        $var = strval($var);
        return $var;
    }


    public function strtolowerFilter()
    {
        $var = $this->getVarValue();
        $var = mb_strtolower($var, 'UTF-8');
        return $var;
    }

    public function strtoupperFilter()
    {
        $var = $this->getVarValue();
        $var = mb_strtoupper($var, 'UTF-8');
        return $var;
    }

    public function scFilter()
    {
        $var = $this->getVarValue();
        return htmlspecialchars($var, ENT_QUOTES, 'UTF-8');
    }
}
