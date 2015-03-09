<?php

namespace UIS\Mvf;

use ArrayObject;

class ValidationErrorsMap extends ArrayObject
{
    public function isEmpty()
    {
        $arrayCopy = $this->getArrayCopy();
        return empty($arrayCopy);
    }
}
