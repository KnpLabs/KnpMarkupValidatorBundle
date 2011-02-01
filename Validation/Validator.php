<?php

namespace Knplabs\MarkupValidatorBundle\Validation;

/**
 * Markup validator
 *
 * @author Antoine Hérault <antoine.herault@gmail.com>
 */
class Validator
{
    const MESSAGE_TYPE_ERROR = 'error';
    const MESSAGE_TYPE_WARNING = 'warning';

    protected $processor;

    public function __construct(ProcessorInterface $processor)
    {
        $this->processor = $processor;
    }

    /**
     * Validates the given markup
     *
     * @param  string $markup
     *
     * @return Result
     */
    public function validate($markup)
    {
        $result = new Result();

        $messages = $this->processor->execute($markup);
        foreach ($messages as $message) {
            if (Validator::MESSAGE_TYPE_ERROR === $message['type']) {
                $result->addError(new Error($message['line'], $message['column'], $message['message']));
            } else if (Validator::MESSAGE_TYPE_WARNING === $message['type']) {
                $result->addWarning(new Warning($message['line'], $message['column'], $message['message']));
            }
        }

        return $result;
    }

    /**
     * Validates the markup from the given uri
     *
     * @param  string $uri
     *
     * @return Result
     */
    public function validateUri($uri)
    {
        if (!extension_loaded('curl')) {
            throw new \RuntimeException(sprintf('You must load the cURL extension to validate a uri.'));
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $uri);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $output = curl_exec($ch);

        if (false === $output) {
            $error = curl_error($ch);
            curl_close($ch);
            throw new \RuntimeException(sprintf('Could not get the markup due to a cURL error: %s.', $error));
        }

        curl_close($ch);

        return $this->validate($output);
    }

    /**
     * Validates the markup from the specified file
     *
     * @param  string $filename
     *
     * @return Result
     */
    public function validateFile($filename)
    {
        if (!file_exists($filename) || !is_readable($filename)) {
            throw new \InvalidArgumentException(sprintf('The file \'%s\' does not exists or is unreadable.', $filename));
        }

        return $this->validate(file_get_contents($filename));
    }
}
