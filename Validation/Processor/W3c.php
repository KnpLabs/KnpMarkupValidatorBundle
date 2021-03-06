<?php

namespace Knp\Bundle\MarkupValidatorBundle\Validation\Processor;

use Knp\Bundle\MarkupValidatorBundle\Validation\Validator;
use Knp\Bundle\MarkupValidatorBundle\Validation\ProcessorInterface;

class W3c implements ProcessorInterface
{
    protected $uri;
    protected $options;

    /**
     * Constructor
     *
     * @param  string $uri     URI of the validation service
     * @param  array  $options An optional array of cURL options
     */
    public function __construct($uri, array $options = array())
    {
        if (!extension_loaded('curl')) {
            throw new \RuntimeException(sprintf('You must load the cURL extension to use the \'%s\' class.', get_class($this)));
        }

        $this->uri = $uri;
        $this->options = $options;
    }

    /**
     * {@inheritDoc}
     */
    public function execute($markup)
    {
        $output = $this->callService($markup);

        $messages = array();
        foreach((array)$output->messages as $message) {
            $message = (array) $message;
            if(false !== empty($message)) {
                $messages[] = $this->normalizeMessage($message);
            }
        }

        return $messages;
    }

    /**
     * Nomalizes the given message and returns it
     *
     * @param  stdObject $message
     */
    public function normalizeMessage($message)
    {
        if (isset($message['subtype']) && 'warning' === $message['subtype']) {
            $type = Validator::MESSAGE_TYPE_WARNING;
        } else {
            $type = Validator::MESSAGE_TYPE_ERROR;
        }

        return array(
            'type'      => $type,
            'line'      => $message['lastLine'],
            'column'    => $message['lastColumn'],
            'message'   => $message['explanation']
        );
    }

    /**
     * Calls the validation service
     *
     * @param  $markup
     *
     * @return
     */
    public function callService($markup)
    {
        $curl = curl_init();
        curl_setopt_array($curl, $this->mergeOptions(array(
            CURLOPT_POSTFIELDS  => array(
                'fragment'  => $markup,
                'output'    => 'json'
            ),
        )));

        $output = curl_exec($curl);
        if (false === $output or empty($output)) {
            $error = curl_error($curl);
            curl_close($curl);
            throw new \RuntimeException(sprintf('Could not call the validation service due to a cURL error: %s.', $error));
        }

        curl_close($curl);

        return (object) json_decode($output);
    }

    /**
     * Merges cURL options and returns it as an associative array
     *
     * @return array
     */
    public function mergeOptions(array $options = array())
    {
        $mergedOptions = $this->options;
        $mergedOptions[CURLOPT_URL] = $this->uri;
        $mergedOptions[CURLOPT_RETURNTRANSFER] = true;
        foreach ($options as $key => $value) {
            $mergedOptions[$key] = $value;
        }

        return $mergedOptions;
    }
}
