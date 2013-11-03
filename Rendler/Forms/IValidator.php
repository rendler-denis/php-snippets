<?php

namespace Rendler\Forms;

/**
 * Abstract class used in form validation
 *
 * Class IValidator
 * @package Rendler\Forms
 */
abstract class IValidator
{

    /**
     * @var array
     */
    protected $rules = array();

    /**
     * @var array
     */
    protected $results = array();

    /**
     * @var array
     */
    protected $formErrors = array();

    /**
     * validate the form data according to the rules set in the
     * inheriting classes
     *
     * @param array $data array of form data
     *
     * @return array|bool
     */
    public function validateData($data)
    {
        if (empty($this->rules)) {
            return false;
        }

        foreach ($data as $field => $value) {
            if (array_key_exists($field, $this->rules) && method_exists($this, $this->rules[ $field ])) {
                $result = $this->{ $this->rules[ $field ] }( $value );
                if (array_key_exists($result, $this->results[ $field ])) {
                    if ($this->results[ $field ][ $result ] === '') {
                        continue;
                    }
                    $this->formErrors[ $field ] = $this->results[ $field ][ $result ];
                } else {
                    $this->formErrors[ $field ] = $this->results['default'];
                }
            }
        }

        return $this->formErrors;
    }

}
