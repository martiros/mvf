<?php

namespace UIS\Mvf\ValidatorTypes;

use UIS\Mvf\Util;

class UrlValidator extends BaseValidator
{
    protected $name = 'url';

    protected $defaultError = '{validation.error.url.invalid}';

    public function validate()
    {
        $valueToValidate = $this->getVarValue();
        if (!Util::isUrl($valueToValidate)) {
            return $this->makeError();
        }

        return $this->makeValid();
    }
}
