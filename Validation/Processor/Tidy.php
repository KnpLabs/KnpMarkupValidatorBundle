<?php

namespace Knplabs\MarkupValidatorBundle\Validation\Processor;

use Knplabs\MarkupValidatorBundle\Validation\Validator;
use Knplabs\MarkupValidatorBundle\Validation\ProcessorInterface;

class Tidy implements ProcessorInterface
{
    /**
     * Constructor
     *
     * @param  string $binary The tidy binary to use to execute validation
     */
    public function __construct()
    {
        if (!extension_loaded('tidy')) {
            throw new \RuntimeException(sprintf('You must load the tidy extension to use the \'%s\' class.', get_class($this)));
        }
    }

    /**
     * {@inheritDoc}
     */
    public function execute($markup)
    {
        $lines = $this->executeTidy($markup);

        return $this->parseLines($lines);
    }

    /**
     * Executes the tidy command and returns an array of result lines
     *
     * @param  string $markup
     *
     * @return array
     */
    public function executeTidy($markup)
    {
        $tidy = tidy_parse_string($markup);

        $lines = explode("\n", tidy_get_error_buffer($tidy));

        return $lines;
    }

    /**
     * Parses the given lines and returns an array of messages
     *
     * @param  array $lines
     *
     * @return array
     */
    public function parseLines(array $lines)
    {
        $messages = array();
        foreach ($lines as $line) {
            if (preg_match('/^line (?<line>\d+) column (?<column>\d+) - (?<type>\w+): (?<message>.+)$/', $line, $matches)) {
                $messages[] = array(
                    'type'      => 'Error' === $matches['type'] ? Validator::MESSAGE_TYPE_ERROR : Validator::MESSAGE_TYPE_WARNING,
                    'line'      => $matches['line'],
                    'column'    => $matches['column'],
                    'message'   => $matches['message']
                );
            }
        }

        return $messages;
    }
}
