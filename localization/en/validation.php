<?php

return [
    'error' => [
        'required' => 'The :attribute field is required.',
        'array'    => [
            'invalid'         => 'The :attribute must be an array.',
            'min_length'      => 'The :attribute must have at least :min_length items.',
            'max_length'      => 'The :attribute may not have more than :max_length items.',
            'allowed_values'  => 'The :attribute contains invalid item.',
            'array_unique'    => 'The :attribute must contain only unique value.',
            'items_validator' => 'The :attribute contains invalid item.',
        ],
        'bool' => [
            'invalid' => 'The :attribute field must be true or false.',
        ],
        'date' => [
            'invalid'  => 'The :attribute is not a valid date.',
            'min_date' => 'The :attribute must be a date after :min_date.',
            'max_date' => 'The :attribute must be a date before :max_date.',
        ],
        'digit' => [
            'invalid'    => 'The :attribute must contain only numbers.',
            'min_length' => 'The :attribute must contain at least :min_length numbers.',
            'max_length' => 'The :attribute must contain max :max_length numbers.',
        ],
        'email' => [
            'invalid' => 'The :attribute must be a valid email address.',
        ],
        'enum' => [
            'invalid' => 'The selected :attribute is invalid.',
        ],
        'float' => [
            'invalid'      => 'The :attribute must be a decimal number',
            'min_value'    => 'The :attribute may not be greater than :min_value.',
            'max_value'    => 'The :attribute must be at least :max_value.',
            'max_decimals' => 'The :attribute must contain max :max_decimals decimal digits.',
        ],
        'function' => [
            'invalid' => 'The :attribute is invalid.',
        ],
        'int' => [
            'invalid'   => 'The :attribute must be an integer.',
            'min_value' => 'The :attribute may not be greater than :min_value.',
            'max_value' => 'The :attribute must be at least :max_value.',
        ],
        'password' => [
            'invalid'                   => 'The :attribute is invalid.',
            'min_length'                => 'Short passwords are easy to guess. The :attribute must be at least :min_length characters.',
            'max_length'                => 'The :attribute may not be greater than :max_length characters.',
            'security_level_not_medium' => 'Password should contain letters(a-z) and at least one numbers(0-9).',
            'security_level_not_height' => 'Password should contain letters(a-z), numbers(0-9) and at least one special character(+-_?=.,...)',
        ],
        'phone'  => [
            'invalid' => 'The :attribute format is invalid.',
        ],
        'string' => [
            'invalid'    => 'The :attribute must be a string.',
            'min_length' => 'The :attribute must be at least :min_length characters.',
            'max_length' => 'The :attribute may not be greater than :max_length characters.',
            'regexp'     => 'The :attribute format is invalid.',
        ],
        'url' => [
            'invalid' => 'The :attribute format is invalid.',
        ],
        'username' => [
            'invalid'     => ':Attribute may only contain letters (a-z), numbers, and periods.',
            'min_length'  => ':Attribute is too short (minimum is :min_length characters)',
            'max_length'  => ':Attribute is too long (maximum is :max_length characters)',
            'first_char'  => 'The first character of :attribute should be a letter (a-z) or number.',
            'last_char'   => 'The last character of :attribute should be a letter (a-z) or number.',
            'punctuation' => 'A fan of punctuation! Alas, :attribute can\'t have consecutive periods.',
        ],
    ],
];
