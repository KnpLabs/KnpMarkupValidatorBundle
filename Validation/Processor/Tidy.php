<?php

namespace Knplabs\MarkupValidatorBundle\Validation\Processor;

use Knplabs\MarkupValidatorBundle\Validation\Validator;
use Knplabs\MarkupValidatorBundle\Validation\ProcessorInterface;

class Tidy implements ProcessorInterface
{
    protected $binary;

    /**
     * Constructor
     *
     * @param  string $binary The tidy binary to use to execute validation
     */
    public function __construct($binary)
    {
        $this->binary = $binary;
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
        // write the markup in a temporary file
        $input = tempnam('/tmp', 'tidy');
        file_put_contents($input, $markup);

        // prepare another temporary file for the output
        $output = tempnam('/tmp', 'tidy');

        exec(sprintf('%s -e %s 2> %s', $this->binary, $input, $output));

        $lines = file($output);

        // remove temporary files
        unlink($input);
        unlink($output);

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
