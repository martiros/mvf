<?php

namespace UIS\Mvf;

use ArrayObject;

class ValidationErrorsMap extends ArrayObject
{
    public function isEmpty()
    {
        return empty($this->getArrayCopy());
    }
}
