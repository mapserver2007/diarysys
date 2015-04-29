<?php
namespace Blog\Entry\Validate;

use WebStream\Validate\Rule\IValidate;

class Page implements IValidate
{
    /**
     * {@inheritdoc}
     */
    public function isValid($value, $rule)
    {
        return $value === null || (bool) preg_match('/^[1-9]{1,}[0-9]{0,}$/', $value);
    }
}
