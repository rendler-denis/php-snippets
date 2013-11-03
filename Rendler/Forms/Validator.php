<?php

namespace Rendler\Forms;

/**
 * An implementation of the IValidator abstract class
 * to validate an email, an IP and a Timestamp
 *
 * Class Validator
 * @package Rendler\Forms
 */
class Validator
    extends IValidator
{

    /**
     * init the validation rules and results arrays
     */
    public function __construct()
    {
        //define the rules
        $this->rules = array(
            'email'      => 'validEmail',
            'timezone'   => 'validTimestamp',
            'ip_address' => 'validIP'
        );

        //define the expected results
        $this->results = array(
            'email' => array(
                '0' => '',
                '100' => 'This email address appears to be invalid.'
            ),
            'timestamp' => array(
                '0' => '',
                '100' => 'There was an error validating the timezone. Are you sure it is correct?'
            ),
            'ip_address' => array(
                '0' => '',
                '100' => 'There was an error validating the IP address. Are you sure it is correct?'
            ),

            'default' => 'Unknown error occurred!'
        );

        return true;
    }

    /**
     * validate an email address
     *
     * @param string $data form data
     *
     * @return string the result of the check
     */
    public function validEmail($data)
    {
        $match = null;

        $result = preg_match('/^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/i', $data, $match);
        if ($result !== 1 || ($data !== $match[0])) {
            return '100';
        }

        return '0';
    }

    /**
     * validate a timestamp
     *
     * @param string $data form data
     *
     * @return string the result of the check
     */
    public function validTimestamp($data)
    {
        $timeNow = new \DateTime(date('Y-m-d H:i:s'), new \DateTimeZone('UTC'));
        $diff = $timeNow->diff(new \DateTime(date('Y-m-d H:i:s', $data), new \DateTimeZone('UTC')), true);

        if ($diff->days > 1) {
            return '100';
        }

        return '0';
    }

    /**
     * validate an IP
     *
     * @param string $data form data
     *
     * @return string the result of the check
     */
    public function validIP($data)
    {
        $match = null;
        $regexp = '/^([01]?\\d\\d?|2[0-4]\\d|25[0-5])\\.' .
                 '([01]?\\d\\d?|2[0-4]\\d|25[0-5])\\.' .
                 '([01]?\\d\\d?|2[0-4]\\d|25[0-5])\\.' .
                 '([01]?\\d\\d?|2[0-4]\\d|25[0-5])$/';

        $result = preg_match($regexp, $data, $match);
        if ($result !== 1 || $data !== $match[0]) {
            return '100';
        }

        return '0';
    }

}
