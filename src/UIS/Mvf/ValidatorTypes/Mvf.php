<?php

namespace UIS\Mvf\ValidatorTypes;

use UIS\Mvf\ValidationManager;

class Mvf extends BaseValidator
{
    protected $params = array(
        'conf' => null,
        'mapping' => null
    );

    public function validate()
    {
        $data = $this->getVarValue();
        $rulesConf = isset($this->params['conf']) ? $this->params['conf'] : $this->params['mapping'];
        $validator = new ValidationManager($data, $rulesConf);
        $validator->setParent($this->getValidationManager());

        $validationResult = $validator->validate();
        $this->setVarValue($data);
        return $validationResult;
    }

    public function allowChangeData()
    {
        return true;
    }
}
