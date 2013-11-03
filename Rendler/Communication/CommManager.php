<?php
namespace Rendler\Communication;

/**
 * A cUrl Wrapper
 *
 * Class CommManager
 * @package Rendler\Communication
 */
class CommManager
{

    /**
     * @var null|resource
     */
    private $curl = null;

    /**
     * init class and cUrl
     *
     * @param string $url      the URL to pass to cUrl
     * @param array  $settings an array of settings for cUrl
     */
    public function __construct($url = null, $settings = null)
    {
        //init cUrl
        try {

            if (!$this->isCurlPresent()) {
                throw new \Exception('cUrl is not present!', '404', false);
            }
            //if there is an url pass it to cURL
            if (isset($url)) {
                $this->curl = \curl_init($url);
            } else {
                $this->curl = \curl_init();
            }
            $this->setopt(CURLOPT_TIMEOUT, '20');

            //if you want to setup cUrl at init, just do it
            if (isset($settings)) {
                $this->setopt($settings);
            }

        } catch (\Exception $e) {
            //handle the exceptions
        }

    }

    /**
     * clear the cUrl connections and object
     */
    public function __destruct()
    {
        \curl_close($this->curl);
        unset($this->curl);
    }

    /**
     * set cUrl options
     *
     * @param int /array $options the cUrl option const. In case of an array
     *                            the $data param will be null
     * @param string    $data     the value to be set for the option
     */
    public function setopt($options, $data = null)
    {
        if (!is_array($options)) {
            \curl_setopt($this->curl, $options, $data);
        } elseif (!is_null($options)) {
            \curl_setopt_array($this->curl, $options);
        }
    }

    /**
     * execute the cUrl command
     *
     * @param array $options an array of options to be used by cUrl
     *
     * @return mixed the execution result of cUrl
     */
    public function exec($options = null)
    {
        if (isset($options)) {
            $this->setopt($options);
        }

        return \curl_exec($this->curl);
    }

    /**
     * reset the cUrl object
     *
     * @throws \Exception
     */
    public function reset()
    {
        try {
            if (!$this->isCurlPresent()) {
                throw new \Exception('cUrl is not present!', '404', false);
            }

            \curl_close($this->curl);

            $this->curl = \curl_init();

            $this->setopt(CURLOPT_TIMEOUT, '20');

        } catch (\Exception $exc) {
            //handle the exception
        }
    }


    /**
     * method used for debugging cUrl connections
     *
     * @return array cUrl debug info
     */
    public function debug()
    {
        return array(
            'info' => \curl_getinfo($this->curl),
            'error' => \curl_errno($this->curl)
        );
    }

    /**
     * check if the cUrl extension is loaded
     *
     * @return bool
     */
    private function isCurlPresent()
    {
        if (!function_exists("curl_init") &&
            !function_exists("curl_setopt") &&
            !function_exists("curl_exec") &&
            !function_exists("curl_close")
        ) {
            return false;
        } else {
            return true;
        }
    }

}
